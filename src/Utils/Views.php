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
        // tidy up the requested view
        $sanitisedViewFile = trim(preg_replace('!\.php$!', '', $viewFile)) . '.php';

        // path file concat
        $file = FRIDAY_VIEW_DIR . '/' . $sanitisedViewFile;

        if (file_exists($file)) {
            // using the output buffer
            // to grab the parsed view
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