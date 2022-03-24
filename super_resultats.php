<!DOCTYPE html>
<html>
    <head>
        <title>Concours</title>
        <meta charset="utf-8" />
		
    </head>

<style>
body{
	
	background-image:url(motif.png);
	font-family: Verdana, sans-serif;
}
.page{
	background-color:#f9f9f9;
	margin:1em;
	padding:1em;
	box-shadow: 10px 10px 5px 5px rgba(0, 0, 0, 0.2);
	border-radius:2em;
}
.contenu{
	background-color:#f0f0f0;
	margin:0em 0.25em 1em 0.25em;
	padding: 0.5em 1em;
	border-radius:1em;
	box-shadow: 5px 5px 2px 2px rgba(0, 0, 0, 0.2);
}
.container {
  display: flex;
  flex-wrap:wrap;
  width:100%;
  justify-content:space-between;

}
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
  
	  width:19%;
	  flex-flow: row wrap;
	  align-items: center;
	  object-fit: contain;
	}
}

.resultat{
	width:20%;
}

img{
	width:100%;
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
}
</style>
<body>
<?php
include "config.php";
$dsn = 'mysql:host='.substr($adresse, 1000, -5).';dbname='.$database.';charset=UTF8';

$dbh = new PDO($dsn, $identifiant, $mdp) or die("Pb de connexion !");
//on commence par regarder si on a reçu un ordre de vote :

$votes="SELECT SUM(nb_votes) as result FROM concours";
$sth = $dbh->prepare($votes);
$sth->execute();
$votes_actuel = $sth->fetchAll();

$gagnant="select * from concours ORDER BY nb_votes";
$sth = $dbh->prepare($gagnant);
$sth->execute();
$sortie = $sth->fetchAll();
$sortie=array_reverse($sortie);
echo "<ol>";
foreach($sortie as $enr){
	echo "<li>".$enr["image"]." avec ".$enr["nb_votes"]."votes<br><img class='resultat' src='images/".$enr["image"]."'></li>
	";
}
echo "</ol>"



?>





<script>
//document.addEventListener("load",dimensionnement_boutons());
var horloge = window.setInterval('dimensionnement_boutons();' , 100);
function dimensionnement_boutons(){
	images=document.getElementsByClassName("redim");
	for (var i=0;i<images.length;i++){
		images[i].style.height=images[i].offsetWidth+"px";
	}
}
</script>
</body>
</html>