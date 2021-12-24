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
    <div class="login-container">
        <div class="login-goback" onclick="location.href='..';">
            <img src="../files/images/bootstrap-logo.svg" class="login-logo">
        </div>

        <h1 style="font-size: 25px;">Login <b>BEWÄSY</b></h1>

        <form class="needs-validation" action="." method="POST" style="width: 90%" novalidate>
            <div class="mb-2 mt-2">
                <label for="userEmail" class="form-label">E-Mail</label>
                <input name="email" type="email" class="form-control" id="userEmail" autofocus required>
                <div class="invalid-feedback">
                    Bitte gib eine gültige E-Mail ein
                </div>
            </div>


            <div class="mb-2">
                <label for="userPassword" class="form-label">Passwort</label>
                <input name="password" type="password" class="form-control" id="userPassword" aria-describedby="passwordHelp" required>
                <div class="invalid-feedback">
                    Bitte gib ein Passwort ein
                </div>
                <div id="passwordHelp" class="form-text"><a href="#">Passwort vergessen?</a></div>
            </div>
            <button type="submit" class="btn btn-primary" style="float: right;" onclick="sendLogin()">Login</button>
        </form>

        <hr style="width: 90%;">

        <p>Noch kein Account? <a href="../sign-up">Registrieren</a></p>
    </div>

    

    <script>
        function sendLogin() {
            var forms = document.querySelectorAll(".needs-validation");

            // Loop over them and prevent submission
            Array.prototype.slice.call(forms).forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }

                    form.classList.add('was-validated')
                }, false)
            })
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>