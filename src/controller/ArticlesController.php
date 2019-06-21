<?php


class ArticlesController {

    public function add() {

        view('articles.add');
    }

    public function save() {

        dump($_POST['article']);

        // traitements + enregistrment en bdd...

        // Enfin, redirection vers page de l'article quand tout est ok
        // redirectTo('article');

    }

    public function show() {

        echo "Affichage de l'article";
    }
}