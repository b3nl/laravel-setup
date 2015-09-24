<?php

namespace b3nl\LSetup\Jobs;

use b3nl\LSetup\Jobs\Job;
use Illuminate\Contracts\Bus\SelfHandling;
use InvalidArgumentException;

class ChangeFile extends Job implements SelfHandling
{
    /**
     * The destination for copy-parsing the source file.
     * @var string
     */
    protected $destination = '';

    /**
     * The source file.
     * @var string
     */
    protected $source = '';

    /**
     * The set property array.
     * @var array
     */
    protected $properties = [];

    /**
     * Returns the path to the destination file.
     * @return string
     */
    public function getDestination()
    {
        return $this->destination;
    } // function

    /**
     * Returns the path to the source file.
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    } // function

    /**
     * Returns the array of the properties.
     * @return array
     */
    public function getProperties()
    {
        return $this->properties;
    } // function

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        exit(__METHOD__);
    } // function

    public function setConfigVars(array $vars)
    {
        return [
            'field' => [
                'optional', 'default', 'validator'
            ]
        ];
    }

    /**
     * Sets the destination file.
     * @param string $destination
     * @return ChangeJob
     * @throws InvalidArgumentException If the destination directory is not writable.
     */
    protected function setDestination($destination)
    {
        $this->destination = $destination;

        if (!is_writable(dirname($this->destination))) {
            throw new InvalidArgumentException(sprintf('The destination directory %s is not writable.', $destination));
        } // if

        return $this;
    } // function

    /**
     * Sets the parsed file.
     * @param string $source Which file should be the template?
     * @param string $destination The destination file.
     * @return ChangeJob
     */
    public function setFile($destination, $source = '')
    {
        if ($source) {
            $this->setSource($source);
        } // if

        return $this->setDestination($destination);
    } // function

    /**
     * Sets the properties array.
     * @param array $properties
     * @return ChangeFile
     */
    public function setProperties(array $properties)
    {
        $this->properties = $properties;

        return $this;
    } // function

    /**
     * Sets the source file.
     * @param string $source
     * @return ChangeFile
     * @throws InvalidArgumentException If the source file is not readable.
     */
    protected function setSource($source)
    {
        $this->source = $source;

        if (!is_readable($source)) {
            throw new InvalidArgumentException(
                sprintf('The source file %s could not be found or is not readable.', $source)
            );
        } // if

        return $this;
    } // function
}
