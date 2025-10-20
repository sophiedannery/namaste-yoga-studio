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

### Structures des branches

- **master** : branche principale contenant les versions stables et déployées en production.
- **dev** : branche de développement intégrant les fonctionnalités testées, en attente de déploiement.
- **feature/nom-de-la-fonctionnalité** : branche créée pour chaque nouvelle fonctionnalité.
- **fix/nom-du-bug** : branche dédiée à la correction d'un bug spécifique.
- **chore/nom-de-la-config** : branche dédiée à la configuration et tâches de maintenance.
- **docs/nom-du-doc** : branche dédiée à la documentation.
- **test/nom-du-test** : branche dédiée aux tests.

## Sécurité et bonnes pratiques

## Tests


## Déploiement
L'application **Namaste Yoga Studio** sera déployée sur la plateforme [Heroku](https://www.heroku.com/)

### URL de production

### Étapes de déploiement

### Sécurité en production

### Domaine personnalisé

## Fichiers SQL

### schema.sql
### data.sql

## Identifiants de test




## Ressources supplémentaires

### Visuels

- 🎨 [Charte graphique]()
- 🖼️ [Wireframes]()
- 💻 [Mockups - Desktop]()
- 📱 [Mockups - Mobile]()

### Diagramme

- 🧩 [Diagramme de séquence]()
- 🧩 [Diagramme de cas d'utilisation]()
- 🧩 [Diagramme de classe]()

### Documentation

- 🛠️ [Documentation technique]()

### Gestion de projet

- 📊 [Gestion de projet](https://www.notion.so/Namaste-Yoga-Studio-28d87135a9b280e19d3de692920d3e04)

## Licence et Contrat

Projet développé par Dannery Sophie dans le cadre de la formation **TP Développeur Web et Web mobile** (RNCP37674) - 2025

Encadrement pédagogique : **STUDI /DREETS**

📧 Contact : **dannery.sophie@gmail.com**


## 🚀 Étapes suivantes

1. Maquettage

2. Création des wireframes et maquettes desktop/mobile.

3. Intégration front-end statique, HTML / CSS responsive (Bootstrap).

4. Développement dynamique (JS), Filtres, formulaires, API Google Avis.

5. Base de données MySQL & MongoDB, Modélisation, scripts schema.sql et data.sql.

6. Développement back-end Symfony, Sécurité, gestion utilisateurs, réservations, API REST.

7. Déploiement et documentation, Docker + Heroku / OVH, guide d’installation, manuel utilisateur.