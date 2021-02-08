<head>

    <?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/csslibarys.php";?>
    <title><?=$GLOBALS["APPLICATION_NAME"]?> | Statistik</title>

</head>


<?php include $GLOBALS['FILE_SYSTEM_BASE_PATH']."/app/util/empty_nav.php";?>
<table class="table">
    <thead class="thead-dark">
        <tr>
        <th scope="col">Lektion</th>
        <th scope="col">Sprache</th>
        <th scope="col">Ø 1</th>
        <th scope="col">Ø 2</th>
        <th scope="col">Ø 3</th>
        </tr>
    </thead>
    <?php
        foreach($data["lessons"] as $lesson_id=>$lesson) {
            $average_0_score = 0;
            $average_1_score = 0;
            $vocable_data = "";
            foreach($lesson["vocables"] as $voc_id=>$voc) {
                if($voc["stats"] != false) { 
                    $average_0_score += $voc["stats"][0]["score"];
                    $average_1_score += $voc["stats"][1]["score"];
                    $vocable_data = $vocable_data.
                    "<tr><td>"
                        .$voc['n_text'].
                    "</td>
                    <td>"
                        .$voc['f_text'].
                    "</td>
                    <td>
                        <h4>
                            <span class='badge'  style='background-color: hsl(".round($voc['stats'][0]['score']).",100%,50%);'>
                                ".$voc['stats'][0]['score']."
                            </span>
                        </h4>
                    </td>
                    <td>
                        <h4>
                            <span class='badge'  style='background-color: hsl(".round($voc['stats'][1]['score']).",100%,50%);'>
                                ".$voc['stats'][1]['score']."
                            </span>
                        </h4>
                    </td></tr>";
                }else {
                    $vocable_data = $vocable_data.
                    "<tr><td>"
                        .$voc['n_text'].
                    "</td>
                    <td>"
                        .$voc['f_text'].
                    "</td>
                    <td>
                        <h4>
                            <span class='badge'  style='background-color: hsl(0,100%,50%);'>
                            </span>
                        </h4>
                    </td>
                    <td>
                        <h4>
                            <span class='badge'  style='background-color: hsl(0,100%,50%);'>
                            </span>
                        </h4>
                    </td></tr>";
                }
            }
            $average_0_score = $average_0_score / count($lesson["vocables"]);
            $average_1_score = $average_1_score / count($lesson["vocables"]);
            $average_score = ($average_0_score + $average_1_score) / 2;

            echo "
            
            <tr>
                <th scope='row'>
                    ".$lesson ["name"]."
                    <button class='btn btn-primary' type='button' data-toggle='collapse' data-target='#collapseExample-".strval($lesson_id)."' aria-expanded='false' aria-controls='collapseExample-".strval($lesson_id)."'>
                        <i class='far fa-plus-square'></i>
                    </button>
                </th>
                <td>".$lesson["language"]."</td>
                <td><h4><span class='badge'  style='background-color: hsl(".round($average_0_score).",100%,50%);'>".round($average_0_score)."</span></h4></td>
                <td><h4><span class='badge'  style='background-color: hsl(".round($average_1_score).",100%,50%);'>".round($average_1_score)."</span></h4></td>
                <td><h4><span class='badge'  style='background-color: hsl(".round($average_score).",100%,50%);'>".round($average_score)."</span></h4></td>
                
            </tr>
            
            <tr style='width: 100%'>
                <td colspan=5>
                    <div class='collapse' id='collapseExample-".strval($lesson_id)."'>
                        <div class='card card-body'>
                            <table class='table'>
                                <tr>
                                    <th scope='col'>".$lesson['language']."</th>
                                    <th scope='col'>Deutsch</th>
                                    <th scope='col'>".$lesson['language']." / Deutsch</th>
                                    <th scope='col'>Deutsch / ".$lesson['language']."</th>
                                </tr>
                                ".$vocable_data."
                            </table>
                        </div>
                    </div>
                </td>
            </tr>
            ";
        }
        
    ?>

</table>

<div class="card text-white bg-dark mb-3">
    <div class="card-body ">
            <p>Ø 1: Durchschnitt (Fremdsprache / Deutsch)</p>
            <p>Ø 2: Durchschnitt (Deutsch / Fremdsprache)</p>
            <p>Ø 3: Durchschnitt</p>
    </div>
</div>


