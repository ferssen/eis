<?php

/* fonctions php à utiliser dans le code principale */

require_once 'database.php'; /* inclure le fichier database.php */

/* fonction collectantant les sources d'information dans la table sources de la base de données */
/* pour les afficher dans un menu déroulant */

function getSource() {
	
	/* variables globale permettant la connexion à la base de données */
	global $database; 	
	global $conndb;
	
	
	/* requete sql affichant les information de la colonne source */
	$sql = "SELECT * FROM sources ORDER BY source ASC";
	
	mysql_select_db($database,$conndb); /* selection de la base de données */
	$rs = mysql_query($sql,$conndb) or die(mysql_error()); /* capture de la requete sql et l'execute dans la BD*/
	$rows = mysql_fetch_assoc($rs); /* capture les lignes de la colonnes source */
	$tot_rows = mysql_num_rows($rs); /* compte le nombre de ligne de la colonne source */
	
	/* creation du menu deroulant et ajout des données de la colonne source dans le menu déroulant */
	if($tot_rows > 0) {
		mb_internal_encoding('UTF-8');
		echo "<select name=\"srch_source\" id=\"srch_source\">\n";
		echo "<option value=\"\">Source du document&hellip;</option>\n";
		do {
			echo "<option value=\"".$rows['id']."\"";
			getSticky(2,'srch_source',$rows['id']);
			echo ">".$rows['source']."</option>";
		} while($rows = mysql_fetch_assoc($rs));
		echo "</select>";
	}
	mysql_free_result($rs); 
}

/* fonction collectantant les secteurs couverts dans la table secteur de la base de données */
/* pour les afficher dans un menu déroulant */
	
function getSector() {
	
	global $database;	
	global $conndb;
	
	$sql = "SELECT * FROM secteurs ORDER BY secteur ASC";
	
	mysql_select_db($database,$conndb);
	$rs = mysql_query($sql,$conndb) or die(mysql_error());
	$rows = mysql_fetch_assoc($rs);
	$tot_rows = mysql_num_rows($rs);
	
	if($tot_rows > 0) {
		echo "<select name=\"srch_sector\" id=\"srch_sector\">\n";
		echo "<option value=\"\">Secteur couvert&hellip;</option>\n";
		do {
			echo "<option value=\"".$rows['id']."\"";
			getSticky(2,'srch_sector',$rows['id']);
			echo ">".$rows['secteur']."</option>";
		} while($rows = mysql_fetch_assoc($rs));
		echo "</select>";
	}
	mysql_free_result($rs);
}

/* fonction collectantant les thématiques exposées dans la table thématiques de la base de données */
/* pour les afficher dans un menu déroulant */

function getThematique() {
	
	global $database;	
	global $conndb;
	
	$sql = "SELECT * FROM thematiques ORDER BY theme ASC";
	
	mysql_select_db($database,$conndb);
	$rs = mysql_query($sql,$conndb) or die(mysql_error());
	$rows = mysql_fetch_assoc($rs);
	$tot_rows = mysql_num_rows($rs);
	
	if($tot_rows > 0) {
		mb_internal_encoding('UTF-8');
		echo "<select name=\"srch_thematic\" id=\"srch_thematic\">\n";
		echo "<option value=\"\">Thématique&hellip;</option>\n";
		do {
			echo "<option value=\"".$rows['id']."\"";
			getSticky(2,'srch_thematic',$rows['id']);
			echo ">".$rows['theme']."</option>";
		} while($rows = mysql_fetch_assoc($rs));
		echo "</select>";
	}
	mysql_free_result($rs);
}

/* fonction collectantant les zones géographiques couvertes dans la table geozones de la base de données */
/* pour les afficher dans un menu déroulant */

function getGeozone() {
	
	global $database;	
	global $conndb;
	
	$sql = "SELECT * FROM geozones ORDER BY zone ASC";
	
	mysql_select_db($database,$conndb);
	$rs = mysql_query($sql,$conndb) or die(mysql_error());
	$rows = mysql_fetch_assoc($rs);
	$tot_rows = mysql_num_rows($rs);
	
	if($tot_rows > 0) {
		mb_internal_encoding('UTF-8');
		echo "<select name=\"srch_zone\" id=\"srch_zone\">\n";
		echo "<option value=\"\">Zone géographique couverte&hellip;</option>\n";
		do {
			echo "<option value=\"".$rows['id']."\"";
			getSticky(2,'srch_zone',$rows['id']);
			echo ">".$rows['zone']."</option>";
		} while($rows = mysql_fetch_assoc($rs));
		echo "</select>";
	}
	mysql_free_result($rs);
}

/* fonction collectantant la date en Année dans la table documents de la base de données */
/* pour les afficher dans un menu déroulant */

function getYear() {
	
	global $database;	
	global $conndb;
	
	$sql = "SELECT DISTINCT YEAR(date) as An FROM documents ORDER BY date ASC";
	
	mysql_select_db($database,$conndb);
	$rs = mysql_query($sql,$conndb) or die(mysql_error());
	$rows = mysql_fetch_assoc($rs);
	$tot_rows = mysql_num_rows($rs);
	
	if($tot_rows > 0) {
		mb_internal_encoding('UTF-8');
		echo "<select name=\"srch_date\" id=\"srch_date\">\n";
		echo "<option value=\"\">Date&hellip;</option>\n";
		do {
			echo "<option value=\"".$rows['An']."\"";
			getSticky(2,'srch_zone',$rows['An']);
			echo ">".$rows['An']."</option>";
		} while($rows = mysql_fetch_assoc($rs));
		echo "</select>";
	}
	mysql_free_result($rs);
}

/* fonction collectantant la langue du document dans la table document de la base de données */
/* pour les afficher dans un menu déroulant */

function getLanguage() {
	
	global $database;	
	global $conndb;
	
	$sql = "SELECT DISTINCT langue FROM documents ORDER BY langue ASC";
	
	mysql_select_db($database,$conndb);
	$rs = mysql_query($sql,$conndb) or die(mysql_error());
	$rows = mysql_fetch_assoc($rs);
	$tot_rows = mysql_num_rows($rs);
	
	if($tot_rows > 0) {
		mb_internal_encoding('UTF-8');
		echo "<select name=\"srch_langue\" id=\"srch_langue\">\n";
		echo "<option value=\"\">Langue&hellip;</option>\n";
		do {
			echo "<option value=\"".$rows['langue']."\"";
			getSticky(2,'srch_zone',$rows['langue']);
			echo ">".$rows['langue']."</option>";
		} while($rows = mysql_fetch_assoc($rs));
		echo "</select>";
	}
	mysql_free_result($rs);
}

/* fonction capturant les informations recherchées et les maintenant sur l'interface de recherche */

function getSticky($case,$par,$value="",$initial="") {
	
	switch($case) {
		case 1: // champs de texte
		if (isset($_GET[$par]) && $_GET[$par] !="") {
			echo stripslashes($_GET[$par]);	
		}
		break;
		case 2: // Menu déroulant
		if (isset($_GET[$par]) && $_GET[$par] == $value) {
			echo " selected=\"selected\"";
		}
		break;
	}
}
?>