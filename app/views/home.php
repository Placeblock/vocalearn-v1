<head>
        
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/home.css">
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Home</title>

</head>

<body>
    
            <?php
                if(isset($_SESSION["user_id"])) {
                    include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/views/home_loggedin.php";
                }else {
                    include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/views/home_loggedout.php";
                }
            ?>
</body>