<?php 
require_once('_functions/function.php'); 

/* code de recherche et de tri de la base de données */

$sql = "SELECT DISTINCT d.titre, d.fichier, YEAR(d.date) date, 
		ss.source, g.zone, s.secteur, t.theme FROM documents d
		
		JOIN sources ss ON (d.source_id = ss.id)
		
		JOIN geozones g ON (d.zone_id = g.id)
			 
		JOIN secteurs s ON (d.secteur_id = s.id)
		
		JOIN thematiques t ON (d.thematique_id = t.id)";

/* Opération se déroulant après le clique sur le bouton recherche */

if (isset($_GET['srch_for'])) {
	
	/* variable devant stocker les valeurs entrées ou selectionnées pour la recherche */
	$getters = array();
	$queries = array();
	
	/* insertion des valeurs entrées ou selectionnées pour la recherche dans le tableau getters */
	foreach($_GET as $key => $value) {
		$temp = is_array($value) ? $value : trim($value);
		if (!empty($temp)) {
			if (!in_array($key,$getters)) {
				$getters[$key] = $value;
			}
		}
	}
	
	/* opération ressortant les informations recherchées par tri des valeurs entrées*/
	
	if (!empty($getters)) {
		foreach($getters as $key => $value) {
			${$key} = $value;
			switch($key) {
				case 'srch_for': array_push($queries,"(d.titre LIKE '%$srch_for%' || d.source_id LIKE '%$srch_for%' || d.secteur_id LIKE '%$srch_for%' || d.thematique_id LIKE '%$srch_for%' || YEAR(d.date) LIKE '%$srch_for%' || d.zone_id LIKE '%$srch_for%' )");
				break;
				case 'srch_year': array_push($queries," YEAR(d.date) = $srch_year");
				break;
				case 'srch_source': array_push($queries,"d.source_id = $srch_source");
				break; 
				case 'srch_zone': array_push($queries,"d.zone_id = $srch_zone");
				break; 
				case 'srch_sector': array_push($queries,"d.secteur_id = $srch_sector");
				break;
				case 'srch_thematic': array_push($queries,"d.thematique_id = $srch_thematic");
				break;
				case 'srch_zone': array_push($queries,"d.zone_id IN ($loc_qry)");
				break;
			}		
		}
	}
	
	/* opération concanténant la requete de tri sql plus haut avec plusieurs autres possibilités de tris */
	
	if (!empty($queries)) {
		$sql .= " WHERE ";
		$i=1;
		foreach($queries as $query) {
			if ($i < count($queries)) {
				$sql .= $query." AND ";
			} else {
				$sql .= $query;
			}
			$i++;
		}
	}
	
	$sql .= " ORDER BY YEAR(d.date) ASC";
}

mysql_select_db($database,$conndb);
$rs = mysql_query($sql,$conndb) or die(mysql_error());
$rows = mysql_fetch_assoc($rs);
$tot_rows = mysql_num_rows($rs);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Document</title>
<link href="_css/document.css" rel="stylesheet" type="text/css" />
</head>
<body>
<div id="out">
	<div id="wr">
		<div id="hd">
	  		<div id="cnt">
				<h2>Rechercher un document</h2>
				<form id="search_form" name="search_form" method="get" action="">
    				<table border="0" cellspacing="0" cellpadding="0" class="tbl_search">
					<tr>
					  <th scope="row"><label for="srch_for">Titre du document:</label></th>
					  <td><input type="text" name="srch_for" id="srch_for" class="f_fld" value="<?php getSticky(1,"srch_for"); ?>"/></td>
					  <th><label for="srch_year">Année:</label></th>
					  <td><?php getYear() ?></td>
					</tr>
					<tr>
					  <th scope="row"><label for="srch_source">Source:</label></th>
					  <td><?php getSource()?></td>
					  <th><label for="srch_zone">Zone géographique:</th>
					  <td><?php getGeozone() ?></td>
					</tr>
					<tr>
					  <th scope="row"><label for="srch_sector">Secteur:</th>
					  <td><?php getSector() ?></td>
					  <th scope="row"><label for="srch_theme">Thematique:</label></th>
					  <td><?php getThematique() ?></td>
					</tr>
					<tr>
				  		<td colspan="4"><label for="btn" class="sbm fl_r"> <input type="submit" id="btn" value="Recherche" /></label></td>
					</tr>
					</table>
	    		</form>
	    		<?php if ($tot_rows > 0) { ?>
					<table border="0" cellspacing="0" cellpadding="0" class="tbl_repeat">
					<tr>
						<th scope="col" class="col_25">Titre du document</th>
						<th scope="col" class="col_15">Source</th>
						<th scope="col" class="col_15">Zone géographique</th>
						<th scope="col" class="col_10">Secteur</th>
						<th scope="col" class="col_10">Thématique</th>
						<th scope="col" class="col_10">Années</th>
						<th scope="col" class="col_25">Fichier</th>
					</tr>
					<?php do { ?>
					<tr>
						<td><?php echo $rows['titre']; ?></td>
						<td><?php echo $rows['source']; ?></td>
						<td><?php echo $rows['zone']; ?></td>
						<td><?php echo $rows['secteur']; ?></td>
						<td><?php echo $rows['theme']; ?></td>
						<td><?php echo $rows['date']; ?></td>
						<td><?php echo $rows['fichier']; ?>
					</tr>
					<?php } while($rows = mysql_fetch_assoc($rs)); ?>
					</table>
					<?php 
					} else {
						if (!empty($querries)) {
							echo "<p> Il n'y a aucune donnée correspondand à votre recherche.</p>";
						} else {
							echo "<p> Il n'y a aucune donnée disponible.</p>";
						}

					} ?>
	  		</div>
	  	</div>
	</div>
	<div id="ft">
		<div id="ftin">
			
		</div>
	</div>
</div>
</body>
</html>
<?php mysql_free_result($rs); ?>