<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title ?></title>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="shortcut icon" href="<?php echo asset()->url('images/favicon.ico') ?>">
        <link rel='stylesheet' href='<?php echo asset()->url('css/materialize.min.css') ?>' type='text/css' media='screen,projection' />
        <link rel='stylesheet' href='<?php echo asset()->url('css/custom.css') ?>' type='text/css' />
    </head>
    <body>
        <header><?php echo partial('partials/navbar') ?></header>
        
        <main><?php echo view() ?></main>
        
        <footer class="page-footer"><?php echo partial('partials/footer') ?></footer>

        <?php echo debugbar() ?>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type='text/javascript' src='<?php echo asset()->url('js/materialize.min.js') ?>'></script>
        <script type='text/javascript' src='<?php echo asset()->url('js/custom.js') ?>'></script>
    </body>
</html>