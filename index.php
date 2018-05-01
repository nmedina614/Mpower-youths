<?php
/**
 * File that initiates settings and protocol for the
 * M-Power Youth website.
 *
 * @author Aaron Melhaff <amelhaff2@mail.greenriver.edu>
 * @author Scott Medlock <squmed@gmail.com>
 * @author Kyle Johnson
 * @author Nolan Medina
 *
 * @since 4/30/2018
 */

session_start();

ini_set('display_errors',1);
error_reporting(E_ALL);

// Require f3
require_once('vendor/autoload.php');

$f3 = Base::instance();
$f3->set('DEBUG', 3);

// Variable used to check if visitor is logged in as an admin.
$f3->set('isAdmin', Validator::isAdmin());

// Path to root directory.
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . '/../');

define('BASE', $f3->get('BASE'));

// Domain all the way to project folder.
define('DOMAIN', $_SERVER['HTTP_HOST'].BASE);

require('controller/routes.php');



$f3->run();