<head>

    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Register</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/loginstyle.css">

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

<div class="container bg-light border border-primary text-dark">
    <form action="<?=$GLOBALS["BASE_PATH"]?>/user/checkregister" method="post">
    <div class="form-group">
        <label for="rname">Name :</label>
        <input type="name"  class="form-control form-control-lg" id="rname" name="rname" placeholder="Name" maxlength="50"></input>
    </div>
    <div class="form-group">
        <label for="remail">Email :</label>
        <input type="email"  class="form-control form-control-lg" id="remail" name="remail" aria-describedby="emailHelp" placeholder="Email" maxlength="50"></input>
        <small id="emailHelp" class="form-text text-muted">Wir werden deine Email niemals mit irgendjemandem teilen.</small>
    </div>
    <div class="form-group">
        <label for="rpswd">Passwort :</label>
        <input type="password" class="form-control form-control-lg" id="rpswd" name="rpswd" aria-describedby="pswdHelp" placeholder="Passwort" maxlength="50"  id="rpswd"></input>
        <small id="pswdHelp" class="form-text text-muted">Dein Passwort wird verschlüsselt, und so mit unlesbar gespeichert.</small>
    </div>
    <p style="color: red;"><?=$data["status"]?></p>
    <br>
    <input type="submit" class="btn btn-primary" value="Registrieren"></input>
    </form>
</div>