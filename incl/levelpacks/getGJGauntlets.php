<?php
chdir(dirname(__FILE__));
include "../lib/connection.php";
require_once "../lib/generateHash.php";

function customErrorHandler($errno, $errstr, $errfile, $errline)
{
	// Log or handle the error as needed
	error_log("Error: $errstr in $errfile on line $errline");
}

// Set the custom error handler
set_error_handler("customErrorHandler");

$gauntletstring = "";
$string = "";
$query = $db->prepare("SELECT ID,level1,level2,level3,level4,level5 FROM gauntlets WHERE level5 != '0' ORDER BY ID ASC");
$query->execute();
$result = $query->fetchAll();
foreach ($result as &$gauntlet) {
	$lvls = $gauntlet["level1"] . "," . $gauntlet["level2"] . "," . $gauntlet["level3"] . "," . $gauntlet["level4"] . "," . $gauntlet["level5"];
	$gauntletstring .= "1:" . $gauntlet["ID"] . ":3:" . $lvls . "|";
	$string .= $gauntlet["ID"] . $lvls;
}
$gauntletstring = substr($gauntletstring, 0, -1);
echo $gauntletstring;
echo "#" . GenerateHash::genSolo2($string);
?>