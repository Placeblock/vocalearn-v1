
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="<?=$GLOBALS["BASE_PATH"]?>/trainer"><i class="fas fa-chalkboard"></i> <?=$GLOBALS["APPLICATION_NAME"]?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
    
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
                <?php  
                    echo("<a class='nav-link text-white' href='".$GLOBALS['BASE_PATH']."/trainer/create_lection'> Lektion Erstellen</a>");
                ?>
            </li>
            <li class="nav-item">
                <?php  
                    echo("<a class='nav-link text-white' href='".$GLOBALS['BASE_PATH']."/trainer/search_lection'>Lektionen Suchen</a>");
                ?>
            </li>
            <li class="nav-item">
                <?php  
                    echo("<a class='nav-link text-white' href='".$GLOBALS['BASE_PATH']."/trainer/user_stats'>Statistiken</a>");
                ?>
            </li>
            <li class="nav-item">
                <?php  
                    echo("<a class='nav-link text-white' href='".$GLOBALS['BASE_PATH']."/trainer/woerterbuch'>Wörterbuch</a>");
                ?>
            </li>
        </ul>
        <?php
            if(!isset($data["profilename"])) {
                $data["profilename"] = "Unknown Name";
            }
            echo("<li class='nav-item dropdown' style='list-style-type: none;'>
                    <a class='nav-link dropdown-toggle text-white' href='#' id='navbardrop' data-toggle='dropdown'>
                        <i class='fas fa-user' style='margin-right: 10px;'></i>".$data["profilename"]."
                    </a>
                    <div class='dropdown-menu dropdown-menu-right'>
                        <a class='dropdown-item' href='".$GLOBALS['BASE_PATH']."/user/profile'>Profil</a>
                        <div class='dropdown-divider'></div>
                        <a class='dropdown-item' href='".$GLOBALS['BASE_PATH']."/user/logout'>Abmelden</a>
                    </div>
                </li>");
        ?>
    </nav>

    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: absolute; bottom: 0; right: 0; margin-right: 10px;">
        <div class="toast-header">
            <strong class="mr-auto text-danger">Antwort:</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body text-da">
            Fehlermeldung
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Nur noch ein paar einstellungen ;)</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="<?=$GLOBALS["BASE_PATH"]?>/trainer/learn" method="get">
                        <input type="hidden" id="form_selection_name" name="selection_name" value="Unknown Name">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Anzahl der Vokabeln:</label>
                            <select class="form-control" id="exampleFormControlSelect1" name="max_vocs">
                                <option>5</option>
                                <option>10</option>
                                <option>20</option>
                                <option>30</option>
                                <option>50</option>
                                <option>70</option>
                                <option>100</option>
                                <option>1000</option>
                            </select>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="direction" id="exampleRadios1" value="1">
                            <label class="form-check-label" for="exampleRadios1">
                                Deutsch / Fremdsprache
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="direction" id="exampleRadios2" value="0" checked>
                            <label class="form-check-label" for="exampleRadios2">
                                Fremdsprache / Deutsch
                            </label>
                        </div>
                        <br>
                        <button type="submit" class="btn btn-default btn-primary">Los Gehts!</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="delete_selection_modal" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel2">Achtung</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Möchtest du wirklich die Lernliste <span id="delete_selection_name">Unknown Name</span> löschen?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger" id="sure_delete_selection">Löschen</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="delete_lesson_modal" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel3">Achtung</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Möchtest du wirklich die Lektion <span id="delete_lesson_name">Unknown Name</span> löschen?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Abbrechen</button>
                    <button type="button" class="btn btn-danger" id="sure_delete_lesson">Löschen</button>
                </div>
            </div>
        </div>
    </div>



    <div id="selections-container">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="Selections-tab" data-toggle="tab" href="#Selections" role="tab" aria-controls="Selections" aria-selected="true">Deine Lernlisten</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="Lektionen-tab" data-toggle="tab" href="#Lektionen" role="tab" aria-controls="Lektionen" aria-selected="false">Deine Lektionen</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent" style="margin-top: 15px; width: 100%;">
            <div class="tab-pane fade show active" id="Selections" role="tabpanel" aria-labelledby="Selections-tab" style="text-align: center;">
                <?php
                    if(isset($data["selections"])) {
                        if(!count($data["selections"]) > 0) {
                            echo "<h5>Du hast noch keine Lernlisten. Im 'suchen' Tab kannst du deine wundervollen Lektionen ganz einfach einer Lernliste hinzufügen</h5>";
                        }
                        if(is_array($data["selections"])) {
                            foreach($data["selections"] as $key=>$value) {
                                echo("<div class='card border-primary mb-3 w-100' card_selection='".$key."'>
                                        <div class='card-header'>
                                            <span class='h4'>".$key."</span>
                                            <div class='float-md-right' style='max-width: 100px; float: right;'>
                                                <button class='btn btn-primary delete_selection' data='button' selection_name='".$key."'><i class='far fa-trash-alt delete_selection' selection_name='".$key."'></i></button>
                                                <button class='btn btn-primary selection_name' data='button' selection_name='".$key."'><i class='far fa-play-circle selection_name' selection_name='".$key."'></i></button>
                                            </div>
                                        </div>
                                    </div>");
                            }
                        }
                    }
                ?>
            
            </div>
            <div class="tab-pane fade" id="Lektionen" role="tabpanel" aria-labelledby="Lektionen-tab" style="text-align: center;">
                <?php
                    if(isset($data["lessons"])) {
                        if(!count($data["lessons"]) > 0) {
                            echo "<h5>Du hast noch keine Lektionen. Wenn du im 'erstellen' Tab dir schnell welche erstellst kannst du gleich mit dem Lernen beginnen!</h5>";
                        }
                        if(is_array($data["lessons"])) {
                            foreach($data["lessons"] as $key=>$value) {
                                echo("<div class='card border-primary mb-3 w-100' card_lesson='".$key."'>
                                        <div class='card-header'>
                                            <span class='h4'>".$value."</span>
                                            <div class='float-md-right' style='max-width: 100px; float: right;'>
                                                <button class='btn btn-primary delete_lesson' data='button' lesson_id='".$key."' lesson_name='".$value."'><i class='far fa-trash-alt delete_lesson' lesson_id='".$key."' lesson_name='".$value."'></i></button>
                                            </div>
                                        </div>
                                    </div>");
                            }
                        }
                    }
                ?>

            </div>
        </div>
    </div>


    <img src="<?=$GLOBALS['BASE_PATH']?>/images/website_header_background.png" style="position: absolute; z-index: -1; width: 100%; bottom:0;"></img>



    <script>
        var selected_selection = false
        var selected_lesson_id = false
        var selected_lesson_name = false
        var user_id = <?=$_SESSION["user_id"]?>;

        $('.selection_name').click(function(event) {

            selected_selection = $(event.target).attr("selection_name");

            console.log(selected_selection);
            document.getElementById("form_selection_name").value = selected_selection;

            
            $('#exampleModal').modal('toggle');

        });

        $('.delete_selection').click(function(event) {

            selected_selection = $(event.target).attr("selection_name");

            $("#delete_selection_name").html(selected_selection);

            
            $('#delete_selection_modal').modal('toggle');
        });

        $('#sure_delete_selection').click(function(event) {
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/delete_selection",
                {
                    user_id: user_id,
                    selection_name: selected_selection
                },
                function(data){
                    $("#delete_selection_modal").modal('hide');

                    $(".toast-body").html(data);
                    $(".toast").toast({ delay: 5000 });
                    $(".toast").toast('show');

                    if(data.includes("erfolgreich")) {
                        $('div[card_selection='+selected_selection+']').remove();
                    }
            });
        });

        
        $('.delete_lesson').click(function(event) {
            selected_lesson_id = $(event.target).attr("lesson_id");
            selected_lesson_name = $(event.target).attr("lesson_name");

            $("#delete_lesson_name").html(selected_lesson_name);


            $('#delete_lesson_modal').modal('toggle');
        });

        $('#sure_delete_lesson').click(function(event) {
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/delete_lesson",
                {
                    lesson_id: selected_lesson_id,
                },
                function(data){
                    $("#delete_lesson_modal").modal('hide');

                    $(".toast-body").html(data);
                    $(".toast").toast({ delay: 5000 });
                    $(".toast").toast('show');

                    if(data.includes("erfolgreich")) {
                        $('div[card_lesson='+selected_lesson_id+']').remove();
                    }
            });
        });








        $('#exampleModal').on('shown.bs.modal', function () {
            $('#exampleModal').trigger('focus');
        })

    </script>
    

