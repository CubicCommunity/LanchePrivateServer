<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/GJPCheck.php";
require_once "../lib/exploitPatch.php";
require_once "../lib/mainLib.php";
$mainLib = new mainLib();

$levelID = ExploitPatch::remove($_POST["levelID"]);
$accountID = GJPCheck::getAccountIDOrDie();

if (!is_numeric($levelID)) {
	exit("-1");
}

// Example custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
	// Log or handle the error as needed
	error_log("Error: $errstr in $errfile on line $errline");
}

// Set the custom error handler
set_error_handler("customErrorHandler");

$userID = $mainLib->getUserID($accountID);
$query = $db->prepare("DELETE from levels WHERE levelID=:levelID AND userID=:userID AND starStars = 0 LIMIT 1");
$query->execute([':levelID' => $levelID, ':userID' => $userID]);
$query6 = $db->prepare("INSERT INTO actions (type, value, timestamp, value2) VALUES 
											(:type,:itemID, :time, :ip)");
$query6->execute([':type' => 8, ':itemID' => $levelID, ':time' => time(), ':ip' => $userID]);
if (file_exists("../../data/levels/$levelID") and $query->rowCount() != 0) {
	rename("../../data/levels/$levelID", "../../data/levels/deleted/$levelID");
}
echo "1";
?>