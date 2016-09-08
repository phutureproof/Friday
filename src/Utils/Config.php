<?php
/**
 * Config
 *
 * Simple ini file handling
 */
namespace PhutureProof\Utils;

use Composer\Downloader\FilesystemException;

class Config
{
    private static $_loadedFiles = [];

    public static function loadConfigFromFile($file)
    {
        $sanitisedFile = md5($file);

        if ( ! isset(self::$_loadedFiles[$sanitisedFile])) {
            if (file_exists($file)) {
                self::$_loadedFiles[$sanitisedFile] = json_decode(file_get_contents($file), true);
            } else {
                throw new FilesystemException("Couldn't load config file: {$file}");
            }
        }

        return self::$_loadedFiles[$sanitisedFile];
    }

    public static function saveConfigToFile()
    {
        // TODO: Implement this.
    }
}