<head>
    
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Login</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/loginstyle.css">

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

<div class="container bg-light border border-primary text-dark" style="">
    <form action="<?=$GLOBALS["BASE_PATH"]?>/user/checklogin" method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Email address</label>
        <input type="email" class="form-control form-control-lg" id="lemail" name="lemail" aria-describedby="emailHelp" placeholder="Enter email">
    </div>
    <div class="form-group">
        <label for="exampleInputPassword1">Passwort</label>
        <input type="password" class="form-control" id="lpswd" name="lpswd" placeholder="Passwort">
    </div>
    <div class="form-check">
        <input type="checkbox" class="form-check-input" id="staylogged" name="staylogged">
        <label class="form-check-label" for="exampleCheck1">Eingeloggt bleiben</label>
    </div>
    <h6 style="color: red;"><?=$data["status"]?></h6>
    <br>
    <h6><a href="<?=$GLOBALS["BASE_PATH"]?>/user/reset">Passwort vergessen?</a></h6>
    <input type="submit" class="btn btn-primary" value="Login"></input>
    </form>
</div>
