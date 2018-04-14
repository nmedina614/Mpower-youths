<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 12:59 PM
 */

// index.php -> routes.php -> THIS -> database/messenger/validator -> views

function getGalleryImages() {
    $allFiles = scandir('assets/images/gallery');

    require('model/validator.php');

    $images = array();

    for($i = 0; $i < count($allFiles); $i++) {
        if(Validator::validImageFile($allFiles[$i])) {
            $images[] = BASE.'/assets/images/gallery/'.$allFiles[$i];
        }
    }

    return $images;
}