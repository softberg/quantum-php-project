<!-- HTML for static distribution bundle build -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quantum API doc</title>
    <link rel="stylesheet" type="text/css" href="<?php echo asset()->url('SwaggerUI/swagger-ui.css') ?>"/>
    <link rel="stylesheet" type="text/css" href="<?php echo asset()->url('SwaggerUI/index.css') ?>"/>
    <link rel="icon" type="image/png" href="<?php echo asset()->url('images/favicon.ico') ?>"/>
</head>

<body>
    <div id="swagger-ui"></div>

    <script src="<?php echo asset()->url('SwaggerUI/swagger-ui-bundle.js') ?>" charset="UTF-8"></script>
    <script src="<?php echo asset()->url('SwaggerUI/swagger-ui-standalone-preset.js') ?>" charset="UTF-8"></script>
    <script src="<?php echo asset()->url('SwaggerUI/swagger-initializer.js') ?>" charset="UTF-8"></script>
</body>

</html>