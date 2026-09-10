# 🎨 Ai-logo-GeTel — Application Web de Gestion & Génération d'Images IA

Bienvenue sur le dépôt officiel du projet **Ai-logo-GeTel** ! Cette application web moderne permet la génération dynamique d'images et de logos via l'IA, le téléversement, le téléchargement et la modération de contenus.

---

## 🚀 Fonctionnalités Principales

### 👤 Côté Utilisateur
* **Authentification & Profil :** Inscription, connexion sécurisée, récupération de mot de passe et gestion du profil.
* **Création & Import :** 
  * Génération d'images/logos par IA via des prompts textuels.
  * Téléversement de fichiers locaux (PNG, JPG, WEBP — max 10 Mo).
* **Exploration & Interaction :** 
  * Galerie responsive avec recherche et filtres par catégories.
  * Gestion des favoris, édition légère, téléchargement HD et partage.
  * Signalement de contenus inappropriés.

### 👨‍💼 Côté Administrateur
* **Modération des contenus :** Traitement des images signalées (validation ou suppression).
* **Gestion des utilisateurs :** Modération des accès et des comptes.
* **Tableau de bord :** Suivi des statistiques globales et configuration des quotas IA.

---

## 🛠️ Architecture Technique

* **Frontend :** Application réactive (React / HTML, CSS, JS)
* **Backend :** API RESTful en PHP (ou framework Laravel / Symfony)
* **Base de données :** MySQL (ou PostgreSQL) pour les métadonnées, utilisateurs et favoris
* **Stockage Fichiers :** Cloud Object Storage (Amazon S3 / Cloudinary) ou Stockage local
* **Moteur IA :** Intégration API de génération d'images (Stable Diffusion / DALL-E)

---

## 📌 Modèle du Domaine (Entités Clés)

* **Utilisateur :** `id`, `email`, `mot_de_passe_hash`, `role` (USER/ADMIN), `statut`.
* **Image :** `id`, `url_fichier`, `prompt_ia`, `type` (GENEREE/TELEVERSEE), `statut`.
* **Favori :** Liaison N-N entre `Utilisateur` et `Image`.

---

## 🔒 Règles de Gestion Clés

1. **Authentification requise :** La génération IA, le téléversement et les favoris nécessitent une connexion active.
2. **Modération automatique :** Toute image recevant $\ge 3$ signalements est masquée automatiquement de la galerie publique.
3. **Sécurité :** Authentification via jetons JWT et chiffrement des mots de passe (bcrypt).

---

## 🤝 Comment Contribuer ?

1. **Cloner le projet :**
   ```bash
   git clone [https://github.com/HERCO237/Ai-logo-GeTel.git](https://github.com/HERCO237/Ai-logo-GeTel.git)
