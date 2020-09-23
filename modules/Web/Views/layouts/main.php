<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?php echo $title ?></title>

        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="shortcut icon" href="<?php echo base_url() ?>/assets/images/favicon.ico">
        <link rel='stylesheet' href='<?php echo asset('css/materialize.min.css') ?>' type='text/css' media='screen,projection' />
        <link rel='stylesheet' href='<?php echo asset('css/custom.css') ?>' type='text/css' />
    </head>

    <body>

        <header>
            <?php if (auth()->check()): ?>
                <?php echo partial('partials/navbar') ?>
            <?php endif; ?>
        </header>

        <?php if(!auth()->check()): ?>
            <header class="teal accent-4 high-z">
                <div class="container wrapper">
                    <nav class="transparent no-box">
                        <div class="nav-wrapper teal accent-4 row">
                            <?php echo partial('partials/language') ?>
                        </div>
                    </nav>
                </div>
            </header>
        <?php endif; ?>
        
        <main>
            <?php echo view() ?>
        </main>

        <footer class="page-footer">
            <?php echo partial('partials/footer') ?>
        </footer>

        <?php echo debugbar() ?>

        <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
        <script type='text/javascript' src='<?php echo asset('js/materialize.min.js') ?>'></script>
        <script type='text/javascript' src='<?php echo asset('js/custom.js') ?>'></script>
    </body>
</html>