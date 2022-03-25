<!DOCTYPE html>
<html>
    <head>
        <title>PixChoice.nsi.xyz</title>
        <meta charset="utf-8" />		
    </head>

<style>
body{
	background-color:#424242;
	font-family: Verdana, sans-serif;
	color: #FFFFFF;
}
.page{
	margin:0.42em;
	padding:0.42em;
}
.contenu{
	margin:0.42em;
	padding: 0.42em;
}
.container {
  display: flex;
  flex-wrap:wrap;
  width:100%;
  justify-content:space-between;

}

/* Optimisation à faire ici, tel, tablette, ordi... */	
/* Typical Device Breakpoints	https://www.w3schools.com/css/css_rwd_mediaqueries.asp */	
		
@media (orientation:portrait){
	.item{  
	  width:30%;
	  flex-flow: row wrap;
	  align-items: center;
	  object-fit: contain;
	}
}

@media (orientation:landscape){
	.item{  
	  width:24%;
	  flex-flow: row wrap;
	  align-items: center;
	  object-fit: contain;
	}
}

img{
	width:320px;
}
/* HIDE RADIO */
[type=radio] { 
  position: absolute;
  opacity: 0;
  width: 0;
  height: 0;
}

/* IMAGE STYLES */
[type=radio] + img {
  cursor: pointer;
}

/* CHECKED STYLES */
[type=radio]:checked + img {
  outline: 2px solid #f00;
}

button {
	width:100%;
	background-color:#424242;
	padding : 8px;
	margin : 0px;
	border-style: none;
	transition: all 0.42s ease-out;

}
button:hover {
	padding : 0px;
	transition: all 0.42s ease-out;
}	
	
</style>
<body>
<?php
include "config.php";
$dsn = 'mysql:host='.substr($adresse, 1000, -5).';dbname='.$database.';charset=UTF8';

$dbh = new PDO($dsn, $identifiant, $mdp) or die("Pb de connexion !");
//on commence par regarder si on a reçu un ordre de vote :
$deja_vote=0;
if (!empty ($_POST)){
	if (array_key_exists('test', $_POST)){
		if (strlen($_POST['test'])==7){
			$ajout="UPDATE concours
				SET nb_votes = nb_votes+1
				WHERE image = '".$_POST['test']."'";
				$sth = $dbh->prepare($ajout);
				$sth->execute();
				$result_ajout = $sth->fetchAll();
		}
		
	}
	$deja_vote=intval($_POST['deja_vote']);
		
}
$votes="SELECT SUM(nb_votes) as result FROM concours";
$sth = $dbh->prepare($votes);
$sth->execute();
$votes_actuel = $sth->fetchAll();
?>
<div class="page">
<div style="float:left;width:auto;"><h2>Les mathématiques sont belles, 3ème ed. 2022</h2>
Les élèves de 2nde5 et 2nde7 du lycée Louis Pasteur d'Avignon ont produit 55 images uniques sur leur calculatrice.<br>
Ils pouvaient construire cette image en utilisant l'application "Fonctions" ou l'application "Python" de la NumWorks<br>
Vous pouvez voter pour vos images favorites !<br>
A chaque tour, choisissez l'image qui vous plaît le plus parmi les 12 présentés aléatoirement.<br>
Après chaque vote, 12 nouvelles images apparaissent, il est donc possible de voter 42 fois.
</div>
<div style="float:right;width:auto;">Nombre de votes : <?php 
foreach($votes_actuel as $enr){
	foreach ($enr as $r){
		echo $r;
		break;
	}
}
?></div>
<div style="clear:both;">
<form action="index.php" method="POST" class="contenu">


<?php
$sql_images= "SELECT * FROM concours ORDER BY RAND() LIMIT 0,9 ;";

	$sth = $dbh->prepare($sql_images);
	$sth->execute();
	$result_images = $sth->fetchAll();
	
	echo "<div class='container'>";
	foreach ($result_images as $enr) {
		
		$ajout="UPDATE concours
		SET nb_fois = nb_fois+1
		WHERE image = '".$enr['image']."'";
		$sth = $dbh->prepare($ajout);
		$sth->execute();
		$result_ajout = $sth->fetchAll();
		
		echo '<label class="item">
				<input type="radio" name="test" value="'.$enr['image'].'" checked>
				<button  class="redim" type="submit">
				<img class="image" src="images/'.$enr['image'].'">
				</button>
				
			</label>';
		/*echo "<img src='images/".$enr['image']."' class='item'></img> 
		";*/
	}
	echo "<input name='deja_vote' type='hidden' value='".($deja_vote+1)."'>";
	echo "</div>";
?>
</form>
</div></div>
</body>
</html>
