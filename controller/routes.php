<?php
/**
 * Created by PhpStorm.
 * User: scottmedlock
 * Date: 4/13/18
 * Time: 12:57 PM
 */

$GLOBALS['f3'];

$f3->route('GET /', function($f3) {

    // Title to use in template.
    $title = "M-Power Youth";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_home.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_home.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /gallery', function($f3) {


    require('model/logic.php');


    $f3->set('images', getGalleryImages());

    // Title to use in template.
    $title = "M-Power Gallery";

    // List of paths to stylesheets.
    $styles = array(
        BASE.'/assets/styles/gallery.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_gallery.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /account', function($f3) {

    // Title to use in template.
    $title = "Account Management";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_home.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_account.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});