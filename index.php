<?php session_start(); ?>

<!DOCTYPE html>
<html>
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
        <meta property="og:description" content="Venez votez pour vos images favorites ! Réalisé par des élèves de 2nde du lycée Louis Pasteur Avignon">
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
            <p>Vous pouvez : <b>voter pour vos images favorites,</b> ou <a href="https://twitter.com/nsi_xyz/status/1507684348820082690">Découvrir une sélection de 42 images</a></p> 
            <p>6 images vous sont présentées aléatoirement, vous en choissisez une, il est possible de voter plusieurs fois.</p>
        </article>

        <div>
            <form action="index.php" method="POST" class="vote">
                <?php
                    // Take 6 random images from database
                    $sql_images= "SELECT * FROM concours ORDER BY RAND() LIMIT 0,6 ;";

                    $sth = $dbh->prepare($sql_images);
                    $sth->execute();
                    $result_images = $sth->fetchAll();

                    $images_ids = array();
                        
                    // For each image
                    foreach ($result_images as $enr) {
                        $images_id[] = $enr["id"];

                        // Print HTML code for a clickable image
                        echo "
                        <div class='choice'>
                            <input type='radio' name='nom_image' value='{$enr['image']}' id='{$enr['image']}' onclick='this.form.submit()'>
                            <label class='item' for='{$enr['image']}'>
                                <div class='lr-borders'></div>
                                <img class='image' src='images/{$enr['image']}' alt='image {$enr['image']}'>
                            </label>
                        </div>";
                    }
                    echo "<input name='deja_vote' type='hidden' value='" . ($deja_vote + 1) . "'>";

                    $_SESSION["last_seen"] = $images_id;
                ?>
            </form>
        </div>

        <script>
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }
        </script>
    </body>
</html>
