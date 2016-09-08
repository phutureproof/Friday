<?php

namespace PhutureProof\Utils;

use Composer\Downloader\FilesystemException;

/**
 * Class Views
 * @package PhutureProof\Utils
 */
class Views
{
    /**
     * @param string $viewFile
     * @param array $data
     *
     * @return string
     * @throws FilesystemException
     */
    public static function load($viewFile, $data = [])
    {
        $sanitisedViewFile = trim(preg_replace('!\.php$!', '', $viewFile)) . '.php';
        die($sanitisedViewFile);

        $file = FRIDAY_VIEW_DIR . '/' . $sanitisedViewFile;
        if (file_exists($file)) {
            extract($data);
            ob_start();
            require($file);
            $content = ob_get_clean();

            return $content;
        } else {
            throw new FilesystemException("Couldn't load view: {$viewFile}");
        }
    }
}