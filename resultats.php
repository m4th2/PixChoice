<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr" prefix="og: http://ogp.me/ns#">
    <head>
        <title>PixChoice.nsi.xyz</title>

        <!-- META VERSE -->
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- OG CARD -->
        <!--
            J'ai mis des trucs au pif, faudra le mettre à jour à la fin pour le site.    
        -->
        <meta property="og:title" content="pixchoice.nsi.xyz">
        <meta property="og:type" content="website">
        <meta property="og:url" content="https://pixchoice.nsi.xyz">
        <meta property="og:image" content="https://pixchoice.nsi.xyz/icon.jpg">
        <meta property="og:description" content="Venez votez pour vos images favorites ! Réalisées par des élèves de 2nde du lycée Louis Pasteur Avignon">
        <meta property="og:site_name" content="nsi.xyz">
        
        <!-- LINKS -->
        <link rel="stylesheet" href="stylesheets/main_style.css">
        <link rel="shortcut icon" href="icon.jpg">
    </head>

<body>
        <?php
            // Include IDs for PDO connexion
            include("config.php");

            // Create PDO
            $dsn = 'mysql:host=' . substr($adresse, 1000, -5) . ';dbname=' . $database . ';charset=UTF8';
            $dbh = new PDO($dsn, $identifiant, $mdp) or die("Pb de connexion !");

            // Do we have to post a vote ?
            $deja_vote = 0;
            if (!empty($_POST)){
                if (isset($_POST["nom_image"]) && strlen($_POST["nom_image"]) == 7){
                    // Vote for a given image (image in $_POST)
                    $ajout="UPDATE concours
                        SET nb_votes = nb_votes + 1
                        WHERE image = '" . $_POST["nom_image"] . "'";

                    $sth = $dbh -> prepare($ajout);
                    $sth -> execute();

                    // Add fellow to votelist
                    $votant_query = "SELECT ip FROM votant";
                    $sth = $dbh -> prepare($votant_query);
                    $sth -> execute();
                    $votant = $sth -> fetchAll();

                    $current_ip = $_SERVER["REMOTE_ADDR"];

                    if (!in_array($current_ip, $votant)) {
                        $add_votant_query = "INSERT INTO votant VALUES (?)";
                        $sth = $dbh -> prepare($add_votant_query);
                        $sth -> execute(array($current_ip));
                    }

                    foreach ($_SESSION["last_seen"] as $enr) {
                        // Increment image occurrence number
                        $ajout="UPDATE concours
                            SET nb_fois = nb_fois + 1
                            WHERE id = '" . $enr . "'";

                        $sth = $dbh->prepare($ajout);
                        $sth->execute();
                        $result_ajout = $sth->fetchAll();
                    }
                }
                $deja_vote = intval($_POST['deja_vote']);
            }

            // Get number of votes and people
            $votes = "SELECT SUM(nb_votes) as result FROM concours";
            $sth = $dbh -> prepare($votes);
            $sth -> execute();
            $votes_actuel = $sth -> fetchAll();

            $people = "SELECT COUNT(ip) FROM votant";
            $sth = $dbh -> prepare($people);
            $sth -> execute();
            $people_actuel = $sth -> fetchAll();
        ?>
        
        <header>
            <h1>Les mathématiques sont belles, 3<sup>ème</sup> ed. 2022</h1>
            <p>Votes : <?php echo $votes_actuel[0][0]; ?> | Votants : <?php echo $people_actuel[0][0]; ?></p>
        </header>

        <article>
            <p>Les élèves de en classe de seconde 5 et 7 du lycée Louis Pasteur d'Avignon ont produit 55 images uniques sur leur calculatrice NumWorks.</p>
            <p>Vous pouviez voter pour vos images favorites, mais le vote est clos. <a href="https://twitter.com/nsi_xyz/status/1507684348820082690">Découvrez sur twitter une sélection de 42 images</a></p>
            <p> Cette page présente le résultat du vote</p> 
        </article>

<?php
include "config.php";
$dsn = 'mysql:host='.substr($adresse, 1000, -5).';dbname='.$database.';charset=UTF8';

$dbh = new PDO($dsn, $identifiant, $mdp) or die("Pb de connexion !");
//on commence par regarder si on a reçu un ordre de vote :
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
		
}
$votes="SELECT SUM(nb_votes) as result FROM concours";
$sth = $dbh->prepare($votes);
$sth->execute();
$votes_actuel = $sth->fetchAll();

$gagnant="select * from concours";
$sth = $dbh->prepare($gagnant);
$sth->execute();
$sortie = $sth->fetchAll();

$maximum=0;
$gagnant=0;
$taux_max=0;
$prefere=0;
for ($i = 1; $i < count($sortie); $i++) {
    if ($sortie[$i]["nb_votes"]>$maximum){
		$maximum=$sortie[$i]["nb_votes"];
		$gagnant=$i;
	}
	if ($sortie[$i]["nb_fois"]!=0){
		if ($sortie[$i]["nb_votes"]/$sortie[$i]["nb_fois"]>$taux_max){
			$taux_max=$sortie[$i]["nb_votes"]/$sortie[$i]["nb_fois"];
			$prefere=$i;
		}
	}
}



?>
<div class="page">
<div style="float:left;width:auto;"><h2>Résultats </h2>
Le concours a reçu <?php echo $votes_actuel[0][0];?> votes.<br>
<p>L'image ayant reçu le plus de votes est : <?php echo $sortie[$gagnant]["image"].", avec ".$sortie[$gagnant]["nb_votes"]." votes."; ?></p>
<img class='resultat' src='images/".<?php echo $sortie[$gagnant]["image"]."?>'>	
<p>L'image ayant le plus fort taux de victoires : <?php echo $sortie[$prefere]["image"].", avec ".round($taux_max*100)."% de victoires par présentation."; ?></p>
<img class='resultat' src='images/".<?php echo $sortie[$prefere]["image"]."?>'>
</div>
<div style="clear:both;">




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
