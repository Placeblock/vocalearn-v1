<head>

    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Create</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/loginstyle.css">

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

<div class="container bg-light border border-primary text-dark">
    <form action="<?=$GLOBALS["BASE_PATH"]?>/trainer/create_lection" method="post">
    <div class="form-group">
        <label for="lectionname">Name :</label>
        <input type="name"  class="form-control form-control-lg" id="lectionname" name="lectionname" placeholder="Name" maxlength="50"></input>
    </div>
    <div class="form-group">
      <label for="language">FremdSprache</label>
      <select id="language" name="language" class="form-control">
        <option value="Latein" selected>Latein</option>
        <option value="Englisch">Englisch</option>
        <option value="Spanisch">Spanisch</option>
        <option value="Französisch">Französisch</option>
        <option value="Andere">Andere</option>
      </select>
    </div>
    <p style="color: red;"><?=$data["create_status"]?></p>
    <p style="color: red;"><?=$data["create_status"]?></p>
    <br>
    <input type="submit" class="btn btn-primary" value="Erstellen"></input>
    </form>
</div>