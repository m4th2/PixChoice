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
.fun{
	height:20%;
	max-height:100px;
	min-height:20px;
}
#ane{
	position:absolute;
	top:0%;
}
#carotte{
	position:absolute;
	top:0%;
}
#final{
	position:absolute;
	top:0%;
	right:10%;
	display:none;
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
$deja_vote=0;
if (!empty ($_POST)){
	if (array_key_exists('test', $_POST)){
		if (strlen($_POST['test'])==9){
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
<div id="ane"><img src="ane.png" class="fun"></div>
<div id="carotte"><img src="carotte.png" class="fun"></div>
<div id="final"><img src="final.png" class="fun"></div>
<div style="float:left;width:auto;"><h3>Votez pour le plus beau graphique !</h3>
A chaque tour, choisissez l'image qui vous plaît le plus parmi les 9 présentes.<br>
Elles vous sont proposées aléatoirement et à chaque clic, 9 autres apparaissent.<br>
Bon vote !</div>
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
<script>
votes=<?php echo $deja_vote?>;
if (votes<20){
	document.getElementById("ane").style.right="10%";
	document.getElementById("carotte").style.right=(60-votes*2)+"%";
	
}
else{
	document.getElementById("ane").style.display="none";
	document.getElementById("carotte").style.display="none";
	document.getElementById("final").style.display="block";
	
	
}

</script>
</body>
</html>