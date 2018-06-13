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

$f3->route('GET|POST /', function($f3) {

    $f3->set('carouselItems', Logic::getCarouselItems());
    $f3->set('events', Logic::getUpcomingEvents());
    $f3->set('pastOrUpcoming', 'Upcoming');

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
        'views/_eventDisplay.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_home.js',
    );

    $footer = 'views/_footer.html';

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /carousel_edit', function($f3) {

    if (!$f3->get('isAdmin')) {
        $f3->reroute('/login');
    }

    $f3->set('carouselItems', Logic::getCarouselItems());

    // Title to use in template.
    $title = "M-Power Youth: edit carousel";

    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_carouselEdit.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_carouselEdit.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_carouselEdit.js',
    );

    $footer = 'views/_footer.html';

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_base.html');
});

// carousel-modify is the route for a submitted form from carousel_edit
$f3->route('POST /carousel-modify', function($f3) {
    // this should work for adding and editing.
    if ($f3->get('isAdmin') && isset($_POST['submit'])) {

        $imageURL = $_POST['imageURL']; // sets variable to old portrait to start

        // check if a new image has been uploaded
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $imageURL = Logic::submitImageToFolder($_FILES['image'], 'carousel');
            $portraitSubstr = substr($imageURL, 0, 5);

            if ($portraitSubstr === "File " || $portraitSubstr === "Only ") { // failed image upload
                $imageURL = $_POST['imageURL']; // reassign to old portraitURL
            } else if (!empty($_POST['imageURL']))  { // successful image upload, delete old image if it exists
                $imageNameWithoutFolder = substr($_POST['imageURL'], strrpos($_POST['imageURL'], '/') + 1);
                $imageFolder = 'staffportraits';
                Logic::deleteImage($imageNameWithoutFolder, $imageFolder);
            }
        }

        // if editing an existing staff member, staffid will be set.
        if ($_POST['idcarousel'] == -1) {
            $newItemPageOrder = 1;

            if (sizeof(Logic::getCarouselItems()) > 0) {
                $newItemPageOrder = Logic::getMaxPageOrder('carousel') + 1;
            }

            $carouselItem = new CarouselItem(-1, $_POST['header'], $_POST['paragraph'],
                $imageURL, $_POST['buttonLink'], $_POST['buttonText'], $newItemPageOrder);
            Logic::addCarouselItem($carouselItem);
        } else {
            $carouselItem = new CarouselItem($_POST['idcarousel'], $_POST['header'],
                $_POST['paragraph'], $imageURL, $_POST['buttonLink'],
                $_POST['buttonText'], $_POST['pageOrder']);
            Logic::updateCarouselItem($carouselItem);
        }
    }

    $f3->reroute('/carousel_edit');
});

$f3->route('GET /past_events', function($f3) {
    $f3->set('events', Logic::getPastEvents());
    $f3->set('pastOrUpcoming', 'Past');

    // Title to use in template.
    $title = "M-Power Youth";

    // List of paths to stylesheets.
    $styles = array(
        BASE.'/assets/styles/_home.css'
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_eventDisplay.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_home.js',
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);


    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('POST /event-modify', function($f3) {
    // this should work for adding and editing.
    if ($f3->get('isAdmin') && isset($_POST['submit'])) {
        if ($_POST['eventid'] == -1) {
            $event = new Event(-1, $_POST['eventTitle'], $_POST['eventDesc'], $_POST['eventDate']);
            Logic::addEvent($event);
        } else {
            $event = new Event($_POST['eventid'], $_POST['eventTitle'], $_POST['eventDesc'], $_POST['eventDate']);
            Logic::updateEvent($event);
        }

        $f3->reroute($_POST['route']);
    }

    $f3->reroute('/login');
});

$f3->route('GET|POST /gallery', function($f3) {

    if(isset($_POST['submit']) && $f3->get('isAdmin')) {
        $f3->set('result', Logic::submitNewImage($_FILES['image'], $_POST['caption'], 'gallery'));
        if($f3->get('result') === true) {
            $f3->reroute('/gallery');
        }
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
        Logic::deleteImage($_POST['image'], 'gallery');
    } else {
        echo json_encode('Invalid Credentials!');
    }

});

$f3->route('POST /ajax-delete-notification', function($f3) {
    if($f3->get('isAdmin')) {
        echo json_encode(Logic::deleteNotification($_POST['notification']));
    } else {
        echo json_encode('Invalid Credentials!');
    }

});

$f3->route('POST /ajax-delete-member', function($f3) {
    if ($f3->get('isAdmin')) {
        Logic::deleteMember($_POST['id'], $_POST['memberType'], $_POST['idColumnName'], $_POST['imageFolderName']);
    } else {
        echo json_encode('Invalid Credentials!');
    }
});

$f3->route('POST /ajax-shift-member', function($f3) {
    if ($f3->get('isAdmin')) {
        Logic::shiftMember($_POST['id'], $_POST['memberType'], $_POST['idColumnName'], $_POST['direction']);
    } else {
        echo json_encode('Invalid Credentials!');
    }
});

$f3->route('POST /ajax-delete-event', function($f3) {
    if ($f3->get('isAdmin')) {
        Logic::deleteEvent($_POST['id']);
    } else {
        echo json_encode('Invalid Credentials!');
    }
});

$f3->route('GET /account', function($f3) {

    if(!$f3->get('loggedIn')){
        $f3->reroute('/login');
    }

    $account = unserialize($_SESSION['account']);
    $accountId = $account->getId();
    $f3->set('accountId', $accountId);
    $f3->set('rentals', Logic::getRentalRequests());
    $f3->set('applications', Logic::getApplications());
    $f3->set('volunteers', Logic::getVolunteers());
    $f3->set('releases', Logic::getAccountRelease($accountId));

    // Title to use in template.
    $title = 'Account';
    // List of paths to stylesheets.
    $styles = array(
        'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css'
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_account.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_admin.js',
        'https://code.jquery.com/jquery-1.12.4.js',
        'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js'
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET|POST /account/edit', function($f3) {
    $curAccount = Logic::accountData($f3->get('username'));
    $storedAccount = new Account($curAccount->getId(), $curAccount->getUsername(), $curAccount->getPassword(), $curAccount->getEmail(), $curAccount->getPhone(), $curAccount->getPrivilege());
    $f3->set('storedAccount', $storedAccount);
    $f3->set('curAccount', $curAccount);

    if(isset($_POST['edit'])){
        $f3->set('editMode', true);
    }

    if(isset($_POST['save'])){
        // check errors is empty
        // $errors in array
        $errors = Validator::validAccountPage($_POST['username'], $_POST['password'], $_POST['confirmPassword'], $_POST['email'], $_POST['phone']);

        $curAccount->setUsername($_POST['username']);
        $curAccount->setPassword($_POST['password']);
        $curAccount->setEmail($_POST['email']);
        $curAccount->setPhone($_POST['phone']);

        if(count($errors) == 0) {
            Logic::updateAccount($curAccount);
        }else{
            $f3->set('errors', $errors);
            $f3->set('editMode', true);
        }
    }

    // Title to use in template.
    $title = "Edit Account";

    // List of paths to stylesheets.
    $styles = array(
        
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_editAccount.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_editAccount.js'
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET|POST /staff', function($f3) {

    $f3->set('StaffMembers', Logic::getAllStaff('staff', 'idstaff'));

    // Title to use in template.
    $title = "M-Power Staff";

    // List of paths to stylesheets.
    $styles = array(
        
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_staff.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_staff.js'
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('POST /staff-modify', function($f3) {
    // this should work for adding and editing.
    if ($f3->get('isAdmin') && isset($_POST['submit'])) {

        $portraitURL = $_POST['staffImage']; // sets variable to old portrait to start

        // check if a new image has been uploaded
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $portraitURL = Logic::submitImageToFolder($_FILES['image'], 'staffportraits');
            $portraitSubstr = substr($portraitURL, 0, 5);

            if ($portraitSubstr === "File " || $portraitSubstr === "Only ") { // failed image upload
                $portraitURL = $_POST['staffImage']; // reassign to old portraitURL
            } else if (!empty($_POST['staffImage']))  { // successful image upload, delete old image if it exists
                $imageNameWithoutFolder = substr($_POST['staffImage'], strrpos($_POST['staffImage'], '/') + 1);
                $imageFolder = 'staffportraits';
                Logic::deleteImage($imageNameWithoutFolder, $imageFolder);
            }
        }

        // if editing an existing staff member, staffid will be set.
        if ($_POST['staffid'] == -1) {
            $newMemberPageOrder = 1;

            if (sizeof(Logic::getAllStaff('staff', 'idstaff')) > 0) {
                $newMemberPageOrder = Logic::getMaxPageOrder('staff') + 1;
            }

            $staffMember = new StaffMember(-1, $_POST['staffFName'],
                $_POST['staffLName'], $_POST['staffTitle'], $_POST['staffBio'],
                $_POST['staffEmail'], $_POST['staffPhone'], $portraitURL, $newMemberPageOrder);
            Logic::addStaffMember($staffMember);
        } else {
            $staffMember = new StaffMember($_POST['staffid'], $_POST['staffFName'],
                $_POST['staffLName'], $_POST['staffTitle'], $_POST['staffBio'],
                $_POST['staffEmail'], $_POST['staffPhone'], $portraitURL, $_POST['pageOrder']);
            Logic::updateStaffMember($staffMember);
        }
    }

    $f3->reroute('staff');
});

$f3->route('GET|POST /board_of_directors', function($f3) {

    $f3->set('BODMembers', Logic::getAllStaff('board_of_directors', 'idbod'));

    // Title to use in template.
    $title = "M-Power Board of Directors";

    // List of paths to stylesheets.
    $styles = array(
        
    );

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_boardOfDirectors.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_boardOfDirectors.js'
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('POST /bod-modify', function($f3) {
    // this should work for adding and editing.
    if ($f3->get('isAdmin') && isset($_POST['submit'])) {

        $portraitURL = $_POST['BODImage']; // sets variable to old portrait to start

        // check if a new image has been uploaded
        if (is_uploaded_file($_FILES['image']['tmp_name'])) {
            $portraitURL = Logic::submitImageToFolder($_FILES['image'], 'staffportraits');
            $portraitSubstr = substr($portraitURL, 0, 5);

            if ($portraitSubstr === "File " || $portraitSubstr === "Only ") { // failed image upload
                $portraitURL = $_POST['BODImage']; // reassign to old portraitURL
            } else if (!empty($_POST['BODImage']))  { // successful image upload, delete old image if it exists
                $imageNameWithoutFolder = substr($_POST['BODImage'], strrpos($_POST['BODImage'], '/') + 1);
                $imageFolder = 'staffportraits';
                Logic::deleteImage($imageNameWithoutFolder, $imageFolder);
            }
        }

        // if editing an existing BOD member, idbod will be set.
        if ($_POST['idbod'] == -1) {
            $newMemberPageOrder = 1;

            if (sizeof(Logic::getAllStaff('board_of_directors', 'idbod')) > 0) {
                $newMemberPageOrder = Logic::getMaxPageOrder('board_of_directors') + 1;
            }

            $BODMember = new StaffMember(-1, $_POST['BODFName'],
                $_POST['BODLName'], $_POST['BODTitle'], $_POST['BODBio'],
                $_POST['BODEmail'], $_POST['BODPhone'], $portraitURL, $newMemberPageOrder);
            Logic::addBODMember($BODMember);
        } else {
            $BODMember = new StaffMember($_POST['idbod'], $_POST['BODFName'],
                $_POST['BODLName'], $_POST['BODTitle'], $_POST['BODBio'],
                $_POST['BODEmail'], $_POST['BODPhone'], $portraitURL, $_POST['pageOrder']);
            Logic::updateBODMember($BODMember);
        }
    }

    $f3->reroute('board_of_directors');
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

        $result = Logic::login($_POST['username'], $_POST['password']);

        // If login is successful, redirect to main page.
        if($result != false) {

            $f3->reroute('/');

        } else { // Otherwise generate error.

            $f3->set('invalid', true);
        }
    }

    // Update login status
    session_destroy();
    $f3->set('isAdmin', Validator::isAdmin());
    $logged = Validator::loggedIn();
    $f3->set('loggedIn', $logged);

    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /event', function($f3) {

    require('model/logic.php');
    $f3->set('events', Logic::getUpcomingEvents());

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

$f3->route('GET|POST /contact', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Contact Us";

    if(isset($_POST['submit'])) {
        $fname = $_POST['name'];
        $lname = $_POST['surname'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $message = $_POST['message'];
        echo "sent!";
        // TODO
        Messenger::sendMessage("njmedina614@gmail.com", "New Contact", $fname
            . " - " . $lname . " - " . $email . " - " . $phone . " : " . $message);
    }

    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_contact.html',
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

$f3->route('GET /donate', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Donate!";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_donate.html',
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
$f3->route('GET /join', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Join Us!";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_join.html',
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

/* -------------------------- INSTRUMENT RENTALS ----------------------------- */


$f3->route('GET /instruments', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Rent-A-Instrument";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_rent.html',
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

$f3->route('GET|POST /instruments/rental/@instrument', function($f3, $params ) {

    if(!$f3->get('loggedIn')){
        $f3->reroute('/login');
    }

    if(isset($_POST['submit'])){
        $account = unserialize($_SESSION['account']);

        $accountId = $account->getId();
        $student = $_POST['student_name'];
        $guardian = $_POST['guard_name'];
        $add1 = $_POST['street1'];
        $add2 = $_POST['street2'];
        $city = $_POST['city'];
        $zip = $_POST['zip'];
        $phone = $_POST['phone'];
        $school = $_POST['school'];
        $grade = $_POST['grade'];
        $instrument = $_POST['instrument'];

        $errors = Validator::validInstrumentPage($student, $guardian, $zip, $phone, $instrument);

        if(count($errors)==0) {

            date_default_timezone_set('America/Los_Angeles');
            $date = date('Y-m-d');

            $result = Logic::requestInstrument($accountId, $student, $guardian, $add1,
                                     $add2, $city, $zip, $phone,
                                     $school, $grade, $instrument,
                                     $date);

            if($result != false){
                $f3->set('result', "Request Submitted");
                $f3->reroute('/form/success');
            } else {
                $f3->set('result', "Request failed to send");
            }

        } else {

            $f3->set('result', "Request failed to send");

            $f3->set('errors', $errors);

            $f3->set('student',$student);
            $f3->set('guardian', $guardian);
            $f3->set('add1', $add1);
            $f3->set('add2', $add2);
            $f3->set('city', $city);
            $f3->set('zip', $zip);
            $f3->set('phone', $phone);
            $f3->set('school', $school);
            $f3->set('grade', $grade);
            $f3->set('instrument', $instrument);

        }
    } else {
        $instrument = $params['instrument'];
    }


    // Title to use in template.
    $title = "M-Power Youth: Instrument Agreement";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_rentForm.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array();
    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);

    //Variables going to be used
    $f3->set('instrument', $instrument);


    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /videos', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Videos";
    // List of paths to stylesheets.
    $styles = array();
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_videos.html',
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
$f3->route('GET /testimonials', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Testimonials";
    // List of paths to stylesheets.
    $styles = array();
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_testimonials.html',
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

$f3->route('GET /about', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: About";
    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_about.css'
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_about.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_home.js',

    );


    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);
    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /files', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Downloadable Files";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_files.html',
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

$f3->route('GET /about', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: About";
    // List of paths to stylesheets.
    $styles = array(
        'assets/styles/_about.css'
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_about.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_home.js',

    );


    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);
    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET /form/success', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Downloadable Files";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_success.html',
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

$f3->route('GET|POST /accounts/register', function($f3) {
    if(isset($_POST['submit'])) {
        $f3->set('result', Logic::register(
            $_POST['username'],
            $_POST['password1'],
            $_POST['password2'],
            $_POST['email'],
            $_POST['phone']
        ));
        if(count($f3->get('result')) == 0) {
            $f3->reroute('/accounts/register/confirmation');
        }
    }

    // Title to use in template.
    $title = "M-Power Youth: Registration";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_register.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_register.js'
    );

    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);
    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET|POST /accounts/register/confirmation', function($f3) {
    // Title to use in template.
    $title = "M-Power Youth: Confirmation";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_confirmation.html',
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


// Route for link for verifying new users.
$f3->route('GET /accounts/register/verify/@hash', function($f3, $params) {
    if(isset($_SESSION['account'])) {
        $f3->reroute('/login');
    }

    $hash = $params['hash'];

    $result = Logic::verifyAccount($hash);


    // Title to use in template.
    $title = "Verify Account";

    // List of paths to stylesheets.
    $styles = array();

    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_verification.html',
        'views/_footer.html'
    );

    // List of paths to scripts being used.
    $scripts = array();

    // Store page attributes to hive.
    $f3->set('result',   $result);
    $f3->set('title',    $title);
    $f3->set('styles',   $styles);
    $f3->set('includes', $includes);
    $f3->set('scripts',  $scripts);

    // Display Template
    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET|POST /PhotoVideoRelease', function($f3) {

    if(!$f3->get('loggedIn')){
        $f3->reroute('/login');
    }

    if(isset($_POST['submit'])) {
        $data = array($_POST['childName'], $_POST['parent']);

        $errors = Validator::validMediaRelease($data[0], $data[1]);

        if (count($errors) == 0) {
            Logic::insertMediaRelease($data);
            $f3->reroute('/form/success');
        } else {
            foreach ($errors as $key => $value) {
                echo $key . " - " . $value;
            }
        }
    }

    // Title to use in template.
    $title = "M-Power Youth: Media Release";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_formMediaRelease.html',
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

$f3->route('GET|POST /enrollment', function($f3) {

    if(!$f3->get('loggedIn')){
        $f3->reroute('/login');
    }

    if(isset($_POST['submit'])) {
        $data = array($_POST['studentName'], $_POST['school'], $_POST['grade'], $_POST['instrument'], $_POST['parent'],
            $_POST['email'], $_POST['phone'], $_POST['street1'], $_POST['street2'], $_POST['city'], $_POST['zip'],
            $_POST['allergies'], $_POST['referral'], $_POST['decision'], $_POST['takeHomeInstrument']);

        $errors = Validator::validEnrollment($_POST['studentName'], $_POST['school'], $_POST['grade'], $_POST['instrument'], $_POST['parent'],
            $_POST['email'], $_POST['phone'], $_POST['street1'], $_POST['street2'], $_POST['city'], $_POST['zip'],
            $_POST['allergies'], $_POST['referral'], $_POST['decision'], $_POST['takeHomeInstrument']);

        if (count($errors) == 0) {
            Logic::insertEnrollment($data);
            $f3->reroute('/form/success');
        }
    }

    $f3->set('curDate', (new DateTime("now", new DateTimeZone('America/Los_Angeles')))->format("Y-m-d"));

    // Title to use in template.
    $title = "M-Power Youth: Enrollment";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_formEnrollment.html',
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

$f3->route('GET|POST /volunteer', function($f3) {

    if(!$f3->get('loggedIn')){
        $f3->reroute('/login');
    }

    if(isset($_POST['submit'])) {

        //Values from POST array
        $account = unserialize($_SESSION['account']);
        $accountId = $account->getId();
        $name = $_POST['full_name']; //Name
        $address = $_POST['address']; //Address
        $zip = $_POST['zip']; //Zip code
        $dob = $_POST['date']; //Date of Birth
        $phone = $_POST['phone']; //phone number
        $dl = $_POST['dl']; //Driver's License Number


        $errors = Validator::validVolunteer($name, $address, $zip, $dob, $phone, $dl);

        if(count($errors)==0) {

            date_default_timezone_set('America/Los_Angeles');
            $date = date('Y-m-d'); //Date of Request

            $result = Logic::volunteerRequest($accountId, $name, $address, $zip, $dob, $phone,
                $dl, $date);

            if ($result != false) {
                $f3->set('result', "Request Submitted");
                $f3->reroute('/form/success');
            } else {
                $f3->set('result', "Request failed to send");
            }

        } else {

            $f3->set('result', "Request failed to send");

            $f3->set('errors', $errors);

             $f3->set('name',$name);
             $f3->set('address',$address);
             $f3->set('zip',$zip);
             $f3->set('dob',$dob);
             $f3->set('phone',$phone);
             $f3->set('dl',$dl);

        }

    }

    // Title to use in template.
    $title = "M-Power Youth: Volunteer Registration";
    // List of paths to stylesheets.
    $styles = array(
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_volunteer.html',
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

$f3->route('GET /administration', function($f3) {
    if (!$f3->get('isAdmin')) {
        $f3->reroute('/login');
    }

    $f3->set('notifications', Logic::getNotifications());
    $f3->set('rentals', Logic::getRentalRequests());
    $f3->set('applications', Logic::getApplications());
    $f3->set('volunteers', Logic::getVolunteers());
    $f3->set('accounts', Logic::getAccounts());
    $f3->set('releases', Logic::getAdminRelease());

    // Title to use in template.
    $title = "M-Power Youth: Administration";
    // List of paths to stylesheets.
    $styles = array(
        'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css'
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_admin.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_admin.js',
        'https://code.jquery.com/jquery-1.12.4.js',
        'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js'
    );
    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);
    $template = new Template();
    echo $template->render('views/_base.html');
});

$f3->route('GET|POST /forms/review/@type/@accountId/@formId', function($f3, $params) {

    // Ensure only admin and owner of form works
    if ($f3->get('isAdmin')) {

        $account = unserialize($_SESSION['account']);
        $accountId = $account->getId();
        if ($params['accountId'] != $accountId)
            if (!$f3->get('isAdmin')) {
                $f3->reroute('/login');
            }


        if($_POST['submit'] == 1 || $_POST['submit']==-1){

            if($params['type'] == 'enrollment'){ Logic::updateEnrollment($_POST['submit'], $params['formId']);};

            if($params['type'] == 'volunteer'){ Logic::updateVolunteer($_POST['submit'], $params['formId']);};

            if($params['type'] == 'rental'){ Logic::updateInstrument($_POST['serial'], $_POST['contract'], $_POST['make'], $_POST['model'], $_POST['submit'], $params['formId']);};

            $f3->reroute('/administration');
        }
    }


    $formData = Logic::getForm($params['type'], $params['formId']);

    $f3->set('formData', $formData[0]);


    // Title to use in template.
    $title = $params['type'] . ' form';
    // List of paths to stylesheets.
    $styles = array(
        'https://cdn.datatables.net/1.10.16/css/dataTables.bootstrap4.min.css'
    );
    // List of paths for sub-templates being used.
    $includes = array(
        'views/_nav.html',
        'views/_reviewForm.html',
        'views/_footer.html'
    );
    // List of paths to scripts being used.
    $scripts = array(
        BASE.'/assets/scripts/_admin.js',
        'https://code.jquery.com/jquery-1.12.4.js',
        'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
        'https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js'
    );

    $f3->set('type', $params['type']);
    $f3->set('title' , $title);
    $f3->set('styles' , $styles);
    $f3->set('includes' , $includes);
    $f3->set('scripts' , $scripts);
    $template = new Template();
    echo $template->render('views/_base.html');
});

