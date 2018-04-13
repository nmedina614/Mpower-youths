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

require('controller/routes.php');



$f3->run();