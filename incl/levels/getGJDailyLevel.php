<?php
chdir(dirname(__FILE__));

// Example custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline)
{
    // Log or handle the error as needed
    error_log("Error: $errstr in $errfile on line $errline");
}

// Set the custom error handler
set_error_handler("customErrorHandler");

$type = !empty($_POST["type"]) ? $_POST["type"] :
    (!empty($_POST["weekly"]) ? $_POST["weekly"] : 0);

$midnight = ($type == 1) ? strtotime("next monday") : strtotime("tomorrow 00:00:00");
include "../lib/connection.php";
//Getting DailyID
$current = time();
$query = $db->prepare("SELECT feaID FROM dailyfeatures WHERE timestamp < :current AND type = :type ORDER BY timestamp DESC LIMIT 1");
$query->execute([':current' => $current, ':type' => $type]);
if ($query->rowCount() == 0)
    exit("-1");
$dailyID = $query->fetchColumn();
if ($type == 1)
    $dailyID += 100001;
//Time left
$timeleft = $midnight - $current;
//output
echo $dailyID . "|" . $timeleft;
?>