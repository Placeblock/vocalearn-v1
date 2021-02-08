<?php

class Trainer extends Controller{

    public function default() {
        if(!$this->islogged()) {
            $this->view("home", []);
        }else {
            $model = $this->model("userModel");
            $lessonmodel = $this->model("modifylection");

            $selections = $lessonmodel->get_selections_by_user($_SESSION["user_id"]);
            if($selections == false && !is_array($selections)) {
                $this->view("home", ["profilename"=>$model->get_name_by_id($_SESSION["user_id"]),"selections"=>[],"lessons"=>[]]);
            }

            $lesson_ids = $lessonmodel->get_lessons_by_user($_SESSION["user_id"]);
            if($lesson_ids == false && !is_array($lesson_ids)) {
                $this->view("home", ["profilename"=>$model->get_name_by_id($_SESSION["user_id"]),"selections"=>[],"lessons"=>[]]);
            }

            $lessons = [];
            foreach($lesson_ids as $key=>$value) {
                $lessons[$key] = $lessonmodel->get_lesson_by_id($key)["name"];
            }

            $this->view("home", ["profilename"=>$model->get_name_by_id($_SESSION["user_id"]),"selections"=>$selections,"lessons"=>$lessons]);
        }
    }

    public function search_lection() {
        if(!$this->islogged()) {
            $this->view("login", ["status"=>"Du musst eingeloggt sein um lernen zu können!"]); 
            return; 
        }

        $model = $this->model("modifylection");
        $response = $model->get_selections_by_user($_SESSION["user_id"]);
        if($response == false && !is_array($response)) {
            $this->view("search_lection", []);
            return;
        }
        $this->view("search_lection", ["selections"=>$response]);
    }


    public function get_lessons_by_name() {
        if(!isset($_POST["query"])) {
            echo "Du musst eine query übergeben";
            return;
        }
        $model = $this->model("modifylection");
        $usermodel = $this->model("userModel");
        $result = $model->search_lection($_POST["query"]);
        foreach($result as $key=>$value) {
            $result[$key]["owner_name"] = $usermodel->get_name_by_id($value["owner"]);
        }
        echo json_encode($result);
    }

    public function create_lection() {
        if(!$this->islogged()) {
            $this->view("login", ["status"=>"Du musst eingeloggt sein um eine Lektion erstellen zu können!"]); 
            return; 
        }
        $model = $this->model("modifylection");


        if(isset($_POST["lectionname"]) && isset($_POST["language"])) {
            $lesson_data = $model->get_lesson_by_name_and_owner($_POST["lectionname"], $_SESSION["user_id"]);
            if( is_string($lesson_data)){
                $this->view("create_lection", ["create_status"=>"Fehler: ".$lesson_data]);
                return;
            }
            if(!$lesson_data) {
                $lection_id = $model->create_lection($_POST["lectionname"], $_POST["language"], $_SESSION["user_id"]);
                if($lection_id != false) {
                    $this->view("edit_lection", ["lection_id"=>$lection_id, "language"=>$_POST["language"], "lectionname"=>$_POST["lectionname"]]);
                    return;
                }else {
                    $this->view("create_lection", ["create_status"=>"Bei der Verbindung zur Datenbank ist etwas schief gegangen :/ . Bitte versuche es später nocheinmal"]);
                    return;
                }
            }else {
                $this->view("edit_lection", ["lection_id"=>$lesson_data["id"], "language"=>$lesson_data["lang"], "lectionname"=>$_POST["lectionname"]]);
                return;
            }
        }else {
            $this->view("create_lection", ["create_status"=>"Jetz noch einen Namen... und daan gehts los!"]);
        }
    }


    public function delete_lesson() {
        if(!isset($_POST["lesson_id"])) {
            echo "Fehlender Parameter: lesson_id";
        }
        $model = $this->model("modifylection");

        $result = $model->remove_lesson($_POST["lesson_id"]);

        if($result = true) {
            echo "Lektion erfolgreich gelöscht!";
            return;
        }else {
            echo "Fehler bei der Verbindug zur Datenbank :-/ !";
            return;
        }
    }

    public function update_voc() {
        $response = [];


        if(!$this->islogged()) {
            $response["result"] = "Du musst angemeldet sein!";
            echo json_encode($response);
            return;
        }
        $model = $this->model("modifylection");

        if(!isset($_POST["lection_id"]) || !isset($_POST["n_text"]) || !isset($_POST["n_info"]) || !isset($_POST["f_text"]) || !isset($_POST["f_info"])) {
            $response["result"] = "Fehlende Angaben";
            echo json_encode($response);
            return;
        }
        if(!$model->is_lection_from_user($_POST["lection_id"], $_SESSION["user_id"])) {
            $response["result"] = "Das ist nicht deine Lektion";
            echo json_encode($response);
            return;
        }
        if(isset($_POST["voc_id"])) {
            $result = $model->edit_vocabulary($_POST["voc_id"], $_POST["n_text"], $_POST["n_info"], $_POST["f_text"], $_POST["f_info"]);
            if($result == false) {
                $response["result"] = "Fehler bei der Verbindung zur Datenbank";
                echo json_encode($response);
                return;
            }
            $response["result"] = "Abgespeichert";
            echo json_encode($response);
            return;
        }else {
            $result = $model->create_vocabulary($_POST["lection_id"], $_POST["n_text"], $_POST["n_info"], $_POST["f_text"], $_POST["f_info"]);
            if($result == false) {
                $response["result"] = "Fehler bei der Verbindung zur Datenbank";
                echo json_encode($response);
                return;
            }else {
                $response["result"] = "Abgespeichert";
                $response["id"] = $result;
                echo json_encode($response);
                return;
            }
        }
    }

    public function delete_voc() {
        $response = [];
        
        if(!$this->islogged()) {
            echo "Du musst angemeldet sein!";
            return;
        }

        $model = $this->model("modifylection");

        if(!isset($_POST["lection_id"]) || !isset($_POST["voc_id"])) {
            echo "Fehlende Angaben";
            return;
        }
        if(!$model->is_lection_from_user($_POST["lection_id"], $_SESSION["user_id"])) {
            echo "Das ist nicht deine Lektion";
            return;
        }
        $result = $model->delete_voc($_POST["voc_id"]);
        if($result == false) {
            echo "Fehler bei der Verbindung zur Datenbank";
            return;
        }else {
            echo "Erfolgreich gelöscht";
            return;
        }

    }

    public function load_lection() {
        if(!$this->islogged()) {
            $response["result"] = "Du musst angemeldet sein!";
            echo json_encode($response);
            return;
        }

        $model = $this->model("modifylection");

        if(!isset($_POST["lection_id"])) {
            $response["result"] = "Fehlende Angaben";
            echo json_encode($response);
            return;
        }
        $response = $model->load_lesson($_POST["lection_id"]);
        if($response == false && !is_array($response)) {
            $response["result"] = "Fehler!";
            echo json_encode($response);
            return;
        }else {
            $response["result"] = "Erfolgreich Lektion geladen!";
            echo json_encode($response);
            return;
        }

    }


    public function add_lesson_to_selection() {
        if(!$this->islogged()) {
            echo "Du musst angemeldet sein!";
            return;
        }
        if(!isset($_POST["lesson_id"])) {
            echo "Fehler!";
            return;
        }
        if(!isset($_POST["selection_name"])) {
            echo "Fehler!";
            return;
        }

        $model = $this->model("modifylection");
        $response = $model->load_lesson(intval($_POST["lesson_id"]));
        if($response == false && !is_array($response)) {
            echo "Fehler bei der Verbindung zur Datenbank!";
            return;
        }

        $not_saved = 0;
        foreach ($response as $id=>$value) {
            $result = $model->insert_selection($_SESSION["user_id"], $_POST["selection_name"], $id);
            if ($result == false) {
                $not_saved++;
            }
        }
        echo strval(count($response)-$not_saved)." von ".count($response)." Vokabeln abgespeichert!";
    }


    public function learn() {

        if(!$this->islogged()) {
            $this->view("login", ["status"=>"Du musst eingeloggt sein um üben zu können!"]);
            return; 
        }

        if(!isset($_GET["selection_name"])) {
            $this->view("show_message", ["message"=>"Achtung Fehler! Bitte probiere es später nocheinmal :-/ (FehlerCode: 302)"]);
            return;
        }
        if(!isset($_GET["max_vocs"])) {
            $this->view("show_message", ["message"=>"Achtung Fehler! Bitte probiere es später nocheinmal :-/ (FehlerCode: 303)"]);
            return;
        }
        if(!isset($_GET["direction"])) {
            $this->view("show_message", ["message"=>"Achtung Fehler! Bitte probiere es später nocheinmal :-/ (FehlerCode: 304)"]);
            return;
        }

        $model = $this->model("modifylection");

        $query = $model->get_query_from_selection($_SESSION["user_id"], $_GET["selection_name"], $_GET["max_vocs"], $_GET["direction"]);


        if($query == false && !is_array($query)) {
            $this->view("show_message", ["message"=>"Achtung! Fehler bei der Verbindung zur Datenbank :-/ (FehlerCode: 100)"]);
            return;
        }
        if(is_array($query)) {
            if(is_array($query["vocables"])) {
                if(count($query["vocables"]) == 0) {
                    $this->view("show_message", ["message"=>"Diese Selection enthält keine Vokabeln! :-/ (FehlerCode: 305)"]);
                    return;
                }
            }else {
                $this->view("show_message", ["message"=>"Diese Selection enthält keine Vokabeln! :-/ (FehlerCode: 305)"]);
                return;
            }
        }else {
            $this->view("show_message", ["message"=>"Achtung! Fehler bei der Verbindung zur Datenbank :-/ (FehlerCode: 100)"]);
            return;
        }

        $this->view("vocabulary_trainer", ["result"=>$query]);


    }

    public function add_vocabulary_stats() {
        if(!isset($_POST["owner"])) {
            echo "Fehlende Angaben! Owner";
            return;
        }
        
        if(!isset($_POST["voc_id"])) {
            echo "Fehlende Angaben! Voc_id";
            return;
        }
        
        if(!isset($_POST["has_known"])) {
            echo "Fehlende Angaben! has_known";
            return;
        }

        if(!isset($_POST["direction"])) {
            echo "Fehlende Angaben! direction";
            return;
        }

        $model = $this->model("modifylection");

        $result = $model->add_stats_to_vocabulary($_POST["owner"], $_POST["voc_id"], $_POST["has_known"], $_POST["direction"]);

        if($result == false) {
            echo "Database Error Occured";
            return;
        }
    }


    public function delete_selection() {
        if(!isset($_POST["selection_name"])) {
            echo "Fehlende Angaben! Selection_name";
            return;
        }

        if(!isset($_POST["user_id"])) {
            echo "Fehlende Angaben! user_id";
            return;
        }

        $model = $this->model("modifylection");

        $result = $model->delete_selection($_POST["user_id"], $_POST["selection_name"]);

        if($result = true) {
            echo "Selection ".$_POST["selection_name"]." erfolgreich gelöscht!";
            return;
        }else {
            echo "Fehler bei der Verbindug zur Datenbank :-/ !";
            return;
        }
    }


    public function user_stats() {
        if(!$this->islogged()) {
            echo "fehler1";
            return; 
        }

        $model = $this->model("modifylection");

        $lesson_ids = $model->get_learned_lessons($_SESSION["user_id"]);

        if($lesson_ids == false && !is_array($query)) {
            echo "fehler2";
            return;
        }

        $lessons = [];
        foreach($lesson_ids as $key=>$value) {
            $lessons[$key] = $model->get_lesson_by_id($key);
        }
        foreach($lessons as $id=>$lesson) {
            foreach($lesson["vocables"] as $voc_id=>$voc) {
                $voc_stats = $model->get_vocable_stats($voc_id, $_SESSION["user_id"]);
                $lessons[$id]["vocables"][$voc_id]["stats"] = $voc_stats;
            }
        }

        $this->view("user_stats", ["lessons"=>$lessons]);

    }

    public function woerterbuch() {
        $this->view("wordbook", []);
    }

    public function search_vokabulary() {
        if(isset($_POST["search_vok"])) {
            $model = $this->model("modifylection");
            $result = $model->search_vok($_POST["search_vok"]);
            echo json_encode($result);

        }else {
            return false;
        }
    }



    protected function islogged() {
        return isset($_SESSION["user_id"]);
    }
}