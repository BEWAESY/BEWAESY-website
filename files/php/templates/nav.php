<nav class="navbar navbar-light bg-light">
    <div class="container-fluid">
        <div class="justify-content-start">
            <a class="navbar-brand" href="#">
                <img src="<?php echo($filePath); ?>files/images/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                BEWÄSY
            </a>
        </div>

        <form class="justify-content-end">
                    <a href="<?php echo($filePath); ?>login"><button class="btn btn-outline-secondary me-2" type="button">Login</button></a>
                    <a href="<?php echo($filePath); ?>login/sign-up"><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#registrationModal">Registrieren</button></a>
        </form>
    </div>
</nav>
