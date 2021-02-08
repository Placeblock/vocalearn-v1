<head>

    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Profil</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/profilestyle.css">

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>



<div class="container bg-light border border-primary text-dark">
    <div class="media">
    <i class="fas fa-user fa-3x" style="margin-right: 10px;"></i>
        <div class="media-body">
            <h2><?=$data["profile_name"]?></h2>
            <br>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="profil-tab" data-toggle="tab" href="#profil" role="tab" aria-controls="profil" aria-selected="true">Profil</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Einstellungen</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent" style="margin-top: 15px;">
                <div class="tab-pane fade show active" id="profil" role="tabpanel" aria-labelledby="profil-tab">Profil</div>
                <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                    <a type="button" class="btn btn-secondary btn-lg btn-block text-white" href='<?=$GLOBALS["BASE_PATH"]?>/user/reset'>Passwort zurücksetzen</a>
                </div>
            </div>
        </div>
    </div>
    
</div>