<!--<nav class="navbar navbar-light bg-light" style="height: 54px; box-shadow: 1px 0 10px rgb(156, 156, 156);">-->
<nav class="navbar navbar-light bg-light" style="height: 54px">
    <div class="container-fluid">
        <div class="justify-content-start">
            <a class="navbar-brand" href="<?php echo($filePath); ?>">
                <img src="<?php echo($filePath); ?>files/images/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                BEWÄSY
            </a>
        </div>

        <?php
            if (isset($_SESSION["userid"])) {
                echo('
                <div class="dropdown">
                    <button class="btn dropdown-toggle shadow-none" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            '.$_SESSION["userEmail"].'
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item" href="'.$filePath.'dashboard">Dashboard</a></li>
                        <li><a class="dropdown-item" href="#">Einstellungen</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="'.$filePath.'logout">Logout</a></li>
                    </ul>
                </div>
                ');
            } else {
                echo('
                    <form class="justify-content-end">
                        <a href="'.$filePath.'login"><button class="btn btn-outline-secondary me-2" type="button">Login</button></a>
                        <a href="'.$filePath.'login/sign-up"><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#registrationModal">Registrieren</button></a>
                    </form>
                ');
            }
        ?>

        <!--<div class="dropdown">
            <button class="btn dropdown-toggle shadow-none" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <?php
                    //echo($_SESSION["userEmail"]);
                ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                <li><a class="dropdown-item" href="<?php //echo($filePath); ?>dashboard">Dashboard</a></li>
                <li><a class="dropdown-item" href="#">Einstellungen</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?php //echo($filePath); ?>logout">Logout</a></li>
            </ul>
        </div>-->


        <!--<form class="justify-content-end">
                    <a href="<?php //echo($filePath); ?>login"><button class="btn btn-outline-secondary me-2" type="button">Login</button></a>
                    <a href="<?php //echo($filePath); ?>login/sign-up"><button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#registrationModal">Registrieren</button></a>
        </form>-->
    </div>
</nav>
