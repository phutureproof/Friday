<?php

namespace PhutureProof\Utils;

use PhutureProof\Exceptions\FileMissing;

class Views
{
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
            throw new FileMissing("Couldn't load view: {$file}");
        }
    }
}