## 🚀 Procédure de déploiement (production)

### 1. Se placer dans le dossier personnel de l’utilisateur

```bash
cd ~
```

### 2. Cloner le projet

```bash
git clone <url-du-projet>
```

### 3. Accéder au répertoire du projet et passer sur la branche de production

```bash
cd BeaupOrientation-Symfony
git checkout prod
```

### 4. Préparer les fichiers d’environnement

* Copier le fichier `.env` vers `.env.local` :

```bash
cp .env .env.local
```

* Modifier `.env.local` avec les identifiants de **production** (base de données, clés, etc.)

* Créer un fichier `.env.docker` avec les variables d’environnement pour Docker, par exemple :

```env
IMAGES_PREFIX=beauporientation-
MYSQL_DB=app
MYSQL_USER=app
MYSQL_PASSWORD=password
```

### 5. Builder et démarrer les conteneurs

```bash
docker compose --env-file .env.docker build --pull --no-cache
docker compose --env-file .env.docker up -d
```

### 6. Vérifier les logs pour s'assurer que tout est bien lancé

```bash
make logs
```

---

## 🔧 Post-démarrage : installation et configuration

### 7. Entrer dans le conteneur PHP

```bash
make sh
```

### 8. Installer les dépendances PHP

```bash
composer install
```

### 9. Effectuer les migrations et vider le cache Symfony

```bash
php bin/console doctrine:migrations:migrate
php bin/console cache:clear
```

### 10. Installer les dépendances front-end et builder les assets

```bash
npm install
npm run build
```
