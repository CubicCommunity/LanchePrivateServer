<?php
chdir(dirname(__FILE__));
//error_reporting(0);
include "../lib/connection.php";
require_once "../lib/exploitPatch.php";
require "../lib/generateHash.php";

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
	// Log or handle the error as needed
	error_log("Error: $errstr in $errfile on line $errline");
}

// Set the custom error handler
set_error_handler("customErrorHandler");

$page = ExploitPatch::remove($_POST["page"]);
$packpage = $page * 10;
$mappackstring = "";
$lvlsmultistring = "";
$query = $db->prepare("SELECT colors2,rgbcolors,ID,name,levels,stars,coins,difficulty FROM `mappacks` ORDER BY `ID` ASC LIMIT 10 OFFSET $packpage");
$query->execute();
$result = $query->fetchAll();
$packcount = $query->rowCount();
foreach ($result as &$mappack) {
	$lvlsmultistring .= $mappack["ID"] . ",";
	$colors2 = $mappack["colors2"];
	if ($colors2 == "none" or $colors2 == "") {
		$colors2 = $mappack["rgbcolors"];
	}
	$mappackstring .= "1:" . $mappack["ID"] . ":2:" . $mappack["name"] . ":3:" . $mappack["levels"] . ":4:" . $mappack["stars"] . ":5:" . $mappack["coins"] . ":6:" . $mappack["difficulty"] . ":7:" . $mappack["rgbcolors"] . ":8:" . $colors2 . "|";
}
$query = $db->prepare("SELECT count(*) FROM mappacks");
$query->execute();
$totalpackcount = $query->fetchColumn();
$mappackstring = substr($mappackstring, 0, -1);
$lvlsmultistring = substr($lvlsmultistring, 0, -1);
echo $mappackstring;
echo "#" . $totalpackcount . ":" . $packpage . ":10";
echo "#";
echo GenerateHash::genPack($lvlsmultistring);
?>