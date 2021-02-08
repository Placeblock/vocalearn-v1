<head>
    
    
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Reset</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/loginstyle.css">

</head>

<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

<div class="container" style="background-color: rgb(42, 45, 49); color: white;">
    <form action="<?=$GLOBALS["BASE_PATH"]?>/user/send_reset_token" method="post">
    <div class="form-group">
        <label for="exampleInputEmail1">Deine Email:</label>
        <input type="email" class="form-control form-control-lg" id="remail" name="remail" placeholder="Deine Email:">
    </div>
    <p style="color: red;"><?=$data["status"]?></p>
    <br>
    <input type="submit" class="btn btn-primary" value="Password zurücksetzen"></input>
    </form>
</div>