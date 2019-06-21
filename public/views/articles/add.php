<?php ob_start(); ?>

<form method="post" action="<?= url('ajouter-article') ?>">
    <input type="text" name="title" id="">
    <input type="text" name="content" id="">
    <input type="submit" value="Envoyer">
</form>

<?php $content = ob_get_clean() ?>
<?php view('template', compact('content')); ?>