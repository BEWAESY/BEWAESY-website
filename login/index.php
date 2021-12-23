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

        <h1 style="font-size: 25px;">Sign in to <b>BEWÄSY</b></h1>

        <form action="." method="POST" style="width: 90%">
            <div class="mb-2 mt-2">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input name="email" type="email" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="mb-2">
                <label for="exampleInputPassword1" class="form-label">Password</label>
                <input name="password" type="password" class="form-control" id="exampleInputPassword1" aria-describedby="passwordHelp">
                <div id="passwordHelp" class="form-text">Passwort vergessen? <a href=".">Hier Zurücksetzen</a></div>
            </div>
            <button type="submit" class="btn btn-primary" style="float: right;">Login</button>
        </form>

        <hr style="width: 90%;">

        <p>Noch kein Account? <a href=".">Registrieren</a></p>
    </div>

    

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>