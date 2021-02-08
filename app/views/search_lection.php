<head>
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>

    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/search_lection.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Create</title>
</head>

<body>

    
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>





    <div id="search-container">
        <div class="input-group input-group-lg" style="margin-bottom: 10px;">
            <div class="input-group-prepend">
                <span class="input-group-text text-light bg-primary" id="search-input-text">Lektion Suchen</span>
            </div>
            <input type="text" class="form-control" placeholder="Lektionsname" aria-label="Lektionsname" aria-describedby="search-input-text" id="search-input">
        </div>
        <div id="result-container">

        </div>
    </div>

    

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
                    <h5 class="modal-title" id="exampleModalLabel">Wähle eine Lernliste</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul class="list-group" id="user-selection-list">
                        <?php
                            $selections = $data["selections"];
                            if($selections) {
                                foreach($data["selections"] as $value) {
                                    echo "<button type='button' class='list-group-item list-group-item-action' onclick='add_to_list(\"".$value."\")'>".$value."</button>";
                                }
                            }

                        ?>
                    </ul>
                </div>
                <div class="modal-footer">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Lernliste Name" aria-label="Selection Name" aria-describedby="button-addon2" id="new-selection-input" maxlength="25">
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary text-white bg-dark " type="button" id="button-addon2" onclick="create_selection()">Erstellen und einfügen</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
        var searchinput = document.getElementById("search-input");
        var resultcontainer = document.getElementById("result-container");
        var selected_lesson = false;
        
        
        

        searchinput.addEventListener("keyup", function(){
            if(searchinput.value.length >= 3) {
                update_list(searchinput.value);
            }else {
                resultcontainer.innerHTML = "";
            }
        })


        function create_selection() {
            name = $("#new-selection-input").val();
            if(name.length < 5) {
                $(".toast-body").html("Dein Name muss mindestens 5 Buchstaben lang sein!");
                $(".toast").toast({ delay: 5000 });
                $(".toast").toast('show');
                return;
            }
            console.log("clicked");
            add_to_list(name);
        }

        function update_list(query) {
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/get_lessons_by_name",
            {
                query: query
            },
            function(data){
                console.log(data);
                try {
                    response = JSON.parse(data);
                } catch (e) {
                    $(".toast-body").html(data);
                    $(".toast").toast({ delay: 5000 });
                    $(".toast").toast('show');
                }

                resultcontainer.innerHTML = "";

                for(id in response) {

                    card = document.createElement("div");
                    card.classList.add("card");
                    card.style.marginBottom = "20px";

                    cardheader = document.createElement("div");
                    cardheader.classList.add("card-header","bg-dark","text-light","h4");
                    cardheader.innerHTML = response[id]["name"];

                    cardbody = document.createElement("div");
                    cardbody.classList.add("card-body");

                    blockquote = document.createElement("blockquote");
                    blockquote.classList.add("blockquote","mb-0");

                    content = document.createElement("p");
                    content.innerHTML = response[id]["language"];

                    footer = document.createElement("footer");
                    footer.classList.add("blockquote-footer");
                    footer.innerHTML = "Erstellt am : " + response[id]["creation_date"] + " von " + response[id]["owner_name"];

                    blockquote.appendChild(content);

                    blockquote.appendChild(footer);

                    cardbody.appendChild(blockquote);

                    button = document.createElement("button");
                    button.style.marginLeft = "10px";
                    
                    button_inner = document.createElement("i");
                    button_inner.classList.add("far","fa-plus-square");
                    button_inner.setAttribute("lection_id", id);
                    button.setAttribute("lection_id", id);
                    button.appendChild(button_inner);
                    button.classList.add("btn","btn-primary","float-md-right");
                    button.setAttribute("data", "button");
                    button.setAttribute("data-toggle","modal");
                    button.setAttribute("data-target","#exampleModal");

                    button.addEventListener("click", function(event){
                        console.log("clicked");
                        selected_lesson = event.target.getAttribute("lection_id");
                        console.log(selected_lesson);
                    })

                    cardheader.appendChild(button);

                    card.appendChild(cardheader);
                    card.appendChild(cardbody);

                    resultcontainer.appendChild(card);

                }
                
            });
        }

        
        $('#exampleModal').on('shown.bs.modal', function () {
            $('#exampleModal').trigger('focus');
        })

        function add_to_list(selection_name) {
            if(selected_lesson == false) {
                console.log("no lection selected");
                return;
            }
            result = $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/add_lesson_to_selection",
                {
                    lesson_id: selected_lesson,
                    selection_name: selection_name
                },
                function(data){
                    $("#exampleModal").modal('hide');

                    $(".toast-body").html(data);
                    $(".toast").toast({ delay: 5000 });
                    $(".toast").toast('show');
            });
        }
    </script>
</body>