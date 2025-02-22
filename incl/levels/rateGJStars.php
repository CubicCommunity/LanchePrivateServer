<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";

// Example custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    // Log or handle the error as needed
    error_log("Error: $errstr in $errfile on line $errline");
}

// Set the custom error handler
set_error_handler("customErrorHandler");

$gs = new mainLib();
$gjp2check = isset($_POST['gjp2']) ? $_POST['gjp2'] : $_POST['gjp'];
$gjp = ExploitPatch::remove($gjp2check);
$stars = ExploitPatch::remove($_POST["stars"]);
$levelID = ExploitPatch::remove($_POST["levelID"]);
$accountID = GJPCheck::getAccountIDOrDie();
$permState = $gs->checkPermission($accountID, "actionRateStars");
if ($permState) {
    $difficulty = $gs->getDiffFromStars($stars);
    $gs->rateLevel($accountID, $levelID, 0, $difficulty["diff"], $difficulty["auto"], $difficulty["demon"]);
}
echo 1;