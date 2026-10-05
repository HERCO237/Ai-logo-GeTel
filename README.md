---

## 💻 Déploiement et Configuration sur une autre machine

Pour installer et faire fonctionner ce backend sur une nouvelle machine d'un collaborateur, suivez rigoureusement ces étapes :

### 1. Prérequis système
Assurez-vous que la machine cible dispose de :
*   **PHP ≥ 8.2** (avec les extensions requises par Laravel : `bcmath`, `ctype`, `fileinfo`, `openssl`, `pdo_mysql`, etc.)
*   **Composer** (Gestionnaire de dépendances PHP)
*   Un serveur de base de données (**MySQL** / MariaDB ou PostgreSQL)

### 2. Récupération et installation du projet
Ouvrez un terminal et exécutez les commandes suivantes dans votre répertoire de travail :

```bash
# 1. Cloner le projet (si ce n'est pas déjà fait)
git clone https://github.com...
cd Ai-logo-GeTel

# 2. Accéder au dossier backend
cd backend

# 3. Installer toutes les dépendances PHP requises
composer install
```

### 3. Configuration de l'environnement local
```bash
# 1. Dupliquer le fichier d'exemple pour créer le vrai fichier de configuration
cp .env.example .env

# 2. Générer la clé de chiffrement unique de l'application Laravel
php artisan key:generate
```

### 4. Configuration de la Base de Données
Créez une base de données vide (ex: `ai_logo_getel`) via votre outil de gestion (phpMyAdmin, TablePlus, DBeaver) puis ouvrez le fichier `.env` fraîchement créé pour y renseigner vos identifiants :

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ai_logo_getel
DB_USERNAME=votre_utilisateur_local  # Généralement 'root'
DB_PASSWORD=votre_mot_de_passe_local # Généralement vide '' ou 'root'
```

### 5. Préparation de la base et du stockage
```bash
# 1. Jouer les migrations pour construire la structure des tables (users, otps, personal_access_tokens)
php artisan migrate

# 2. Créer le lien symbolique indispensable pour l'affichage public des avatars/photos de profil téléchargés
php artisan storage:link
```

### 6. Lancement de l'API
Pour démarrer le serveur de développement local :
```bash
php artisan serve
```
L'API sera accessible à l'adresse suivante : **`http://127.0.0.1:8000`**.  
Les routes de ce module s'appellent ainsi à l'adresse : `http://127.0.0`, etc.

### 🧪 Configuration Postman / Insomnia pour les tests
*   **Pour les requêtes d'inscription (`/register`) :** Envoyez les données au format `multipart/form-data` pour permettre l'envoi de la photo de profil (`photo`).
*   **Pour les routes protégées :** Ajoutez le Header `Authorization: Bearer <votre_token_recu_au_login>` à chaque requête.
