﻿### Deploiement

## Serveur
- Acheter un serveur (dédié, VPS, mutualisé...) sur une plateforme comme OVH, O2Switch etc.
- Se connecter en SSH pour accéder au serveur distant et exécuter des lignes de commandes dans le termial du serveur.
- Le configurer pour installer PHP, Apache MySQL en ligne de commandes et configurer correctement le serveur (ce que fait MAMP en local)

## Installation de Symfony sur le serveur
- Se connecter en SSH dans le dossier du serveur qui est "publique" (le dossier configuré pour être ciblé comme racine por le web).
- Récupérer le lien du projet à jour sur le GitHub et faire un Git clone vers cette url dans le dossier "publique" du serveur.
- Exécter la ligne de commandes "composer install" pour installer sur le serveur toutes les dépendances PHP du projet (Symfony, Doctrine, Twig, etc.) dans le dossier vendor (non versionné avec git)
- Copier le .env et le coller en .env.local en modifiant la variable d'environnement de la BDD pour mettre les infos du serveur de BDD fourni par OVH.
- Modifiez la variale d'environnement APP_ENV pour la passer en "PROD". Cela permet de faire fonctionner le projet en mode production (optimisation des caches, donc du chargement etc.)
- Re-créez le schéma de BDD (tables, colonnes etc.) avec "php bin/console doctrine:migrations:migrate"
- Vider les caches avec "php bin/console cache:clear --env=prod --no-debug"

## Nom de domaine
- S'assurer que Apache est configuré pour pointer directement dans le dossier public de Symfony
- Acheter un nom de domaine avec un certificat SSL (pour le HTTPS)
- Relier le nom de domaine à l'adresse IP du serveur.
  
