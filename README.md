# Installation et Mise en Route de Symfony avec Docker

Ce fichier explique comment installer et démarrer le projet avec Docker Compose.

---

## Prérequis

Avant de commencer, assurez-vous que les outils suivants sont installés sur votre machine :

- **Docker**
- **Docker Compose** (version 2.10 ou supérieure)

Vous pouvez suivre [la documentation officielle pour installer Docker Compose](https://docs.docker.com/compose/install/).

---

## Étapes d'installation

### 1. Cloner le projet ou télécharger les fichiers

Assurez-vous d’avoir une copie du projet Symfony avec le fichier `docker-compose.yml` à la racine.

---

### 2. Construire les images Docker

Construisez les images Docker de votre projet en exécutant la commande ci-dessous dans un terminal à la racine du projet :


docker compose build --no-cache

### 3. Démarrer les conteneurs

Lancez les conteneurs en arrière-plan avec la commande suivante :

HTTP_PORT=8000 HTTPS_PORT=4443 HTTP3_PORT=4443 docker compose up --pull always -d --wait

### 4. Accéder à l'application Symfony

Accès en HTTP

Dans votre navigateur, accédez à l'application via l'URL suivante :

http://localhost:8000

### 5. Arrêter les conteneurs
Lorsque vous avez terminé d'utiliser le projet, arrêtez et supprimez les conteneurs en utilisant la commande suivante :

docker compose down --remove-orphans

### 6. Accéder au conteneur PHP (optionnel)

Si vous devez interagir avec la console Symfony ou exécuter d'autres commandes depuis le conteneur PHP, utilisez cette commande pour y accéder :

sudo docker exec -it gamevault_back-php-1 bash


bdd : 

DATABASE_URL="mysql://u421430464_gamevaultUser:AjfjDFUfhf74364@193.203.168.4:3306/u421430464_gamevault?serverVersion=mariadb-10.11.10&charset=utf8"



EntryPoints de l'API
Le contrôleur GameController expose plusieurs routes disponibles pour les interactions avec l'API. Voici une documentation détaillée des endpoints.

### 1. Récupérer tous les jeux
Description
Récupère la liste de tous les jeux disponibles.

Méthode HTTP : GET
URL : /api/game
Nom de route : app_game_list
Exemple de Réponse
[
    {
        "id": 1,
        "name": "Game 1",
        "slug": "game-1",
        "isFavorite": false
    },
    {
        "id": 2,
        "name": "Game 2",
        "slug": "game-2",
        "isFavorite": true
    }
]

### 2. Ajouter un jeu aux favoris
Description
Ajoute un jeu à la liste des favoris.

Méthode HTTP : POST
URL : /api/game/favorite
Nom de route : app_game_favorite_add
Corps de la Requête (JSON)
{
    "game": {
        "id": 1,
        "name": "Game 1",
        "slug": "game-1",
        "isFavorite": true
    }
}

Exemple de Réponse
{
    "message": "Game added to favorites",
    "game": {
        "id": 1,
        "slug": "game-1",
        "name": "Game 1",
        "isFavorite": true
    }
}

### 3. Supprimer un jeu des favoris
Description
Supprime un jeu de la liste des favoris.

Méthode HTTP : DELETE
URL : /api/game/delete
Nom de route : app_game_favorite_remove
Corps de la Requête (JSON)
{
    "slug": "game-1"
}

Exemple de Réponse
En cas de succès :

{
    "success": true
}

En cas d'erreur (jeu non trouvé) :

{
    "error": "Game not found"
}