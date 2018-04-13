<?php
/**
 * TODO
 * @author Aaron Melhaff <amelhaff2@mail.greenriver.edu>
 * @author Scott Medlock <squmed@gmail.com>
 * @version 1.0
 */

session_start();

ini_set('display_errors',1);
error_reporting(E_ALL);

// Require f3
require_once('vendor/autoload.php');

$f3 = Base::instance();
$f3->set('DEBUG', 3);

// Model::connect();

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

$f3->run();