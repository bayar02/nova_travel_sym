# ✈️ NovaTravel
Une plateforme de gestion de voyages préorganisés (JavaFX & Symfony)

📌 Présentation
NovaTravel est une solution complète de gestion de voyages intégrant à la fois une application desktop (JavaFX) et une application web (Symfony 6.4). Elle permet aux utilisateurs d’explorer, réserver, planifier et gérer divers aspects d’un voyage organisé, avec un fort accent sur l’expérience utilisateur, l’automatisation et l’intelligence.

🧩 Modules Fonctionnels
La plateforme est structurée autour de 5 gestions principales, chacune composée d'au moins deux entités liées :

Gestion des Utilisateurs
➤ Création, modification, suppression, rôle (admin, client), connexion, etc.

Gestion des Hébergements
➤ Hôtels, maisons d’hôtes, disponibilité, réservation.

Gestion des Vols
➤ Vols par destination, horaires, compagnies, réservations.

Gestion des Réclamations et Réponses
➤ Réclamations des clients, réponses des administrateurs, suivi.

Gestion des Événements et Plannings
➤ Activités proposées, planification personnelle, durée, prix, intégration de calendrier.

🔗 Intégrations & Fonctionnalités Avancées
🌤️ Weather API : Affichage de la météo selon la destination dans l’interface Planning.

🤖 Intégration Gemini AI : Réponses intelligentes aux utilisateurs (chatbot).

📞 WhatsApp Integration : Redirection directe pour contact via WhatsApp.

📅 API Google Calendar : Synchronisation et affichage des événements planifiés.

💳 Paiement via Stripe : Génération d’un QR code menant vers une page de paiement sécurisée.

📄 Génération de PDF : Téléchargement de plannings ou réservations.

📊 Exportation Excel (XLS) : Export des données des modules.

🔍 Recherche & Tri : Recherche intelligente, tri dynamique dans les tableaux.

💬 Chatbot intégré : Assistance automatique dans le site web.

📄 Pagination : Navigation fluide sur les listes d’éléments.

🖥️ Application JavaFX
Technologies utilisées :
Java 17

JavaFX

SceneBuilder

JDBC / MySQL

Librairies tierces (QR Code, PDF, Excel, etc.)

Fonctionnalités spécifiques :
Interface riche et dynamique.

API météo et Google Calendar intégrées dans le module Planning.

QR Code interactif pour paiement Stripe.

Système d’authentification multi-rôle.

Export PDF / Excel des données.

Tri & recherche intelligente.

🌐 Application Web Symfony
Technologies utilisées :
Symfony 6.4

PHP 8.x

Twig

Doctrine ORM

MySQL

JavaScript / Bootstrap

Fonctionnalités spécifiques :
Interface responsive (mobile friendly).

Intégration du chatbot AI et WhatsApp.

Stripe, génération de PDF et QR code.

Dashboard administrateur.

Statistiques et filtres dynamiques.

API REST pour modules partagés avec l'app JavaFX.

🗂️ Structure du projet
🔹 Backend commun :
Base de données partagée nova_travel

Schéma relationnel optimisé

Sécurité (JWT, rôles utilisateurs, validation)

🔹 JavaFX :
css

📁 src/
├── controllers/
├── models/
├── services/
├── utils/ (QR, PDF, Excel, API)
🔹 Symfony :
cpp


📁 src/
📁 templates/
📁 public/
📁 assets/
📁 migrations/
⚙️ Installation et Lancement
🖥️ JavaFX :
Cloner le dépôt : git clone https://github.com/username/novatravel-java.git

Importer dans un IDE compatible JavaFX (ex: IntelliJ, NetBeans).

Configurer la base de données (nova_travel) avec votre identifiant.

Lancer l’application via la classe principale Main.java.

🌐 Symfony :
Cloner le dépôt : git clone https://github.com/username/novatravel-web.git

Installer les dépendances :


composer install
npm install && npm run build
Configurer le fichier .env pour la connexion MySQL.

Exécuter les migrations et fixtures :


php bin/console doctrine:migrations:migrate
php bin/console doctrine:fixtures:load
Lancer le serveur : symfony server:start

👨‍💻 Membres de l’équipe
👤 Djarboui Meriem – Responsable Planning & Événements + API & intégration web

👤 Bayar Aziz – Gestion des utilisateurs

👤 Chtioui Kenza – Gestion des hébergements

👤 Kraoua Monji – Gestion des vols

👤 Ben Jemaa Feryel – Réclamations & Réponses

🔧 Travail collaboratif via GitHub (6 branches fonctionnelles)

📣 À venir
Système de notifications en temps réel

Générateur d’itinéraires personnalisés

Application mobile Flutter (en réflexion)

🎯 Objectif
NovaTravel vise à proposer une expérience de voyage numérique moderne, complète et intuitive, en combinant ergonomie, technologies avancées, et intégration de services intelligents.

Souhaitez-vous également un README en anglais pour la version GitHub internationale ?







Vous avez dit :
make it as short as possible


ChatGPT a dit :
Voici une version courte et professionnelle du fichier README pour votre projet NovaTravel, en français :

✈️ NovaTravel
Plateforme de gestion de voyages (JavaFX & Symfony)

🧭 Description
NovaTravel est une application bilingue (desktop JavaFX + web Symfony) dédiée aux voyages organisés, intégrant 5 modules clés :

Utilisateurs

Hébergements

Vols

Événements & Plannings

Réclamations & Réponses

🚀 Fonctionnalités
🌤️ Météo par destination (API Weather)

🧠 Chatbot IA (Gemini)

📱 WhatsApp intégré

📅 Google Calendar

💳 Paiement Stripe via QR Code

📄 PDF / Excel / QR

🔎 Tri, recherche, pagination

💬 Chat en ligne

🖥️ JavaFX
Interfaces dynamiques (SceneBuilder)

Intégration API météo, Google Calendar

Exports PDF/XLS, QR codes interactifs

🌐 Symfony Web
Interface responsive

Dashboard admin

Paiement Stripe, chatbot, WhatsApp

Authentification, rôles

⚙️ Lancement rapide
JavaFX

git clone <repo-java>
Configurer DB et lancer Main.java
Symfony

git clone <repo-web>
composer install
php bin/console doctrine:migrations:migrate
symfony server:start
👥 Équipe
Projet développé par 5 étudiants en pré-ingénierie (Esprit).
Modules répartis, travail via GitHub (6 branches).
