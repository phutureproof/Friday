<?php

namespace PhutureProof\Utils;

class Config
{
    public static function loadConfigFromFile($file)
    {
        return parse_ini_file($file, true);
    }
}