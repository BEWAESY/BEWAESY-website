<?php
    session_start();
    include "../files/php/config/sql.php";

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
            header("Location: ../dashboard");
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
                <img src="../files/images/bootstrap-logo.svg" class="login-logo">
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
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
