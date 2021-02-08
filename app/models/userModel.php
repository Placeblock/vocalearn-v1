<?php



class UserModel {

    public function __construct() {
        $db = "DB4173608";
        $this->con = new MySQLi('rdbms.strato.de',"U4173608", "t4B2aEBYH4bSJcCR", $db);
        if($this->con->connect_error) {
            die ("VERBINDUNG ZUR DATENBANK FEHLGESCHLAGEN : " .$con->connect_error);
        }

        $sql = "CREATE TABLE IF NOT EXISTS voc_users ( # parent table
                id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                email varchar(255) NOT NULL,
                password varchar(255) NOT NULL,
                name varchar(255) NOT NULL,
                class varchar(30) NOT NULL,
                perm varchar(30) NOT NULL,
                verified tinyint(1) NOT NULL,
                total_querys int NOT NULL DEFAULT 0,
                creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                modification_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                ) ENGINE=innodb;
            )";

        $result = $this->con->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS password_validate  (
            userid INT(6) PRIMARY KEY,
            token VARCHAR(100) NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";
        $result = $this->con->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS password_reset (
            userid INT(6) PRIMARY KEY,
            token VARCHAR(100) NOT NULL,
            reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )";

        $result = $this->con->query($sql);
    }

    public function check_password($user_id, $pswd) {
        if($stmt = $this->con->prepare("SELECT password FROM voc_users WHERE id= ?")) {
            $stmt->bind_param("s", $user_id);
            $stmt->execute();

            $result = $stmt->bind_result($password);

            $stmt->fetch();

            $stmt->close();

            if(!is_null($password)) {
                if (password_verify($pswd, $password)) {
                    return true;
                }else {
                    return false;
                }
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function is_verified($user_id) {
        if($stmt = $this->con->prepare("SELECT verified FROM voc_users WHERE id= ?")) {
            $stmt->bind_param("s", $user_id);
            $stmt->execute();

            $result = $stmt->bind_result($verified);

            $stmt->fetch();

            $stmt->close();

            return $verified;

        }else {
            return false;
        }
    }

    public function get_id_by_email($email) {
        if($stmt = $this->con->prepare("SELECT id FROM voc_users WHERE email=?")) {
            $stmt->bind_param("s", $email);
            $stmt->execute();

            $stmt->bind_result($id);
            $stmt->fetch();

            $stmt->close();

            if(!is_null($id)) {
                return $id;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function get_name_by_id($user_id) {
        if($stmt = $this->con->prepare("SELECT name FROM voc_users WHERE id=?")) {
            $stmt->bind_param("i", $user_id);
            $stmt->execute();

            $stmt->bind_result($name);
            $stmt->fetch();

            $stmt->close();

            if(!is_null($name)) {
                return $name;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function register_user($user_name, $pswd, $email) {
        if($stmt = $this->con->prepare("INSERT INTO voc_users (email, password, name, verified) VALUES (? , ? , ?, false)")) {
            $stmt->bind_param("sss",$email, password_hash($pswd, PASSWORD_DEFAULT), $user_name);
            $stmt->execute();

            $token = $this->create_token();

            $link = ("http:".$GLOBALS["BASE_PATH"]."/user/verify?token=".$token."");

            $user_id = $this->get_id_by_email($email);

            if($stmt = $this->con->prepare("INSERT INTO password_validate (userid, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token=VALUES(token)")) {
                $stmt->bind_param("ss", $user_id, $token);
                $stmt->execute();
                $stmt->close();
            }else {
                return false;
            }

            $email_content = "Bitte Verifiziere deinen Account in dem du auf den Folgenden Link klickst! Ich hoffe, du hast viel Spaß beim Lernen!\n\n\n".$link."\n\n\n ~Felix";
            $email_content = wordwrap($email_content, 70);

            $headers = 'From: felix.weglehner@gmx.de' . "\r\n" .'Reply-To: '.$email.'' . "\r\n" . "Content-type: text/plain; charset=utf-8";
            mail($email, "Bitte Verifiziere deinen Account | ".$GLOBALS["APPLICATION_NAME"]."", $email_content, $headers);

            return true;
        }else {
            echo("hier");
            return false;
        }
    }


    public function create_token() {
        $bytes = random_bytes(32);
        return bin2hex($bytes);
    }

    public function verify_user($token) {
        if($stmt = $this->con->prepare("DELETE FROM password_validate WHERE reg_date < NOW() - INTERVAL 45 MINUTE")) {
            $stmt->execute();
        }else {
            return false;
        }
        if($stmt = $this->con->prepare("SELECT userid FROM password_validate WHERE token=?")) {
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            if(!is_null($user_id)) {
                if($stmt = $this->con->prepare("UPDATE voc_users SET verified=true WHERE id=?")) {
                    $stmt->bind_param("i", $user_id);
                    $stmt->execute();
                    $stmt->close();
                }else {
                    var_dump($this->con->error);
                    return;
                }

                if($stmt = $this->con->prepare("DELETE FROM password_validate WHERE token=?")) {
                    $stmt->bind_param("s", $token);
                    $stmt->execute();
                    $stmt->close();
                    return true;
                }else {
                    return false;
                }
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function get_reset_password_user_id($token) {
        if($stmt = $this->con->prepare("DELETE FROM password_reset WHERE reg_date < NOW() - INTERVAL 30 MINUTE")) {
            $stmt->execute();
        }else {
            return false;
        }
        if($stmt = $this->con->prepare("SELECT userid FROM password_reset WHERE token=?")) {
            $stmt->bind_param("s", $token);
            $stmt->execute();

            $stmt->bind_result($user_id);
            $stmt->fetch();
            $stmt->close();

            if(!is_null($user_id)) {
                return $user_id;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function set_new_password($user_id, $pswd) {
        if($stmt = $this->con->prepare("UPDATE users SET password =? WHERE id= ?")) {
            $stmt->bind_param("ss", password_hash($pswd, PASSWORD_DEFAULT), $user_id);
            $stmt->execute();

            $stmt->close();

            if($stmt = $this->con->prepare("DELETE FROM password_reset WHERE userid= ?")) {
                $stmt->bind_param("s", $user_id);
                $stmt->execute();
                return true;
            }else {
                return false;
            }
        }else {
            return false;
        }
    }

    public function create_reset_token($user_id, $email) {

        $token = $this->create_token();

        $link = ("http:".$GLOBALS["BASE_PATH"]."/user/enter_new_password?token=".$token."");

        if($stmt = $this->con->prepare("INSERT INTO password_reset (userid, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token=VALUES(token)")) {
            $stmt->bind_param("ss", $user_id, $token);
            $stmt->execute();
            $stmt->close();

            $email_content = "Wenn du deinen Account zurücksetzen willst, klicke auf diesen link!\n\n\n".$link."\n\n\n ~Felix";
            $email_content = wordwrap($email_content, 70);

            $headers = 'From: felix.weglehner@gmx.de' . "\r\n" .'Reply-To: '.$email.'' . "\r\n" . "Content-type: text/plain; charset=utf-8";
            mail($email, "Zurücksetzen deines Passworts | ".$GLOBALS["APPLICATION_NAME"]."", $email_content, $headers);
            return true;
        }else {
            return false;
        }
    }



}