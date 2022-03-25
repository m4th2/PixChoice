# PixChoice

## M. Bernon, enseignant de Physique Chimie

Dans cette archive sont présents les fichiers nécessaires pour mettre en place un système permettant de voter pour différentes images, toutes placées dans le dossier "images".
Pas de contrainte particulière pour les nommer normalement (j'imagine que mettre des espaces n'est pas l'idée la plus judicieuse cependant)

Mise en place :

1. Placer toutes les images participant au concours dans le dossier images
2. Le système utilise une base de donnée dont il faut saisir les paramètres dans le config.php, j'ai laissé ceux par défaut que j'utilise avec mon serveur local de test
3. le fichier reset.php se charge d'effacer si besoin et de créer la table nécessaire au stockage des votes
4. le fichier index.php est le fichier principal permettant le vote qui ne fonctionnera qu'une fois la table créée dans la BDD
5. le fichier resultats.php donne le gagnant et super_resultats.php classe les images par ordre de vote

Bon courage !

## Rappels

On ne dépose pas les identifiants de la base SQL ou du serveur FTP sur GitHub !
Les images seront remplacées mais on peut tester pour commencer avec ces images.
