<?php
//error_reporting(E_ALL);
//ini_set("display_errors", 1);
require_once 'include/autoload.php';
$debug = isset($_GET['debug']) ? true : false;
$Poll = new Polls(377, $debug);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <title>Опросник MMPI</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <script src="assets/js/jquery-3.2.1.slim.min.js"></script>
    <script src="assets/js/popper.min.js"></script>
    <script src="assets/js/bootstrap.min.js"></script>
</head>
<body>
<div class="container">
<form method="post" action="results.php">
    <input name="test" value="377" type="hidden">
    <div class="col-md-12 mb-5">
        <h4 class="mb-3"><?$Poll->showPollTitle();?></h4>

        <?$Poll->showContact();?>

        <?$Poll->showQuestions();?>

        <hr class="mb-4">
        <button class="btn btn-primary btn-lg btn-block" type="submit">Отправить</button>

    </div>
</form>
</div>
</body>
</html>