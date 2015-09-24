<?php

namespace b3nl\LSetup\Console\Commands;

use b3nl\LSetup\Jobs\ChangeFile;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\Console\Input\InputOption;

class EnvConfiguration extends Command
{
    /**
     * The name of the console command.
     * @var string
     */
    protected $name = 'setup:config-env';

    /**
     * The console command description.
     * @var string
     */
    protected $description = 'Configures the project environment.';

    /**
     * Returns an array of jobs for the given config.
     * @return \b3nl\LSetup\Jobs\Job[]
     */
    protected function getJobsForConfig()
    {
        $fileConfigs = config('lsetup.env.files', []);
        $jobs = [];

        if ($fileConfigs && (is_array($fileConfigs))) {
            foreach ($fileConfigs as $fileConfig) {
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

                $jobs[] = $changeJob;
            } // foreach
        } // if

        return $jobs;
    } // function

    /**
     * Get the console command options.
     * @return array
     */
    protected function getOptions()
    {
        $options = [];

        $jobs = $this->getJobsForConfig();

        foreach ($jobs as $job) {
            foreach ($job->getProperties() as $property) {
                $options[] = [
                    $name = array_shift($property),
                    null,
                    @$property[1] !== null ? InputOption::VALUE_OPTIONAL : InputOption::VALUE_REQUIRED,
                    trans('lsetup.' . strtolower($name) . '.desc'),
                    @$property[1]
                ];
            } // foreach
        } // foreach

        return $options;
    } // function

    /**
     * Returns the option values for the configured jobs.
     * @return array
     */
    protected function getOptionValuesForJobs()
    {
        $values = [];

        foreach ($this->getJobsForConfig() as $job) {
            foreach ($job->getProperties() as $property) {
                $values[$name = $property[0]] = $this->option($name);
            } // foreach
        } // foreach

        return $values;
    } // function

    /**
     * Returns the validator rules for the configured jobs.
     * @return array
     */
    protected function getValidatorForJobs()
    {
        $rules = [];

        foreach ($this->getJobsForConfig() as $job) {
            foreach ($job->getProperties() as $property) {
                $rules[array_shift($property)] = array_shift($property);
            } // foreach
        } // foreach

        return $rules;
    } // function

    /**
     * Execute the console command.
     * @return bool
     */
    public function handle()
    {
        $jobs = $this->getJobsForConfig();

        /** @var \Illuminate\Contracts\Validation\Validator $validator */
        $validator = Validator::make(
            $this->getOptionValuesForJobs(),
            $this->getValidatorForJobs()
        );

        if ($validator->fails()) {
            var_dump($validator->getMessageBag()->toArray());
        } // if

        exit(var_dump($this->getOptionValuesForJobs()));

        foreach ($jobs as $job) {
            $job->handle();
        } // foreach

        return true;
    } // function
}
