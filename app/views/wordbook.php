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
                <span class="input-group-text text-light bg-primary" id="search-input-text">Vokabel</span>
            </div>
            <input type="text" class="form-control" placeholder="Vokabel" aria-label="Lektionsname" aria-describedby="search-input-text" id="search-input">
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

        function update_list(query) {
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/search_vokabulary",
            {
                search_vok: query
            },
            function(data){
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

                    cardbody = document.createElement("div");
                    cardbody.classList.add("card-body");
                    cardbody.style.display = "flex";
                    cardbody.style.backgroundColor = "#404040";
                    cardbody.style.color = "white";
                    cardbody.style.borderRadius = "5px";
                    cardbody.style.justifyContent = "space-between";


                    firstfeld = document.createElement("div");
                    firstfeld.style.display = "flex";
                    firstfeld.style.flexDirection = "column";
                    firsttext = "<p style='font-size:20px;'>" + response[id][0] + "</p>"
                    firstinfo = "<p>" + response[id][1] + "</p>"
                    if(response[id][1] != "") {
                        firstfeld.innerHTML = firsttext + firstinfo
                    }else {
                        firstfeld.innerHTML = firsttext
                    }

                    secondfeld = document.createElement("div");
                    secondfeld.style.display = "flex";
                    secondfeld.style.flexDirection = "column";
                    secondtext = "<p style='font-size:20px;'>" + response[id][2] + "</p>"
                    secondinfo = "<p>" + response[id][3] + "</p>"
                    if(response[id][1] != "") {
                        secondfeld.innerHTML = secondtext + secondinfo
                    }else {
                        secondfeld.innerHTML = secondtext
                    }

                    cardbody.appendChild(firstfeld);
                    cardbody.appendChild(secondfeld);

                    card.appendChild(cardbody);

                    resultcontainer.appendChild(card);

                }
                
            });
        }
    </script>
</body>