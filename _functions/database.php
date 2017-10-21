<?php 
/* connexion à la base de données */

$hostname = "localhost"; /* nom du server de la base de données */

$database = "eis"; /* nom de la base de données */

$username = "root"; /* nom d'utilisateur de la base de données */

$password = ""; /* Mot de passe de la base de données*/

$conndb = mysql_connect($hostname, $username, $password) 
	      or trigger_error(mysql_error()); /* connecxion à la base de données */

?>