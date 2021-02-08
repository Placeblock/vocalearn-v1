
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <a class="navbar-brand" href="<?=$GLOBALS["BASE_PATH"]?>/trainer"><i class="fas fa-chalkboard"></i> <?=$GLOBALS["APPLICATION_NAME"]?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
        </ul>
        <?php  
            echo("<div class='btn-group' role='group'>
                    <a href='".$GLOBALS['BASE_PATH']."/user/login' class='btn btn-outline-light my-2 my-sm-0'>Login</a>
                    <a href='".$GLOBALS['BASE_PATH']."/user/register' class='btn btn-outline-light my-2 my-sm-0'>Register</a>
                </div>");
        ?>
    </nav>


    <div class="jumbotron container-fluid" style="margin-bottom: 0px; background-color: transparent;">
        <h1 class="display-4">Was ist <?=$GLOBALS["APPLICATION_NAME"]?>?</h1>
        <p class="lead">Mit <?=$GLOBALS["APPLICATION_NAME"]?> kannst du einfach effizient Vokabeln üben.</p>
        <hr class="my-4">
        <p>Du willst anfangen zu lernen? Dann starte jetzt!</p>
        <p class="lead">
            <?php
                echo("<a class='nav-link' href=<a class='btn btn-primary btn-lg' href='".$GLOBALS['BASE_PATH']."/user/login'>Lernen</a>");
            ?>
        </p>
    </div>

    <div class="show-case">
        <div class="image">
            <img src="<?=$GLOBALS['BASE_PATH']?>/images/howtowebsite.png" style="width: 100%;"></img>
        </div>
        <div class="content">
            <h5>Wie funktionierts? So!</h5>
        </div>
    </div>

    <div class="jumbotron container-fluid" style="text-align: right; margin-bottom: 0px; border-top: 2px solid grey; border-radius: 0px; background-color: transparent;">
        <h1 class="display-4">Was kann <?=$GLOBALS["APPLICATION_NAME"]?>?</h1>
        <p class="lead">
            Mit <?=$GLOBALS["APPLICATION_NAME"]?> kannst du deine eigenen Lektionen erstellen, mit anderen teilen, 
            aber auch Lektionen von anderen üben. Was <?=$GLOBALS["APPLICATION_NAME"]?> besonders macht, ist, dass es dich 
            gezielt die Wörter abfragen kann, die du üben müsstest. Und noch viel mehr.
        </p>
        <hr class="my-4">
        <p class="lead">
            <?php
                echo("<a class='nav-link' href=<a class='btn btn-primary btn-lg' href='".$GLOBALS['BASE_PATH']."/user/login'>Erstellen</a>");
            ?>
        </p>
    </div>


    
    <div class="show-case">
        <div class="image">
            <img src="<?=$GLOBALS['BASE_PATH']?>/images/website_mobile.png" style="width: 100%;"></img>
        </div>
        <div class="content">
            <h5>Ganz wichtig war uns das layout. Deshalb ist die Webseite für unterschiedliche Geräte optimiert.</h5>
        </div>
    </div>


    <img src="<?=$GLOBALS['BASE_PATH']?>/images/website_header_background.png" style="position: relative; z-index: -1; width: 100%; float: bottom;"></img>

