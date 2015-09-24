<?php

namespace b3nl\LSetup\Http\Controllers\Setup;

use b3nl\LSetup\Jobs\ChangeFile;
use b3nl\LSetup\Http\Controllers\Controller;
use b3nl\LSetup\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ConfigController extends Controller
{
    /**
     * Returns the jobs for the config files.
     * @return ChangeFile[]
     */
    protected function getFileJobs()
    {
        $return = [];

        if ($savedConfig = config('lsetup.env.files')) {
            foreach ($savedConfig as $fileConfig) {
                /** @var ChangeFile $changeJob */
                $changeJob = app('laravel-setup.job.change-env-file');

                if (!is_callable($fileConfig)) {
                    if (!is_object($fileConfig)) {
                        $fileConfig = app($fileConfig);
                    } // if

                    $fileConfig = [$fileConfig];
                } // if

                $returned = call_user_func_array($fileConfig, array($changeJob));

                if (is_object($returned)) {
                    $changeJob = $returned;
                } // if

                $return[] = $changeJob;
            } // foreach
        } // if

        return $return;
    } // function

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $returnConfig = [];

        /** @var ChangeFile $changeJob */
        foreach ($this->getFileJobs() as $changeJob) {
            if ($props = $changeJob->getProperties()) {
                foreach ($props as $index => $prop) {
                    $returnConfig[$key = array_shift($prop)] = array_combine(['validator', 'default'], $prop);

                    $returnConfig[$key]['value'] = getenv($key) ?: null;
                    $returnConfig[$key]['isRequired'] = strpos($returnConfig[$key]['validator'], 'required') !== false;
                } // foreach
            } // if
        } // foreach

        return $returnConfig;
    } // function

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        /** @var ChangeFile $job */
        foreach ($this->getFileJobs() as $job) {
            $destination = $job->getDestination();
            $fileStarted = false;
            $sourceParams = ($source = $job->getSource()) ? parse_ini_file($source) : [];
            $mergedParams = array_merge($sourceParams, $request->all());

            foreach ($mergedParams as $key => $value) {
                $fileStarted = (bool)file_put_contents(
                    $destination, sprintf("%s=%s\n", $key, $value), $fileStarted ? FILE_APPEND : 0
                );
            } // foreach
        } // foreach

        return $mergedParams;
    } // function
}
