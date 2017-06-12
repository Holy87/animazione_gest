<?php
require_once ABS_PATH.'/application/views/MainView.php';
require_once ABS_PATH.'/application/models/Item.php';
require_once ABS_PATH.'/application/models/PartyTheme.php';
require_once ABS_PATH.'/application/models/Party.php';
require_once ABS_PATH.'/application/models/User.php';

$user = User::getCurrent();
?>
<!DOCTYPE html>
<!--suppress ALL -->
<head>
    <title><?php MainView::page_title(); ?></title>
    <meta charset="utf-8">
    <meta name="description" content="">
    <meta name="author" content="<?php echo AUTHOR ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/styles/dataTables.bootstrap4.min.css">
    <!-- Stile per Bootstrap Select -->
    <!--<link rel="stylesheet" type="text/css" href="assets/styles/bootstrap-select.min.css">-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />

    <link rel="stylesheet" type="text/css" href="assets/styles/style.css">

    <!-- FAVICON -->
    <link rel="apple-touch-icon" sizes="57x57" href="assets/favicon/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="assets/favicon/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="assets/favicon/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="assets/favicon/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="assets/favicon/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="assets/favicon/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="assets/favicon/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="assets/favicon/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="assets/favicon/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192"  href="assets/favicon/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="assets/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/favicon/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/favicon/favicon-16x16.png">
    <link rel="manifest" href="assets/favicon/manifest.json">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="assets/favicon/ms-icon-144x144.png">
    <meta name="theme-color" content="#ffffff">
    <?php MainView::getCustomCss(); ?>
</head>

<body>

<!-- Fixed navbar -->
<?php require_once  ABS_PATH.'/application/views/templates/navbar.php'; ?>

<!-- MAIN DYNAMIC CONTNET -->
<?php load_page($user) ?>

<!-- FOOTER -->
<?php require_once  ABS_PATH.'/application/views/templates/footer.php'; ?>

<!-- MODAL ERRORE -->
<?php require_once ABS_PATH.'/application/views/templates/error_modal.php' ?>


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="assets/scripts/jquery-3.2.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
<script type="text/javascript" src="assets/scripts/dataTables/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="assets/scripts/dataTables/dataTables.bootstrap4.min.js"></script>
<!-- Javascript per selettore -->
<!--<script type="text/javascript" src="assets/scripts/bootstrap-select.min.js"></script>
<script type="text/javascript" src="assets/scripts/defaults-it_IT.min.js"></script>-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>
<script type="text/javascript" src="assets/scripts/main.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<!--<script src="assets/scripts/ie10-viewport-bug-workaround.js"></script>-->
<svg xmlns="http://www.w3.org/2000/svg" width="500" height="500" viewBox="0 0 500 500" preserveAspectRatio="none" style="display: none; visibility: hidden; position: absolute; top: -100%; left: -100%;"><defs><style type="text/css"></style></defs><text x="0" y="25" style="font-weight:bold;font-size:25pt;font-family:Arial, Helvetica, Open Sans, sans-serif">500x500</text></svg>
<?php MainView::getCustomJs();
MainView::get_scripts() ?>
</body>