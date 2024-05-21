### Vie quotidienne

Restons humble, les besoins changent souvent et le code aussi, un code qui peut sembler mal conçu, complexe, est surement dû à une suite d’itérations sans connaissance précise du besoin final lors de son écriture.

En cas de bugs chercher des solutions plutôt que des coupables.

Ne chercher à identifier l’auteur d’un bug, seulement si nécessaire pour sa résolution, pour comprendre le code écrit et les intentions lors de son écriture.

Si le bug est simple, le corriger directement.

En cas de bug récurrent ou d’optimisation possible avertir la personne avec l’unique intention de la faire progresser.
Nous travaillons en équipe, lorsqu’un collègue nous aide à réaliser une fonctionnalité ou comprendre un bug on préféra utiliser ne « nous avons fait » que le « j’ai ».

Toute suggestion est la bienvenue. Votre avis et votre expertise compte 😊

### Environnement de développement

- Symfony : 7.0.x
- Bootstrap : 5.3.x
- IDE : PHPStorm / VSCode

#### Récupérer le projet

```sh
$ git clone git@gitlab.com:mlejeune/carsfleet.git
$ cd carsfleet
```

#### Sur un environnement `Docker`

- Dépendances : Git, PHP 8.2.x, Symfony CLI, composer (2+)
- Base de données : MySQL 5.7, MariaDB 10.4

|  Container |     Port    |                       URL                       |
|:----------:|:-----------:|-------------------------------------------------|
| www        | 8080        | http://localhost:8080                           |
| db         | 3306        |                                                 |
| phpmyadmin | 8081        | http://localhost:8081                           |
| mailhog    | 1025 / 8025 | smtp://localhost:1025 / http://loacalhost:8025  |

```sh
$ docker-compose up -d --build
```

> Lancer la commande : `symfony check:requirements` pour adapter la configuration php.ini

#### Installation de la base de données

```sh
$ php bin/console doctrine:migrations:migrate --all-or-nothing
```

### Bonnes pratiques

> Les conventions sont un ​ensemble de règles communes​ pour que le code soit plus ​ facile à lire et à comprendre.

#### Structure des dossiers​

​Le projet respecte la structure standard des dossiers de Symfony.​ Le nom des dossiers doit être en snake_case.​
- `/assets` pour les fichiers js, css/scss ainsi que les fichiers statiques. Tous ces fichiers sont compilés, versionnés et déplacés dans `/public` par webpack lors du déploiement.​
- `/bin` pour les utilitaires. Contient par défaut la console symfony et phpunit.​
- `/config` pour les fichiers de configuration de symfony et des bundles.​
- `/public` pour les fichiers accessibles via le navigateur.​
- `/src` pour le code source de l’application.​
- `/templates` pour les vues TWIG.​
- `/tests` pour les fichiers relatifs aux tests, unitaires et d’intégration.​
- `/translations` pour les traductions​
- `/var` pour les fichiers temporaires : le cache et les logs.​

Dans `/src` on regroupe les classes du même type dans un même dossier. On peut également créer des sous-dossiers ci nécessaire. Exemple de dossiers courants :​
- `/src/Controller​`
- `/src/DataFixtures​`
- `/src/Entity​`
- `/src/EventSubscriber​`
- `/src/Exception​`
- `/src/Form​`
- `/src/Repository​`
- `/src/Security​`
- `/src/Service​`
- `/src/Trait​`

Les classes PHP se trouvant dans un même dossier appartiennent au même namespace. Qui est établi selon l’arborescence conformément à la norme PSR-4. Les dossiers dans src doivent donc également être nommés en UpperCamelCase.​

#### Nommage des fichiers​

- Utiliser **UpperCamelCase** pour nommer les classes et les fichiers PHP : `NewClass.php`
- Suffixer les traits, interfaces et exception...
    - Exemple avec :
        - Trait : `NewTrait.php`
        - Interface : `NewInterface.php`
        - Exception : `NewException.php`
- Les classes abstraites doivent être préfixées avec `Abstract​`
- Utiliser **snake_case** pour nommer les fichiers twig et de configuration
- Utiliser **kebab-case** pour les autres fichiers css, js, images...

#### Nommage des variables et méthodes​

- Utiliser **camelCase** pour nommer les variables et méthodes PHP : `$newVar` / `newFunction()`
- Utiliser **UPPER_SNAKE_CASE** pour nommer les constantes PHP : `NEW_CONST`
- Utiliser **snake_case** pour les variables TWIG et les paramètres de configuration. : `config_var`
- Ne pas utiliser les varibles de moins de 3 caractères, exception pour l'attribut `$id` dans les entités

#### Norme PSR

​Le code doit être en anglais et formaté selon la norme PSR-2.​

​Les commentaires de méthodes, de classes et du code doivent être en français.​ Commenter régulièrement le code pour faciliter sa compréhension : https://symfony.com/doc/current/contributing/code/standards.html ​

Plus d’information sur les règles PSR (PHP Standards Recommendations):​ https://www.php-fig.org/psr/ ​


> Ne jamais utiliser directement​ les variables globales php :​ `$_GET`, `$_POST`, `$_COOKIE`, ​`$_REQUEST`, `$_SESSION` : https://www.php.net/manual/fr/language.variables.superglobals.php

```sh
$ git commit -m "feature: Ceci est un commit"
```

#### GIT

Il convient de créer une branche pour chaque fonctionnalité et des petites « Merge request ».​

- Faire des « commits » et « pusher » souvent (au moins tous les jours) : Le commit régulier réduit souvent la charge globale des modifications faîtes sur un seul commit et, encore une fois, nous permet de valider uniquement les modifications associées. De plus, il nous permet de partager notre code plus fréquemment avec les autres. De cette façon, tout les développeurs peuvent plus facilement intégrer les modifications régulièrement et éviter les conflits de fusion. En revanche, le fait d'avoir peu de grands « commits » et de les partager rarement rend difficile à la fois la résolution des conflits et la compréhension de ce qui s'est passé.​
- Tester avant de faire des commits.​
- Écrire des messages normalisés pour les commits : Il faut commencer le message du commit par le type de commit, ensuite un bref résumé des modifications. Le corps du message doit fournir des réponses détaillées aux questions suivantes : Qu’elles sont les motivations du changement ? Quoi de neuf sur ces modifications ?
- Les branches : Les développeurs peuvent créer des branches préfixées par `feature/` ou `bugfix/` et faire des « merge requests » vers les branches `develop`, `release/`, `hotfix/` ou autres branches protégées gérées par les lead-devs.

​Suivre les recommandations de https://www.conventionalcommits.org/ ​

Ainsi que les bonnes pratiques Symfony : https://symfony.com/doc/current/best_practices.html

#### De plus

- Côté front attention aux points suivants :
    - Ne pas laisser de fichiers debug
    - Ne pas utiliser de balise style dans le html (sauf cas spécifique width et height)
    - Ne pas laisser de balise vide (par exemple : `<p></p>`) si elles n’ont pas d’identifiant et peuplée en javascript
    - Pour les assets CSS/JS ne pas utiliser de CDN mais importer les dépendances avec yarn
    - Utiliser des variables dans les SCSS pour les couleurs
    - Préférer les valeurs relatives dans les CSS : `rem` / `em`
    - Le code javascript doit être dans des fichiers séparés gérées par webpack

- Coté back :
    - Ne pas laisser de code de debug
    - Ne pas laisser de code commenté
    - Préférer sortir rapidement des fonctions et limiter l’usage d’if / else