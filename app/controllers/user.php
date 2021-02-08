<?php

class User extends Controller{

    public function default() {
        $model = $this->model("userModel");

        $this->view("login", []);
    }

    public function profile() {
        if(isset($_SESSION["user_id"])) {
            $model = $this->model("userModel");
            $lessonmodel = $this->model("modifylection");

            $profile_name = $model->get_name_by_id($_SESSION["user_id"]);
            if($profile_name == false) {
                $profile_name = "Unknown Name";
            }

            $this->view("profile", ["profile_name"=>$profile_name]);
        }else {
            $this->view("login", ["status"=>"Du musst eingeloggt sein um dein Profil sehen zu können!"]); 
            return; 
        }
    }

    public function login() {
        $model = $this->model("userModel");

        $this->view("login", []);
    }

    public function logout() {
        unset($_SESSION["user_id"]);
        $this->view("home", []);
    }

    public function register() {
        $model = $this->model("userModel");

        $this->view("register", []);
    }

    public function checklogin() {
        $model = $this->model("userModel");
        $status = "";

        if((!isset($_POST["lemail"])) || (!isset($_POST["lpswd"]))) {
            $status = "Falsche Email oder Passwort";
            $this->view("login", ["status"=>$status]);
            return;
        }

        $user_id = $model->get_id_by_email($_POST["lemail"]);
        if($user_id == false) {
            $status = "Falsche Email oder Passwort";
            $this->view("login", ["status"=>$status]);
            return;
        }
        if(!$model->is_verified($user_id)) {
            $status = "Bitte verifiziere deinen Accound erst mit deiner Email Adresse!";
            $this->view("login", ["status"=>$status]);
            return;
        }

        if($model->check_password($user_id, $_POST["lpswd"])) {
            $_SESSION["user_id"] = $user_id;
            if($user_name = $model->get_name_by_id($_SESSION["user_id"])) {

                require_once "app/controllers/trainer.php";

                $trainercontroller = new Trainer;

                $trainercontroller->default();
            }else {
                $this->view("login", ["status"=>"Irgendetwas hat nicht geklappt. Bitte versuche es mal mit <a href='".$GLOBALS["BASE_PATH"]."/home'>diesem</a> Link"]);
            }
            return;
        }else {
            $status = "Falsche Email oder Passwort";
        }

        
        $this->view("login", ["status"=>$status]);

    }


    public function checkregister() {
        $model = $this->model("userModel");
        $status = "";

        if((!isset($_POST["rname"])) || (!isset($_POST["rpswd"])) || (!isset($_POST["remail"]))) {
            $status = "Bist du dir sicher, alles angegeben zu haben?";
        }

        # PRÜFEN OB KOREKKTE EINGABE VORLIEGT
        if(strlen($_POST["rname"]) <= 20 ) {
            if(strlen($_POST["rname"]) >= 5 ) {
                if(strlen($_POST["rpswd"]) <= 50 ) {
                    if(strlen($_POST["rpswd"]) >= 8 ) {
                        if (preg_match_all( "/[0-9]/", $_POST["rpswd"]) >= 3 ) {
                            if(filter_var($_POST["remail"], FILTER_VALIDATE_EMAIL)) {
                                #PRÜFEN OB DER USER EXISTIERT BZW NICHT EXISTIERT
                                if(!$model->get_id_by_email($_POST["remail"])) {
                                    #Hat das registrieren geklappt?
                                    if($model->register_user($_POST["rname"], $_POST["rpswd"], $_POST["remail"])) {
                                        $this->view("show_message", ["message"=>"Wir haben dir eine Email mit einem Link geschickt. Dort kannst du dich verifizieren"]);
                                        return;
                                    }else {
                                        $status = "Beim Registrieren hat etwas nicht geklappt! Versuche es bitte nochmal!";
                                    }
                                }else {
                                    $status = "Maximal einen Account pro Email! Diese Email ist schon belegt!";
                                }
                            }else {
                                $status = "Du musst eine richtige Email Adresse angeben!";
                            }
                        }else {
                            $status = "Dein Passwort muss mindestens 3 Zahlen enthalten!";
                        }
                     }else {
                        $status = "Die minimale Länge des Passworts beträgt 8 Zeichen!";
                     }
                }else {
                    $status = "Die maximale Länge des Passworts beträgt 50 Zeichen!";
                }
            }else {
                $status = "Die minimale Länge des Namens beträgt 5 Zeichen!";
            }
        }else {
            $status = "Die maximale Länge des Namens beträgt 20 Zeichen!";
        }

        
        $this->view("register", ["status"=>$status]);

    }


    public function verify() {
        $model = $this->model("userModel");

        if(isset($_GET["token"])) {
            if($model->verify_user($_GET["token"])) {
                $this->view("login", ["status"=>"Du bist erfolgreich Verifiziert! Melde dich nun an und starte mit dem Lernen!"]);
            }else {
                $this->view("show_message", ["message"=>"Du hast einen falschen Token angegeben. Wenn du meinst, dass dies ein Fehler ist probiere es gleich nochmal."]);
            }
        }else {
            $this->view("show_message", ["message"=>"Du hast einen falschen Token angegeben. Wenn du meinst, dass dies ein Fehler ist probiere es gleich nochmal."]);
        }

    }

    public function enter_new_password() {
        $model = $this->model("userModel");
        if(isset($_GET["token"])) {
            if($user_id = $model->get_reset_password_user_id($_GET["token"])) {
                $_SESSION["rspw_user_id"] = $user_id;
                $this->view("reset_password", ["status"=>""]);
            }else {
                $this->view("show_message", ["message"=>"Du hast einen falschen Token angegeben. Wenn du meinst, dass dies ein Fehler ist probiere es gleich nochmal."]);
            }
        }else {
            $this->view("show_message", ["message"=>"Du hast einen falschen Token angegeben. Wenn du meinst, dass dies ein Fehler ist probiere es gleich nochmal."]);
        }
    }

    public function set_new_password() {
        $model = $this->model("userModel");
        if(isset($_SESSION["rspw_user_id"])) {
            if(isset($_POST["rpswd"])) {
                if(strlen($_POST["rpswd"]) <= 50 ) {
                    if(strlen($_POST["rpswd"]) >= 8 ) {
                        if (preg_match_all( "/[0-9]/", $_POST["rpswd"]) >= 3 ) {
                            if($model->set_new_password($_SESSION["rspw_user_id"], $_POST["rpswd"])) {
                                unset($_SESSION["rspw_user_id"]);
                                $this->view("login", ["status"=>"Erfolgreich Passwort zurückgesetzt! Melde dich nun an und starte mit dem Lernen!"]);
                                return;
                            }else {
                                $this->view("show_message", ["message"=>"Ein Fehler ist aufgetreten! Bitte probier es nochmal!"]);
                            }
                        }else {
                            $status = "Dein Passwort muss mindestens 3 Zahlen enthalten!";
                        }
                     }else {
                        $status = "Die minimale Länge des Passworts beträgt 8 Zeichen!";
                     }
                }else {
                    $status = "Die maximale Länge des Passworts beträgt 50 Zeichen!";
                }
            }else {
                $status="Du musst ein neues Passwort angeben!";
            }
        }else {
            $this->view("show_message", ["message"=>"Ein Fehler ist aufgetreten! Bitte probier es nochmal!"]);
        }

        
        $this->view("reset_password", ["status"=>$status]);

        
    }

    public function send_reset_token() {
        $model = $this->model("userModel");
        if(isset($_POST["remail"])) {
            if($user_id = $model->get_id_by_email($_POST["remail"])) {
                if($model->create_reset_token($user_id, $_POST["remail"])) {
                    $this->view("show_message", ["message"=>"Wir haben dir eine Email mit einem Link geschickt. Dort kannst du dein Passwort zurücksetzen"]);
                }else {
                    $this->view("show_message", ["message"=>"Ein Fehler ist aufgetreten! Bitte probier es nochmal!"]);
                }
            }else {
                $this->view("enter_email_reset_password", ["status"=>"Diese Email ist nicht registriert!"]);
            }
        }else {
            $this->view("enter_email_reset_password", ["status"=>"Du musst eine Email angeben!"]);
        }
    }

    public function reset() {
        $this->view("enter_email_reset_password", []);
    }
}