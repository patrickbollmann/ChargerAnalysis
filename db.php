<?php
include "secret.php";

$mysqli = new mysqli("localhost", $dbuser, $dbpass, "evCharge");
$mysqli->set_charset("utf8");

if ($mysqli->connect_errno) {
    die("Verbindung fehlgeschlagen: " . $mysqli->connect_error);
}
?>
