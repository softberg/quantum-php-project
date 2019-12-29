<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Quantum Project</title>

        <link href="https://fonts.googleapis.com/css?family=Poppins:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link rel="shortcut icon" href="<?php echo base_url() ?>/assets/images/favicon.ico">
        <link rel='stylesheet' href='<?php echo base_url() ?>/assets/css/style.css' type='text/css' media='all' />
        <link rel='stylesheet' href='<?php echo base_url() ?>/assets/css/style-custom-color-vyce.css' type='text/css' media='all' />
    </head>

    <body>
        <?php if (auth()->check()): ?>
            <?php render_partial('partials/navbar') ?>
        <?php endif; ?>
        <?php echo view() ?>

        <script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/assets/js/plugins.js'></script>
        <script type='text/javascript' src='<?php echo base_url() ?>/assets/js/app.js'></script>
    </body>
</html>