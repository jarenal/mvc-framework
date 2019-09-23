<?php
declare(strict_types=1);

namespace Jarenal\Core;

use Exception;
use Symfony\Component\Yaml\Yaml;

/**
 * Class Config
 * @package Jarenal\Core
 */
class Config implements ConfigInterface
{
    /**
     * @var array
     */
    private $settings;

    /**
     * Config constructor.
     * @param string $configFile
     * @throws Exception
     */
    public function __construct(string $configFile)
    {
        try {
            if (!file_exists($configFile)) {
                throw new Exception("File '$configFile' doesn't exist");
            }
            $this->settings = Yaml::parseFile($configFile, Yaml::PARSE_CONSTANT);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param string $parameter
     * @return mixed|null
     */
    public function get(string $parameter)
    {
        if (isset($this->settings[$parameter])) {
            return $this->settings[$parameter];
        } else {
            return null;
        }
    }
}
