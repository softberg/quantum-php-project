<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quantum API doc</title>
    <link rel="stylesheet" type="text/css" href="<?php echo asset()->url('OpenApiUI/swagger-ui.css') ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset()->url('OpenApiUI/index.css') ?>"/>
    <link rel="icon" type="image/png" href="<?php echo asset()->url('images/favicon.ico') ?>"/>
    <link rel="stylesheet" href="<?php echo asset()->url('OpenApiUI/theme-material.css') ?>" />
</head>

<body>
    <div id="swagger-ui"></div>

    <script src="<?php echo asset()->url('OpenApiUI/swagger-ui-bundle.js') ?>" charset="UTF-8"></script>
    <script src="<?php echo asset()->url('OpenApiUI/swagger-ui-standalone-preset.js') ?>" charset="UTF-8"></script>
    <script src="<?php echo asset()->url('OpenApiUI/swagger-initializer.js') ?>" charset="UTF-8"></script>
</body>

</html>