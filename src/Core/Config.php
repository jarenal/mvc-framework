<?php

namespace Jarenal\Core;

use Symfony\Component\Yaml\Yaml;

class Config implements ConfigInterface
{
    private $settings;

    public function __construct($configFile)
    {
        $this->settings = Yaml::parseFile($configFile, Yaml::PARSE_CONSTANT);
    }

    public function get($parameter)
    {
        if (isset($this->settings[$parameter])) {
            return $this->settings[$parameter];
        } else {
            return null;
        }
    }
}
