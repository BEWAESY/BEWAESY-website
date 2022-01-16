<?php
    session_start();
    include "../files/php/config/sql.php";


    // Check if user is already logged in, and if yes, redirect
    if (isset($_SESSION["userid"])) {
        header("Location: ../dashboard");
        die();
    }


    $wrongCredentials = false;

    $email = @$_POST["email"];
    $password = @$_POST["password"];

    // Check if everything is there
    if (isset($email) && isset($password)) {
        // If email and password where sent, check them
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array("email" => $email));
        $user = $statement->fetch();

        // Check data
        if ($user !== false && password_verify($password, $user["password"])) {
            $_SESSION["userid"] = $user["id"];
            $_SESSION["userEmail"] = $user["email"];
            header("Location: ../dashboard");
            die();
        } else {
            $wrongCredentials = true;
        }
    }
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - BEWÄSY</title>
    <link rel="icon" type="image/x-icon" href="../files/images/logo.svg">

    <link href="../files/addons/bootstrap.min.css" rel="stylesheet">
    <link href="../files/css/login.css" rel="stylesheet">
</head>
<body>
    <div class="center">
        <div class="alert alert-warning alert-dismissible fade show" role="alert" <?php if ($wrongCredentials == false) echo("style='display: none;;'") ?>>
            E-Mail oder Passwort falsch
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>


        <div class="login-container">
            <div class="login-goback" onclick="location.href='..';">
                <img src="../files/images/logo.svg" class="login-logo">
            </div>

            <h1 style="font-size: 25px;">Login <b>BEWÄSY</b></h1>

            <form id="loginForm" class="needs-validation" action="." method="POST" style="width: 90%" novalidate>
                <div class="mb-2 mt-2">
                    <label for="userEmail" class="form-label">E-Mail</label>
                    <input name="email" type="email" class="form-control" id="userEmail" <?php echo(isset($email) ? "value='".$email."'" : "autofocus"); ?> required>
                    <div class="invalid-feedback">
                        Bitte gib eine gültige E-Mail ein
                    </div>
                </div>


                <div class="mb-2">
                    <label for="userPassword" class="form-label">Passwort</label>
                    <input name="password" type="password" class="form-control" id="userPassword" aria-describedby="passwordHelp" required <?php if (isset($email)) echo("autofocus"); ?>>
                    <div class="invalid-feedback">
                        Bitte gib ein Passwort ein
                    </div>
                    <div id="passwordHelp" class="form-text"><a href="password-reset">Passwort vergessen?</a></div>
                </div>
                <button id="loginSubmit" type="submit" class="btn btn-primary" style="float: right;">Login</button>
            </form>

            <hr style="width: 90%;">

            <p>Noch kein Account? <a href="sign-up">Registrieren</a></p>
        </div>
    </div>

    

    <script>
        activateValidation();

        function activateValidation() {
            var forms = document.querySelectorAll(".needs-validation");

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                        form.classList.add('was-validated')
                    } else {
                        setTimeout(sendLogin, 1);
                    }

                    //form.classList.add('was-validated')
                }, false)
            })
        }

        function sendLogin() {
            $("#loginForm").removeClass("was-validated");

            // Disable form
            $("#userEmail, #userPassword, #loginSubmit").prop("disabled", true);
        }
    </script>
    <script src="../files/addons/jquery-3.6.0.min.js"></script>
    <script src="../files/addons/bootstrap.bundle.min.js"></script>
</body>
</html>
