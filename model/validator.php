<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 1:00 PM
 */
class Validator
{
    public static function validImageFile($filename)
    {
        // Analyze filename.
        $path      = pathinfo($filename);

        // Find file extension.
        $extension = strtolower($path['extension']);

        // Return whether file extension is an image.
        return (
            $extension == 'jpg'  ||
            $extension == 'jpeg' ||
            $extension == 'png'  ||
            $extension == 'gif'
        );
    }
}

