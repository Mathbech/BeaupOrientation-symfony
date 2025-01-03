# Beaup'Orientation

Bienvenue sur le projet **Beaup'Orientation**.

## Description

Beaup'Orientation est une application Symfony conçue pour suivre les courreur en course d'orientation. Elle utilise Docker pour l'environnement de développement et de production.

## Lien de gestion de projet

Vous pouvez suivre la gestion du projet sur Trello : [Lien Trello](https://trello.com/b/qlucubGq)

Vous pouvez également visionner les maquettes et wireframe sur figma: [Lien Figma](https://www.figma.com/design/v70S8t0TYmwUVtUorvxdQ0/Projet-beauporientation?node-id=57-33&t=F7zuzqPqAqpOQWLo-1)

Vous pouvez visualiser le diagram de la BDD sur le document PDF : [Lien PDF](https://drive.google.com/file/d/1ZxLKimm8CNrI-1cPsFqQFFyRNQMh-bZ5/view?usp=sharing)

## Prérequis

- Docker
- Docker Compose
- PHP 8.3 ou supérieur

## Installation

1. Clonez le dépôt :

    ```sh
    git clone https://github.com/votre-utilisateur/votre-repo.git
    cd votre-repo
    ```

2. Copiez les fichiers d'environnement :

    ```sh
    cp .env .env.local
    ```

3. Construisez les images Docker :

    ```sh
    docker compose build --no-cache --pull
    ```

4. Démarrez les conteneurs :

    ```sh
    docker compose up -d
    ```

5. Accédez à l'application :

    ```sh
    https://localhost
    ```

## Configuration

1. Générer les clé pulic et privés pour l'utilisation de JWT token: 
    ```sh
     php bin/console lexik:jwt:generate-keypair
    ```

## Connexion à l'api

2. Url de connexion 
    ```sh
    https://localhost/auth
    ```

## Accès aux ressources

1. Url 
    ```sh
    https://localhost/api/{entité}
    ```
    
