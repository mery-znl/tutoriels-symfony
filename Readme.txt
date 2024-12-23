TPTutorial - Documentation

Description:
TPTutorial est une application web Symfony permettant la gestion de tutoriels. 
Elle inclut des fonctionnalités telles que la gestion des matières, des chapitres, des tutoriels et des commentaires, ainsi que la gestion des utilisateurs avec différents rôles.

Prérequis :
- PHP >= 8.1
- Composer
- Symfony CLI
- Une base de données (MySQL recommandée)
- WAMP/MAMP/LAMP (selon votre système d'exploitation)

Installation :
1. Clonez ce dépôt :
   git clone <URL_DE_VOTRE_DEPOT>

2. Installez les dépendances avec Composer :
   composer install

3. Configurez la base de données dans le fichier .env :
   DATABASE_URL="mysql://user:password@127.0.0.1:3306/tptutorial"

4. Effectuez les migrations pour créer les tables dans la base de données :
   php bin/console doctrine:migrations:migrate

5. Lancez le serveur de développement :
   symfony server:start

6. Accédez à l'application via :
   http://127.0.0.1:8000

Fonctionnalités :
- Authentification (connexion, inscription, réinitialisation de mot de passe).
- Rôles utilisateur (Admin, User, Banned) avec accès restreint selon les permissions.
- Gestion des entités : création, lecture, modification, suppression (CRUD) pour les matières, chapitres, tutoriels et commentaires.

Structure du projet :
- src/Controller : Contient les contrôleurs pour gérer la logique de l'application.
- src/Entity : Définit les entités de la base de données.
- templates/ : Contient les fichiers Twig pour l'affichage.
- public/css/styles.css : Fichier CSS pour le style de l'application.

Notes :
- Assurez-vous que l'adresse email configurée dans le fichier .env est correcte pour les fonctionnalités d'envoi d'email.
- Supprimez une matière uniquement si elle n'est pas associée à des tutoriels pour éviter des erreurs d'intégrité.

Auteur :
- Votre Nom
