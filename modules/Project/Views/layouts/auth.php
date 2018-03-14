<!DOCTYPE html>
<html >
    <head>
        <meta charset="UTF-8">
        <title>Sign-Up/Login Form</title>
        <link href='http://fonts.googleapis.com/css?family=Titillium+Web:400,300,600' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?php echo base_url() ?>/assets/css/auth/normalize.css">
        <link rel="stylesheet" href="<?php echo base_url() ?>/assets/css/auth/style.css">

    </head>

    <body>
        <div class="form">
            <?php echo view() ?>
        </div>
        <script src='http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>
        <script src="<?php echo base_url() ?>/assets/js/auth/index.js"></script>

    </body>
</html>