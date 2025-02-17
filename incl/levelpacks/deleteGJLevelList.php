<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
	// Log or handle the error as needed
	error_log("Error: $errstr in $errfile on line $errline");
}

// Set the custom error handler
set_error_handler("customErrorHandler");

$gs = new mainLib();
$accountID = GJPCheck::getAccountIDOrDie();
$listID = ExploitPatch::number($_POST["listID"]);
if (is_numeric($listID) and $accountID == $gs->getListOwner($listID)) {
	$list = $db->prepare('DELETE FROM lists WHERE listID = :listID');
	$list->execute([':listID' => $listID]);
	exit("1");
} else
	exit("-1");