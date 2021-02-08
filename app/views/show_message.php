<head>
    
    
    
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Message</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/loginstyle.css">

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

<div style="width: 100%; height: 100%; display: flex; justify-content: center; align-items: center;">
    <h4><?=$data["message"]?></h4>
</div>