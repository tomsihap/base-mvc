# Utilisation du projet

> ** NOTE :** Nous commençons à travailler avec un projet dont la quantité de fichier est importante. Il est **très très très* important que vous soyez vigilants sur le nommage des fichiers :
- Controllers : `ArticlesController.php` (PascalCase, pluriel pour le nom de la table)
- Models : `Article.php` (PascalCase, singulier pour le nom de la table)
- Vues : `views/articles/index.php` (camelCase, pluriel pour le nom de la table)

> De plus, **utilisez les raccourcis !**. Pour trouver rapidement un fichier, vous pouvez faire `ctrl+p` et taper le début du nom de fichier puis `Entrée` pour l'ouvrir.

## 1. Installation des dépendances
Les paquets `symfony/var-dumper` et `bramus/router` ont déjà été importés au projet dans le fichier `composer.json` (grâce à la commande `composer require bramus/router` par exemple).

L'idée est d'importer les dépendances requises avec Composer, qui installera les dépendances dans le dossier `vendor`, mais de partager le projet **sans** le dossier `vendor`.

En effet, la liste des dépendances existe dans `composer.json`, il suffit juste de partager la liste et de réinstaller les dépendances en local plutôt que de partager `vendor` (potentiellement plusieurs milliers de fichiers dedans !).

> **ATTENTION :** Vérifiez bien que le terminal est actuellement dans le dossier du projet ! Pour en être sûrs, ouvrez **directement** le dossier du projet dans VSCode (`ctrl+shift+o` ou "File > Open Folder" ou "Fichier > Ouvrir le dossier"), et ouvrez le terminal d'ici (`ctrl+ù`)

### Installer les dépendances

> *Note* : Un terminal / une console, c'est presque pareil ! Pour être précis, le terminal est le programme qui ouvre une console.


> *Note* : Le `$` dans la commande suivante n'est pas à taper dans la console. Il indique juste que la ligne est une ligne de terminal (cela vient du $ dans le terminal Bash des systèmes Unix).

Ouvrez un terminal et saisissez :
```
$ composer install
```

## 2. index.php

Le fichier `index.php` est composé comme suit :


D'abord, on importe l'autoloader de Composer, c'est un fichier qui chargera d'un coup toutes les dépendances installées avec Composer.

```php
require __DIR__ . '/vendor/autoload.php';
```


`index.php` inclut aussi tous les fichiers de configuration nécessaires au projet, on va les découvrir au chapitre suivant.
```php
require 'config/aliases.php';
require 'config/config.php';
require 'config/helpers.php';
require 'config/Db.php';
```

La fonction `spl_autoload_register` prend en argument une liste de classes pour les charger au moment où l'on en a besoin : un peu comme l'autoload.php de Composer, mais pour nos propres classes !

L'argument passé à `spl_autoload_register` est une fonction anonyme qui va scanner les dossiers que nous listons dans la constante `CLASSES_SOURCES` (voir config plus bas).

```php
spl_autoload_register (function ($class) {
    $sources = array_map(function($s) use ($class) {
        return $s . '/' . $class . '.php';
    },
    CLASSES_SOURCES);

    foreach ($sources as $source) {
        if (file_exists($source)) {
            require_once $source;
        }
    }
});
```

Enfin, lorsque tout le projet est chargé correctement, on peut charger la liste des routes.


```php
require 'routes.php';
```


## 3. /config

Le dossier `/config` contient plusieurs fichiers de configuration à adapter à votre projet :

### aliases.php

Liste des aliases de classes (comme un `use \Bramus\Router\Router` pour tout le projet).
```php
class_alias('\Bramus\Router\Router', 'Router');
```

### config.php

Constantes de configuration du projet :

- Configuration PHP du projet si besoin (`ini_set`)
- Constantes pour les credentials de la base de données

- le titre du site, pour l'afficher sur toutes les pages et l'éditer ici facilement.

- la `base_url` qui va nous aider à avoir des liens propres (voir plus tard)

- la liste des dossiers contenant potentiellement des classes
```php
ini_set('allow_url_include', 1);

const DB_HOST = 'localhost';
const DB_PORT = '3306';
const DB_NAME = 'videoclub';
const DB_USER = 'root';
const DB_PWD  = '';

const WEBSITE_TITLE = "Mon nouveau site en MVC";
const BASE_URL = "localhost/videoclub";

const CLASSES_SOURCES = [
    'src/controller',
    'config',
    'src/model',
];
```

### Db.php

C'est une classe qui nous aidera à faire nos requêtes PDO, on aura l'occasion de la voir plus tard.

### helpers.php

Les helpers sont des fonctions utilitaires qui nous serviront dans l'ensemble du projet, par exemple des fonctions qui permettent de générer des URL, ou bien la fonction `view` qui nous servira à afficher nos vues. Vous pouvez stocker ici toutes les fonctions qui peuvent vous aider plus tard.

## 4. Créer une première route

Les routes sont stockées dans le fichier `routes.php`, voici comment elles sont construites avec `bramus/router` :

```php

$router = new Router(); // On instancie le routeur

// On définit nos routes :
$router->get('/home', 'PagesController@home');
$router->get('/articles', 'ArticlesController@index');
$router->get('/articles/{id}', 'ArticlesController@show');
$router->post('/articles', 'ArticlesController@save');

$router->run(); // On lance le routeur
```

On prend la méthode `->get()` ou `->post()` en fonction de la méthode HTTP souhaitée (`get` ou `post` par exemple). Concrètement, s'il s'agit d'un lien classique accessible via la barre d'URL du navigateur, ça sera `GET`. Si c'est l'action d'un formulaire, ça sera `POST`.

Ensuite, on indique le pattern ou modèle d'URL à écouter : le routeur va écouter ce qui se passe dans l'URL (quand l'utilisateur clique sur un lien par exemple) et va essayer de trouver le pattern correspondant :

Par exemple : si le client va sur l'url `localhost/project/articles`, le routeur va rediriger vers le contrôleur `ArticlesController`, et la méthode `index` dans ce contrôleur.

### 5. Les contrôleurs

> ** Note importante :** Les contrôleurs prennent toujours le nom de la table au pluriel, et sont nommés en PascalCase. **Le fichier ET la classe doivent avoir RIGOUREUSEMENT le même nom.**

> Par exemple, pour les actions relatives aux articles (table `Article`), on aura un contrôleur `ArticlesController`, dans le fichier `ArticlesController.php`.

> Les controllers sont stockés dans `/src/controller/`.

> ** Note importante : ** Dans quel controller mettre les méthodes pour les pages qui ne sont pas relatives à une table ? Les pages `À propos`, `FAQ`, `Accueil`, `Contact` par exemple ? Vous pouvez les mettre dans un controller à part, nommé `PagesController` par exemple.

> Bien sûr, veillez à ne pas avoir de table nommée `Page` en base de données ;)

Le contrôleur est en fait une classe, contenant plusieurs méthodes publiques. Pour le moment, on retiendra d'une classe que c'est un containeur qui possède des fonctions (les `méthodes`) et des variables (les `attributs`) qui lui sont propres.

Cela nous permet d'organiser notre code en modules : par exemple, `ArticlesController` contiendra toutes les méthodes relatives aux articles.


```php
class ArticlesController {

    public function index() {

        // Daprès la route que l'on a défini plus haut,
        // Quand l'utilisateur va sur `localhost/articles`,
        // Il atterrit dans cette méthode index() !

        echo "Voici la liste des articles : ";

    }

    public function show ($id) {

        // Si on a précisé une variable dans la route, comme dans la route
        // $router->get('/articles/{id}', 'ArticlesController@show'),
        // on peut y accéder ainsi:
        // 1. L'utilisateur va sur la route : localhost/articles/34
        // 2. On récupère la variable dans la méthode (ici, c'est $id).

        echo "Voici l'article # " . $id;
    }
}

```