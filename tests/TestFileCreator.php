<?php

namespace tests\TomPHP\ConfigServiceProvider;

trait TestFileCreator
{
    /**
     * @var string
     */
    private $configFilePath;

    /**
     * @param string $name
     *
     * @return string
     */
    protected function getTestPath($name)
    {
        $this->ensurePathExists();

        return "{$this->configFilePath}/$name";
    }

    /**
     * @param string $name
     * @param string $content
     */
    protected function createTestFile($name, $content = 'test content')
    {
        $this->ensurePathExists();

        file_put_contents("{$this->configFilePath}/$name", $content);
    }

    private function deleteTestFiles()
    {
        $this->ensurePathExists();

        // Test for safety!
        if (strpos($this->configFilePath, __DIR__) !== 0) {
            throw new \Exception('DANGER!!! - Config file is not local to this project');
        }

        $files = glob("{$this->configFilePath}/*");

        foreach ($files as $file) {
            unlink($file);
        }
    }

    private function ensurePathExists()
    {
        $this->configFilePath = __DIR__ . '/.test-config';

        if (!file_exists($this->configFilePath)) {
            mkdir($this->configFilePath);
        }
    }
}
