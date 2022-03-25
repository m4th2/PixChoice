<?php
include "config.php";
$mysqli = mysqli_init();
$mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
$mysqli->real_connect($adresse, $identifiant, $mdp, $database);

if ($mysqli->connect_errno) {
    echo "Échec lors de la connexion à MySQL  : (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
}

/* ce code ne semble pas marcher en ligne (mais il tourne en local, il produit une erreur. reset.php remplira donc juste la base de données avec les noms des images.
La table sera elle créée artisanalement via phpmyadmin oO

if (!$mysqli->query("DROP TABLE IF EXISTS concours")) {
		echo "Échec lors de la suppression de la table : (" . $mysqli->errno . ") " . $mysqli->error;
	}
if (!$mysqli->query("CREATE TABLE concours (
  image varchar(10) DEFAULT NULL COMMENT 'nom du fichier',
  clé int(11) NOT NULL,
  nb_votes int(11) DEFAULT 0,
  nb_fois int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;")) {
		echo "Échec lors de la création de la table : (" . $mysqli->errno . ") " . $mysqli->error;
	}
if (!$mysqli->query("ALTER TABLE concours
  ADD PRIMARY KEY (clé);")){
	  echo "Échec lors de l'ajout de la clé primaire : (" . $mysqli->errno . ") " . $mysqli->error;
  }
if (!$mysqli->query("ALTER TABLE concours
  MODIFY clé int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;")){
	  echo "Échec lors de l'ajout de l'option d'auto-incrémentation : (" . $mysqli->errno . ") " . $mysqli->error;
  }
 */


$extensionsAffichees=array("jpg");
$fichiers=array(); 
foreach (new DirectoryIterator('../concours/images') as $fileInfo) {
    if(!$fileInfo->isDot()) {
		$nom=$fileInfo->getFilename();
		$extension=substr($nom,-3);
		if (in_array($extension,$extensionsAffichees) and !is_dir($nom)){
			$fichiers[]=$nom;
			echo "<li>".$nom."</li>";
		}
	}
}

foreach ($fichiers as $fichier){
	if (!$mysqli->query('INSERT INTO concours (image) VALUES ("'.$fichier.'")')) {
		echo "<li>".$fichier."</li>";
		echo "Échec lors de la création de la table : (" . $mysqli->errno . ") " . $mysqli->error;
	}
}




?>
