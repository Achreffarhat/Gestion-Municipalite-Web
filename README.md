# Symfony 6 Gestion Municipalite
Ce projet est un exemple de système de gestion de municipalité, qui permet de gérer différents aspects de la municipalité, notamment la gestion des utilisateurs, la gestion des véhicules, la gestion du calendrier, la gestion des réclamations et la gestion des outils.
## Requirements
- php 8
- composer
- symfony
- database
- email smtp credentials

## Installation

- add the database credentials to the .env file
- add the mail-smpt credentials to the .env file
- `composer install`
- `php bin/console doctrine:schema:create`
- `symfon serve`

## Utilisation :
Une fois l'application lancée, vous pouvez accéder aux différentes fonctionnalités de gestion municipale via les liens du menu de navigation.
## Fonctionnalités :
Les fonctionnalités principales de ce système de gestion de municipalité sont les suivantes:
- Gestion des utilisateurs :
Permet de créer, modifier ,lister, login , verification par email lors de l'inscription , restaurer mot de passe et supprimer des comptes utilisateur pour les employés de la municipalité.Les utilisateurs peuvent avoir différents rôles (administrateur, employé, etc.) et des permissions différentes selon leur rôle.
 - Gestion des véhicules :
Permet de gérer les véhicules de la municipalité, notamment leur enregistrement, leur affectation à des employés et leur maintenance.
 - Gestion du calendrier :
Permet de planifier les événements de la municipalité, tels que les réunions, les fêtes et les événements spéciaux.
 - Gestion des réclamations:
 La gestion des réclamations permet aux citoyens de signaler des problèmes dans la municipalité, tels que des problèmes de voirie, des pannes d'éclairage public, etc. Les réclamations sont ensuite traitées par les employés de la municipalité.
 - Gestion des outils
La gestion des outils permet de suivre les outils municipaux, tels que les équipements de jardinage et les outils de voirie, ainsi que les dates d'entretien.
## Contributeurs : 
- [Amineallahmekni] (https://github.com/Amineallahmekni)
- [HamzaRahmouni] (https://github.com/rahmouni29301)
- [TrabelsiAla] (https://github.com/Trabelsiala)
- [ManaiRaed] (https://github.com/manairaed)
