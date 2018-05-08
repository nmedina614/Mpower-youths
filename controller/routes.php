<?php
/**
 * File containing routing information.
 *
 * @author Aaron Melhaff
 * @author Scott Medlock
 * @author Kyle Johnson
 * @author Nolan Medina
 *
 * @since 4/30/2018
 */

$GLOBALS['f3'];

$f3->route('GET /', function($f3) {

    require('model/logic.php');
    $f3->set('events', Logic::getEvents());

    // Title to use in template.
    $title = "M-Power Youth";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_home.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_home.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_home.js',
        '//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit'
    );

    $footer = 'views/_footer.html';

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET|POST /gallery', function($f3) {

    if(isset($_POST['submit']) && $f3->get('isAdmin')) {
        $f3->set('invalid', Logic::submitNewImage($_FILES['image'], $_POST['caption']));
    }

    $f3->set('images', Logic::getGalleryImages());

    // Title to use in template.
    $title = "M-Power Gallery";

    // List of paths to stylesheets.
    $styles = array(
        BASE.'/assets/styles/_gallery.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_gallery.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_gallery.js'
    );


    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('POST /ajax-delete-image', function($f3) {
    if($f3->get('isAdmin')) {
        Logic::deleteGalleryImage($_POST['image']);
    } else {
        echo json_encode('Invalid Credentials!');
    }

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
        'views/_account.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_account.js'
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /staff', function($f3) {

    require('model/logic.php');
    $f3->set('StaffMembers', Logic::getAllStaff());

    // Title to use in template.
    $title = "M-Power Staff";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_home.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_staff.html',
        'views/_footer.html'
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

$f3->route('GET /staff2', function($f3) {

    // Title to use in template.
    $title = "M-Power Staff";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_home.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_staff2.html',
        'views/_footer.html'
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



// Login route.
$f3->route('GET|POST /login', function($f3) {

    // Title to use in template.
    $title = "Login";

    // List of paths to stylesheets.
    $styles = array(
        BASE.'/assets/styles/_login.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_login.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title',    $title);
    $f3->set('styles',   $styles);
    $f3->set('includes', $includes);
    $f3->set('scripts',  $scripts);

    if(isset($_POST['submit'])) {

        $result = Logic::adminLogin($_POST['username'], $_POST['password']);

        // If login is successful, redirect to main page.
        if($result != false) {

            $f3->reroute('/');

        } else { // Otherwise generate error.

            $f3->set('invalid', true);
        }
    }

    session_unset();

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /NewEvent', function($f3) {

    require('model/logic.php');
    $f3->set('events', Logic::getEvents());

    // Title to use in template.
    $title = "M-Power Youth";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_newEvent.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_NewEvent.html',
        'views/_footer.html'
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

$f3->route('GET /event', function($f3) {

    require('model/logic.php');
    $f3->set('events', Logic::getEvents());

    // Title to use in template.
    $title = "M-Power Youth: Events";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_event.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /contact', function($f3) {

    // Title to use in template.
    $title = "M-Power Youth: Contact Us";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_event.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_contact.html');
});

$f3->route('GET /donate', function($f3) {

    // Title to use in template.
    $title = "M-Power Youth: Donate!";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_event.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_donate.html');
});

$f3->route('GET /join', function($f3) {

    // Title to use in template.
    $title = "M-Power Youth: Join Us!";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_event.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_join.html');
});

$f3->route('GET /rent', function($f3) {

    // Title to use in template.
    $title = "M-Power Youth: Rent-A-Instrument";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_event.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_rent.html');
});

$f3->route('GET /videos', function($f3) {

    // Title to use in template.
    $title = "M-Power Youth: Videos";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_event.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_videos.html');
});