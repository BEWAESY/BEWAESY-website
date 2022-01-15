<?php include "files/php/config/config.php" ?>
<?php session_start(); ?>

<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BEWÄSY</title>

    <link href="files/css/main.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
    <?php include "files/php/templates/nav.php" ?>

    <h1>LANDING PAGE</h1>

    <div class="footer-container">
        <footer class="my-4">
            <ul class="nav justify-content-center">
                <li class="nav-item"><a href="." class="nav-link px-2 text-muted">Home</a></li>
                <li class="nav-item"><a href="impressum.php" class="nav-link px-2 text-muted">Impressum</a></li>
                <li class="nav-item"><a href="datenschutz.php" class="nav-link px-2 text-muted">Datenschutzerklärung</a></li>
            </ul>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
