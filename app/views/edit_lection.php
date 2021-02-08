<head>
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/create_lection.css">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Create</title>
</head>

<body>

    
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>

    <div id="container">
        <div id="vocabulary_list">
            <h3><?=$data["lectionname"]?></h3>
            <div id="controlls-container" class="border border-dark rounded bg-dark">
                <button onclick="create_vok()" class="btn btn-primary">
                    <i class="far fa-plus-square fa-2x"></i>
                </button>
                <button onclick="save_voc()" class="btn btn-primary">
                    <i class="far fa-save fa-2x"></i>
                </button>
            </div>
        </div>
    </div>

    


    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 0; right: 0; margin-right: 10px;">
        <div class="toast-header">
            <strong class="mr-autotoast-header">Fehler!</strong>
            <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            Fehlermeldung
        </div>
    </div>


    <script>
        var voc_element = document.getElementById("vocabulary_list");
        var lection_id = <?=$data["lection_id"]?>;
        var language = <?php echo "'".$data["language"]."'" ?>;

        load_lection();

        function create_vok(voc_id, n_text, n_info, f_text, f_info) {


            voc_container = document.createElement("div");
            voc_container.classList.add("voc_container", "bg-primary");
            if(voc_id !== undefined) {
                voc_container.id = voc_id;
            }

            function create_vok_field(text, info, language) {
                voc_field = document.createElement("div");
                voc_field.classList.add("voc_field")
                voc_field.style.display = "flex";
                voc_field.style.flexDirection = "column";

                voc_text = document.createElement("h5");
                voc_text.classList.add("text-white");
                voc_text.innerHTML = language;

                voc_input = document.createElement("input");
                voc_input.classList.add("form-control");
                voc_input.setAttribute("placeholder", "Bedeutung:");
                voc_input.setAttribute("multiple", "");
                
                voc_extra = document.createElement("input");
                voc_extra.classList.add("form-control");
                voc_extra.setAttribute("multiple", "");
                voc_extra.setAttribute("placeholder", "Extra Info:");

                if(text !== undefined) {
                    voc_input.value = text;
                }
                if(info !== undefined) {
                    voc_extra.value = info;
                }
    
                voc_field.appendChild(voc_text);
                voc_field.appendChild(voc_input);
                voc_field.appendChild(voc_extra);

                return voc_field;
            }

            voc_delete = document.createElement("div");
            voc_trashcan = document.createElement("i");
            voc_trashcan.classList.add("far","fa-trash-alt","fa-2x");
            voc_delete.appendChild(voc_trashcan);
            voc_delete.style.color = "white";
            voc_delete.classList.add("trashcan");

            voc_container.appendChild(create_vok_field(n_text, n_info, language));
            voc_container.appendChild(voc_delete);
            voc_container.appendChild(create_vok_field(f_text, f_info, "Deutsch"));

            voc_element.appendChild(voc_container);

            voc_trashcan.addEventListener("click", function(event){
                delete_voc(event.target.parentElement.parentElement);
            })
        }

        document.addEventListener("keydown", function(e) {
            if ((window.navigator.platform.match("Mac") ? e.metaKey : e.ctrlKey) && e.keyCode == 83) { 
                e.preventDefault();
                save_voc();
            } 
        }, false);

        function save_voc() {
            elements = document.querySelectorAll("div.voc_container");
            for(var i = 0; i < elements.length; i++) {
                ele = elements[i];
                if(is_filled(ele)) {
                    if(ele.hasAttribute('id')) {
                        vocs = ele.querySelectorAll("input");
                        voc_inputs =  [].map.call(vocs, el => el.value); 
                        update_vocabulary(ele.id, voc_inputs[0] , voc_inputs[1], voc_inputs[2], voc_inputs[3]);
                    }else {
                        
                        vocs = ele.querySelectorAll("input");
                        voc_inputs =  [].map.call(vocs, el => el.value);
                        create_vocabulary(ele, voc_inputs[0] , voc_inputs[1], voc_inputs[2], voc_inputs[3]);
                    }
                }
            }
        }

        function delete_voc(ele) {
            if(ele.hasAttribute('id')) {
                $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/delete_voc",
                {
                    voc_id: ele.id,
                    lection_id: lection_id
                },
                function(data){
                    $(".toast-body").html(data);
                    $(".toast-header").html("Antwort");
                    $(".toast").toast({ delay: 5000 });
                    $(".toast").toast('show');
                    if(data.includes("Erfolgreich")) {
                        console.log(ele.parentElement.removeChild(ele));
                    }
                });
            }else {
                console.log(ele.parentElement.removeChild(ele));
            }
        }

        function is_filled(ele) {
            vocs = ele.querySelectorAll("input");
            if(!vocs[0].value.length > 0) {
                return false;
            }
            if(!vocs[2].value.length > 0) {
                return false;
            }
            return true;
        }

        function update_vocabulary(voc_id, n_text, n_info, f_text, f_info) {
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/update_voc",
            {
                lection_id: lection_id,
                voc_id: voc_id,
                n_info: n_info,
                n_text: n_text,
                f_text: f_text,
                f_info: f_info
            },
            function(data){
                var response = jQuery.parseJSON(data);
                $(".toast-body").html(response.result);
                $(".toast-header").html("Antwort");
                $(".toast").toast({ delay: 5000 });
                $(".toast").toast('show');
            });
        }

        function create_vocabulary(elem, n_text, n_info, f_text, f_info) {
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/update_voc",
            {
                lection_id: lection_id,
                n_info: n_info,
                n_text: n_text,
                f_text: f_text,
                f_info: f_info
            },
            function(data){
                var response = JSON.parse(data);
                if(response.hasOwnProperty('id')) {
                    elem.id = response.id;
                }
                $(".toast-body").html(response.result);
                $(".toast-header").html("Antwort");
                $(".toast").toast({ delay: 5000 });
                $(".toast").toast('show');
            });
        }

        function load_lection() {
            console.log("loaded");
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/load_lection",
            {
                lection_id: lection_id
            },
            function(data){
                response = JSON.parse(data);
                $(".toast-body").html(response.result);
                $(".toast-header").html("Antwort");
                $(".toast").toast({ delay: 5000 });
                $(".toast").toast('show');
                for(id in response) {
                    if(id != "result") {
                        create_vok(id, response[id]["n_text"], response[id]["n_hints"], response[id]["f_text"],response[id]["f_hints"]);
                    }
                }
            });
        }
    </script>
</body>