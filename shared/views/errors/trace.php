<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Error On Page</title>

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="shortcut icon" href='/assets/images/favicon.ico'/>
    <link rel='stylesheet' href='/assets/css/materialize.min.css' type='text/css' media='screen,projection'/>
    <link rel='stylesheet' href='/assets/css/custom.css' type='text/css'/>
    <link rel='stylesheet' href='/assets/css/trace.css' type='text/css'/>
</head>

<body>

<main class="grey lighten">
    <div class="container">
        <div class="row center-align text-darken-1">
            <div class="material-alert error"><strong><?php echo $severity . ':</strong> &nbsp;' . $errorMessage; ?></div>
            <ul class="collapsible">
                <?php $stackTrace = view_param('stackTrace'); ?>

                <?php foreach ($stackTrace as $index => $trace): ?>
                    <li <?php echo(!$index ? 'class="active"' : '') ?>>
                        <div class="collapsible-header">
                            <i class="material-icons">bug_report</i><?php echo $trace['file'] ?>
                        </div>
                        <div class="collapsible-body">
                            <div class="d-flex"><?php echo $trace['code'] ?></div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var elems = document.querySelectorAll('.collapsible');
        M.Collapsible.init(elems);
    });
</script>
<script type='text/javascript' src='/assets/js/materialize.min.js'></script>
</body>
</html>