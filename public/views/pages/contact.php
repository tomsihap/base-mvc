<?php ob_start(); ?>

<form method="post" action="<?= url('contactez-nous') ?>">
    <input type="text" placeholder="nom" name="nom" id="">
    <input type="text" placeholder="message" name="message" id="">
    <input type="submit" value="Envoyer">
</form>

<?php $content = ob_get_clean() ?> <?php view('template', compact('content')); ?>