<head>
    
    
    
    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Learn</title>
    <link rel="stylesheet" href="<?=$GLOBALS["BASE_PATH"]?>/css/learnstyle.css">

</head>





<?php 

include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";

?>

<div id="learn-container" class="border border-primary rounded" style="border-width:5px !important;">
    <div id="vocable-container" class="bg-dark rounded" style="border-width:5px !important;">
        <div id="vocable-front" class="bg-dark vocable-inner rounded">
            <button id="speech" class="btn btn-light"><i id="speech-i" class="fas fa-volume-up"></i></button>
            <p id="front-main-text" style="font-size: 2em; text-align: center; word-break: break-word !important;">Front Main</p>
            <p id="front-info-text" style="word-break: break-all !important;">Front Info</p>
        </div>
        <div id="vocable-back" class="bg-dark vocable-inner rounded">
            <button id="speech-back" class="btn btn-light"><i class="fas fa-volume-up"></i></button>
            <p id="back-main-text" style="font-size: 2em; text-align: center; word-break: break-word !important;">Back Main</p>
            <p id="back-info-text" style="word-break: break-all !important;">Back Info</p>
        </div>
    </div>
    <div id="tick-cross-container">
        <button type="button" class="btn btn-danger btn-lg tick-cross-button" id="button-false">Falsch</button>
        <button type="button" class="btn btn-success btn-lg tick-cross-button" id="button-right">Richtig</button>
    </div>
</div>



<script>
    var data = <?php echo json_encode($data["result"]) ?>;

    var vocables = shuffle(Object.values(data["vocables"]));
    
    var solution_shown = false;

    update_voc();



    $("#vocable-container").click(function(event) {
        if((event.target.id == "speech") || (event.target.id == "speech-i")) {
            return;
        }
        $("#vocable-container").css({'transform' : 'perspective(600px) rotateY(180deg)'});
        solution_shown = true;
    });

    $("#button-false").click(function() {
        if(solution_shown == true) {
            solution_shown = false;
            button_clicked(false)
        }
    });

    $("#button-right").click(function() {
        if(solution_shown == true) {
            solution_shown = false;
            button_clicked(true)
        }
    });

    $("#speech").click(function(event) {
        if ('speechSynthesis' in window) {
            var msg = new SpeechSynthesisUtterance();
            var voices = window.speechSynthesis.getVoices();
            msg.text = $("#front-main-text").html();
            window.speechSynthesis.speak(msg);

        }else{
            alert("Sorry, your browser doesn't support text to speech!");
        }
    });

    $("#speech-back").click(function() {
        if ('speechSynthesis' in window) {
            var msg = new SpeechSynthesisUtterance();
            var voices = window.speechSynthesis.getVoices();
            msg.text = $("#back-main-text").html();
            window.speechSynthesis.speak(msg);

        }else{
            alert("Sorry, your browser doesn't support text to speech!");
        }
    });

    function button_clicked(has_known) {
        $("#vocable-container").css({'transform' : 'perspective(600px) rotateY(0deg)'});

        setTimeout(function() {
            var known_int = has_known ? 1 : 0;
            $.post("<?=$GLOBALS["BASE_PATH"]?>" + "/trainer/add_vocabulary_stats",
                {
                    owner: data["owner"],
                    voc_id: vocables[0]["id"],
                    has_known: known_int,
                    direction: data["direction"]
                },
                function(data){
                    console.log(data);
            });

            if(has_known) {
                vocable = vocables.shift();
                vocables.push(vocable);
            }else {
                new_index = Math.floor(Math.random() * (6 - 3) ) + 3;
                vocable = vocables.shift();
                vocables.splice(new_index, 0, vocable);
            }

            update_voc();
        }, 250);
        
    }

    function update_voc() {
        if(data["direction"]) {
            $("#front-main-text").html(vocables[0]["f_text"]);
            $("#front-info-text").html(vocables[0]["f_hints"]);
            $("#back-main-text").html(vocables[0]["n_text"]);
            $("#back-info-text").html(vocables[0]["n_hints"]);
            $("#speech").attr("visibility", "visible");
            $("#speech-back").hide()
        }else {
            $("#front-main-text").html(vocables[0]["n_text"]);
            $("#front-info-text").html(vocables[0]["n_hints"]);
            $("#back-main-text").html(vocables[0]["f_text"]);
            $("#back-info-text").html(vocables[0]["f_hints"]);
            $("#speech-back").attr("visibility", "visible");
            $("#speech").hide()
        }
    }


    function shuffle(array) {
        let counter = array.length;

        // While there are elements in the array
        while (counter > 0) {
            // Pick a random index
            let index = Math.floor(Math.random() * counter);

            // Decrease counter by 1
            counter--;

            // And swap the last element with it
            let temp = array[counter];
            array[counter] = array[index];
            array[index] = temp;
        }

        return array;
    }

</script>


