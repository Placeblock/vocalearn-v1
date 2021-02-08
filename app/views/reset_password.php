<head>

    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Reset</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/loginstyle.css">

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

<div class="container bg-light border border-primary text-dark" style="">
    <form action="<?=$GLOBALS["BASE_PATH"]?>/user/set_new_password" method="post">
        <div class="form-group">
            <label for="exampleInputEmail1">Neues Passwort:</label>
            <input type="password" class="form-control form-control-lg" id="rpswd" name="rpswd" aria-describedby="pswdHelp" placeholder="Neues Passwort:">
        </div>
        <p style="color: red;"><?=$data["status"]?></p>
        <br>
        <input type="submit" class="btn btn-primary" value="Reset"></input>
    </form>
</div>
