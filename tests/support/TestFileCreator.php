<?php

namespace tests\support;

trait TestFileCreator
{
    /**
     * @var string
     */
    private $configFilePath;

    protected function tearDown()
    {
        $this->deleteTestFiles();
    }

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
     * @param string $filename
     * @param array  $config
     */
    protected function createPHPConfigFile($filename, array $config)
    {
        $code = '<?php return ' . var_export($config, true) . ';';

        $this->createTestFile($filename, $code);
    }

    /**
     * @param string $filename
     * @param array  $config
     */
    protected function createJSONConfigFile($filename, array $config)
    {
        $code = json_encode($config);

        $this->createTestFile($filename, $code);
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
        $this->configFilePath = __DIR__ . '/../.test-config';

        if (!file_exists($this->configFilePath)) {
            mkdir($this->configFilePath);
        }
    }
}
