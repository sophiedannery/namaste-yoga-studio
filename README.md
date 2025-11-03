# Namaste Yoga Studio - Site de réservation de cours de yoga

**Namaste Yoga Studio** est une application web et web mobile destinée à la gestion des cours de yoga du centre "Namaste Yoga Studio" situé à Buis-les-Baronnies.
Le site permettra aux visiteurs de découvrir les cours et professeurs, et aux élèves de **réserver une place en ligne**.

## 🎯 Objectifs

- Digitaliser la réservation des cours.
- Permettre la gestion des plannings pour les professeurs.
- Offrir à l'administrateur une vision d'ensemble via un tableau de bord.

## 🧩 Stack technique

| Service               | Technologie                          | Rôle                                                    |
| --------------------- | ------------------------------------ | ------------------------------------------------------- |
| Front-end             | HTML5 / CSS3 (Bootstrap), JavaScript | Maquettage et dynamisme des interfaces                  |
| Back-end              | PHP 8 / Symfony                      | Traitements serveurs, sécurité                          |
| Base de données SQL   | MySQL                                | Données principales : utilisateurs, cours, réservations |
| Base de données NoSQL | MongoDB                              | Stockage de logs et statistiques                        |
| Conteneurisation      | Docker                               | Environnement reproductible et isolé                    |
| Serveur web           | Nginx                                | Gestion des requêtes HTTP                               |
| Versioning            | Git / GitHub                         | Suivi de versions et collaboration                      |

## ⚙️ Installation et configuration de l'environnement

### 1. Prérequis

- Docker et Docker compose installés
- Git installé
- Port **8080** libre pour l'application
- Port **3306** libre pour MySQL
- Port **27017** libre pour MongoDB

### 2. Cloner le projet

```bash
git clone https://github.com/sophiedannery/namaste-yoga-studio.git
cd namaste-yoga-studio
```

### 3. Lancer les conteneurs

```bash
docker compose up -d
```

_Note : si vous avez modifié le Dockerfile :_

```bash
docker compose up --build -d
```

### 4. Vérifier les services

```bash
docker compose ps
```

Résultat attendu :

```bash
SERVICE   STATUS    PORTS
app       Up        9000/tcp
web       Up        0.0.0.0:8080->80/tcp
db        Up        3306/tcp
mongo     Up        27017/tcp
```

### 5. Vérifier les connexions aux bdd

```bash
docker compose exec db mysql -u namaste_user -p -e "SHOW DATABASES;"
docker compose exec mongo mongosh -u root -p root --authenticationDatabase admin --eval "show dbs"
```

## Structure du projet

| Dossiers      | Description                                                   |
| ------------- | ------------------------------------------------------------- |
| public/       | Fichiers accessibles publiquement (CSS, images, index.php...) |
| src/          | Code source PHP (contrôleurs, entités, repository...)         |
| templates/    | Vues Twig                                                     |
| migrations/   | Fichiers de migration Doctrine                                |
| .env/         | Fichier d'environnement                                       |
| README.md     | Documentation projet                                          |

## Fonctionnalités principales

Résumé des User Stories / fonctionnalités implémentées :

- Recherche de cours de yoga du studio
- Réservation de cours
- Création d'un compte élève
- Espace élève avec planning
- Création d'un compte professeur
- Espace professeur avec planning et nouveau cours
- Tableau de bord administrateur (statistiques, gestion de comptes)

### Structures des branches

- **master** : branche principale contenant les versions stables et déployées en production.
- **dev** : branche de développement intégrant les fonctionnalités testées, en attente de déploiement.
- **feature/nom-de-la-fonctionnalité** : branche créée pour chaque nouvelle fonctionnalité.
- **fix/nom-du-bug** : branche dédiée à la correction d'un bug spécifique.
- **chore/nom-de-la-config** : branche dédiée à la configuration et tâches de maintenance.
- **docs/nom-du-doc** : branche dédiée à la documentation.
- **test/nom-du-test** : branche dédiée aux tests.

### Processus de développement :

1. Création d'une nouvelle fonctionnalité : branche `feature/...` à partir de `dev`.
2. Développement local et commits fréquents : (`feat:`, `fix:`...).
3. Tests manuels.
4. Merge vers `dev` une fois la fonctionnalité testée et validée.
5. Merge de `dev` vers `master` uniquement lors d'un déploiement.

## Sécurité et bonnes pratiques

## Tests


## Déploiement
L'application **Namaste Yoga Studio** est déployée sur la plateforme [Heroku](https://www.heroku.com/)

### URL de production

https://namaste-yoga-studio.fr

### Étapes de déploiement

. Création de l'application Heroku

```bash
heroku login
heroku create namaste-yoga-studio-buis
```

2. Définition du Procfile
   `web: heroku-php-apache2 public/`

3. Configuration des variables d'environnement

```bash
heroku config:set APP_ENV=prod
heroku config:set APP_SECRET=your_app_secret
heroku config:set APP_DEBUG=0
heroku config:set MAILER_DSN=smtp://user:pass@mailtrap.io
heroku config:set MONGODB_URL="mongodb+srv://..."
```

4. Connexion aux bases de données

Base de données MySQL via JawsDB
```bash
heroku addons:create jawsdb:kitefin
heroku config:set DATABASE_URL=$(heroku config:get JAWSDB_URL)
```
Base de données NoSQL via MongoAtlast
Créer cluster sur MongoAtlas
Récupérer URL de connexion
```bash
heroku config:set MONGODB_URL=”mongodb+srv://…”
```

5. Exécution des migrations Doctrine
   `php bin/console doctrine:migrations:migrate`

6. Déploiement du code
   `git push heroku master`

### Sécurité en production

- HTTPS activé automatiquement (Let's Encrypt via Heroku)
- Redirection forcée vers l'URL sécurisée https://ecoride-app.fr
- Variables sensibles stockées en dehors du code source
- Auncun fichier `.env` versionné grâce au `.gitignore`

### Domaine personnalisé

Le nom de domaine _namaste-yoga-studio.fr_ a été acheté chez [Gandi](https://www.gandi.net/fr) et configuré pour pointer vers **Heroku** via :

- un enregistrement **CNAME**
- un enregistrement **ALIAS**

Toutes les requêtes sont redirigées vers l'URL https://namaste-yoga-studio.fr.

## Fichiers SQL

### schema.sql

Le fichier [backup.sql](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/db/backup.sql) contient une sauvegarde complète de la base (structure + données) à un instant T. Il permet de restaurer rapidement un environnement identique


**Commande pour exécuter :**
```bash
mysql -u root -p namaste_test2 < backup.sql
```


### schema.sql

Le fichier [schema.sql](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/db/schema.sql) contient **le script de création des tables** nécessaires au fonctionnement de l'application.

Tables créées : `user`, `session`, `reservation`, `room`, `classType`, `review`, `suspension`

**Commande pour exécuter :**

```bash
mysql -u root -p namaste_test2 < schema.sql
```

### data.sql

Le fichier [data.sql](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/db/data.sql) contient **un jeu de données** permettant de tester l'application. Il comprend notamment :

- des utilisateurs avec différents rôles (admin, teacher, student)
- des types de cours 
- des cours de yoga 
- des réservations à venir et terminées
- des annulations
- des salles
- une suspension
- des avis

**Commande pour insérer les données :**

```bash
mysql -u root -p namaste_test2 < data.sql
```

Il est possible d'adapter les identifiants, mots de passe et autre selon vos besoins.



## Identifiants de test

Les identifiants de test sont disponibles dans le [Manuel d'utilisation]().


## Ressources supplémentaires

### Visuels

- 🎨 [Charte graphique](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Maquettes%20et%20charte%20graphique/Charte%20Graphique%20-%20Namaste%20Yoga%20Studio.pdf)
- 🖼️ [Wireframes](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Maquettes%20et%20charte%20graphique/Wireframes%20-%20Namaste%20Yoga%20Studio.pdf)
- 💻 [Mockups - Desktop](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Maquettes%20et%20charte%20graphique/Mockups%20-%20Desktop%20-%20Namaste%20Yoga%20Studio.pdf)
- 📱 [Mockups - Mobile](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Maquettes%20et%20charte%20graphique/Mockups%20-%20Mobile%20-%20Namaste%20Yoga%20Studio.pdf)

### Modélisation bdd

- 🧩 [Modèle Conceptuel de Données](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Mod%C3%A9lisation%20bdd/MCD%20-%20Namaste.png)
- 🧩 [Modèle Logique de Données](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Mod%C3%A9lisation%20bdd/MLD%20-%20Namaste.png)
- 🧩 [Modèle Physique de Données](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Mod%C3%A9lisation%20bdd/MPD%20-%20Namaste.png)

### Diagramme

- 🧩 [Diagramme de séquence]()
- 🧩 [Diagramme de cas d'utilisation](https://github.com/sophiedannery/namaste-yoga-studio/blob/master/docs/Diagrammes/Diagramme%20d'utilisation.jpeg)
- 🧩 [Diagramme de classe]()

### Documentation

- 🛠️ [Documentation technique]()

### Gestion de projet

- 📊 [Gestion de projet](https://www.notion.so/Namaste-Yoga-Studio-28d87135a9b280e19d3de692920d3e04)

## Licence et Contrat

Projet développé par Dannery Sophie dans le cadre de la formation **TP Développeur Web et Web mobile** (RNCP37674) - 2025

Encadrement pédagogique : **STUDI /DREETS**

📧 Contact : **dannery.sophie@gmail.com**


