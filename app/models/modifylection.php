<?php
    class Modifylection {

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
                    )";
    
            $result = $this->con->query($sql);
    
            $sql = "CREATE TABLE IF NOT EXISTS lessons (
                        id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                        owner_id int NOT NULL,
                        name varchar(255) NOT NULL,
                        lang varchar(50) NOT NULL,
                        creation_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        FOREIGN KEY (owner_id) REFERENCES voc_users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                        CONSTRAINT unique_lessions UNIQUE (owner_id, name)
                    )";
    
            $result = $this->con->query($sql);
    
            $sql = "CREATE TABLE IF NOT EXISTS vokabeln (
                id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
                lesson_id int NOT NULL,
                n_text varchar(255),
                n_hints varchar(255),
                f_text varchar(255),
                f_hints varchar(255),
                creation timestamp DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE ON UPDATE CASCADE
             )";
             
            $result = $this->con->query($sql);

             $sql = "CREATE TABLE IF NOT EXISTS voc_states (
                    f_flag bool NOT NULL,
                    owner_id int NOT NULL,
                    vokabel_id int NOT NULL,
                        req_time timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        req_count int NOT NULL,
                        score int(4) NOT NULL DEFAULT 0,
                    FOREIGN KEY (owner_id) REFERENCES voc_users(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    FOREIGN KEY (vokabel_id) REFERENCES vokabeln(id) ON DELETE CASCADE ON UPDATE CASCADE,
                    PRIMARY KEY ( owner_id, vokabel_id, f_flag )
                    )";
    
            $result = $this->con->query($sql);
        }

        public function create_lection($name, $language, $author) {
            if($stmt = $this->con->prepare("INSERT INTO lessons (owner_id, name, lang) VALUES (?, ?, ?)")) {
                $stmt->bind_param("sss", $author, $name, $language);
                $stmt->execute();
                $lection_id = $stmt->insert_id;
                $stmt->close();
                return $lection_id;
            }else {
                return false;
            }
        }

        public function remove_lesson($lec_id) {
            if($stmt = $this->con->prepare("DELETE FROM lessons WHERE id=?")) {
                $stmt->bind_param("i", intval($lec_id));
                $stmt->execute();

                $stmt->close();
                return true;
            }else {
                return false;
            }
        }

        public function get_lesson_by_id($lesson_id) {
            if($stmt = $this->con->prepare("SELECT name, lang, creation_date FROM lessons WHERE id=?")) {
                $stmt->bind_param("i", $lesson_id);
                $stmt->bind_result($name,$lang,$creation_date);
                $stmt->execute();
                $lesson = [];
                while ($stmt->fetch()) {
                    $lesson["name"] = $name;
                    $lesson["language"] = $lang;
                    $lesson["creation_date"] = $creation_date;
                }
                $stmt->close();

                if($stmt = $this->con->prepare("SELECT id,n_text,n_hints,f_text,f_hints FROM vokabeln WHERE lesson_id=?")) {
                    $stmt->bind_param("i", $lesson_id);
                    $stmt->bind_result($id,$n_text,$n_hints,$f_text,$f_hints);
                    $stmt->execute();
                    $vocs = array();
                    while ($stmt->fetch()) {
                        $vocable = array();
                        $vocable["n_text"] = $n_text;
                        $vocable["n_hints"] = $n_hints;
                        $vocable["f_text"] = $f_text;
                        $vocable["f_hints"] = $f_hints;
                        $vocs[$id] = $vocable;
                    }
                    $stmt->close();
                    $lesson["vocables"] = $vocs;
                }else {
                    return false;
                }
                return $lesson;
            }else {
                return false;
            }
        }

        public function get_vocable_stats($voc_id, $user_id) {
            if($stmt = $this->con->prepare("SELECT f_flag,req_time,req_count,score FROM voc_states WHERE owner_id=? AND vokabel_id=?")) {
                $stmt->bind_param("ii",  intval($user_id), intval($voc_id));
                $stmt->execute();
    
                $stmt->bind_result($f_flag,$req_time,$req_count,$score);

                $voc_states = array();
                while ($stmt->fetch()) {
                    $voc_states[$f_flag]["req_time"] = $req_time;
                    $voc_states[$f_flag]["req_count"] = $req_count;
                    $voc_states[$f_flag]["score"] = $score;
                }
    
                $stmt->close();
                return $voc_states;
            }else {
                echo "Error: ".$this->con->error;
                return false;
            }
        }

        public function is_lection_from_user($lec_id, $user_id) {
            if($stmt = $this->con->prepare("SELECT owner_id FROM lessons WHERE owner_id=? AND id=?")) {
                $stmt->bind_param("ii", $user_id, $lec_id);
                $stmt->execute();
    
                $stmt->bind_result($author);
                $stmt->fetch();
    
                $stmt->close();

                if($author == $user_id) {
                    return true;
                }else {
                    return false;
                }
            }else {
                return false;
            }

        }

        public function search_lection($name) {
            if( !($stmt = $this->con->prepare("SELECT id, owner_id, name, lang, creation_date FROM lessons WHERE name LIKE ?"))) {
                return "Prepare failed: ".$this->con->error;
            }

            $searchquery = "%{$name}%";
            if ( !$stmt->bind_param("s", $searchquery) ) {
                return "Binding failed: ".$stmt->error;
            }

            if ( !$stmt->execute() ) {
                return "Execute failed: ".$stmt->error;
            }
    
            if ( !$stmt->bind_result($id, $owner_id, $name, $lang, $creation_date) ) {
                return "Result bind failed: ".$stmt->error;
            }
            $lessons = array();
            while ($stmt->fetch()) {
                $lessons[$id]["owner"] = $owner_id;
                $lessons[$id]["name"] = $name;
                $lessons[$id]["language"] = $lang;
                $lessons[$id]["creation_date"] = $creation_date;
            }
            $stmt->close();
            return $lessons;
        }

        public function create_vocabulary($lesson_id, $n_text, $n_hints, $f_text, $f_hints) {
            if($stmt = $this->con->prepare("INSERT INTO vokabeln (lesson_id, n_text, n_hints, f_text, f_hints) VALUES (?, ?, ?, ?, ?)")) {
                $stmt->bind_param("issss", $lesson_id, $n_text, $n_hints, $f_text, $f_hints);
                $stmt->execute();
                $voc_id = $stmt->insert_id;
                $stmt->close();
                return $voc_id;
            }else {
                return false;
            }
        }

        public function delete_voc($voc_id) {
            if($stmt = $this->con->prepare("DELETE FROM vokabeln WHERE id=?")) {
                $stmt->bind_param("i", $voc_id);
                $stmt->execute();

                $stmt->close();
                return true;
            }else {
                return false;
            }
        }

        public function edit_vocabulary($voc_id, $n_text, $n_info, $f_text, $f_info) {
            if($stmt = $this->con->prepare("UPDATE vokabeln SET n_text = ?, n_hints = ?, f_text = ?, f_hints = ? WHERE id=?")) {
                $stmt->bind_param("ssssi", $n_text, $n_info, $f_text, $f_info, $voc_id);
                $stmt->execute();
                $stmt->close();
                return true;
            }else {
                return false;
            }
        }

        public function get_lesson_by_name_and_owner($lesson_name, $owner_id) {
            if($stmt = $this->con->prepare("SELECT id, lang, creation_date FROM lessons WHERE owner_id = ? AND name = ? ")) {
                if(!$stmt->bind_param("is", $owner_id, $lesson_name)) {
                    return "Binding failed: ".$stmt->error;
                }            
                
                if ( !$stmt->execute() ) {
                    return "Execute failed: ".$stmt->error;
                }

                if(!$stmt->bind_result($id, $lang, $creation_date) ) {
                    return "Result bind failed: ".$stmt->error;
                }
                
                $lesson = array();
                while ($stmt->fetch()) {
                    $lesson["id"] = $id;
                    $lesson["lang"] = $lang;
                    $lesson["creation_date"] = $creation_date;
                }
                $stmt->close();
                return $lesson;
            }else {
                return false;
            }
        }

        public function load_lesson($lesson_id) {
            if($stmt = $this->con->prepare("SELECT id,n_text,n_hints,f_text,f_hints FROM vokabeln WHERE lesson_id=?")) {
                $stmt->bind_param("i", $lesson_id);
                $stmt->bind_result($id,$n_text,$n_hints,$f_text,$f_hints);
                $stmt->execute();
                $vocs = array();
                while ($stmt->fetch()) {
                    $vocable = array();
                    $vocable["n_text"] = $n_text;
                    $vocable["n_hints"] = $n_hints;
                    $vocable["f_text"] = $f_text;
                    $vocable["f_hints"] = $f_hints;
                    $vocs[$id] = $vocable;
                }
                $stmt->close();
                return $vocs;
            }else {
                return false;
            }
        }


        public function insert_selection($user_id, $name, $vocabulary) {
            if($stmt = $this->con->prepare("INSERT INTO selections (owner_id, vokabel_id, name) VALUES (?, ?, ?)")) {
                $stmt->bind_param("iis", intval($user_id), intval($vocabulary), $name);
                $stmt->execute();
                $stmt->close();
                return true;
            }else {
                return false;
            }
        }

        public function get_lessons_by_user($user_id) {
            if($stmt = $this->con->prepare("SELECT id FROM lessons WHERE owner_id=?")) {
                $stmt->bind_param("i", $user_id);
                $stmt->bind_result($id);
                $stmt->execute();
                $selections = array();
                while ($stmt->fetch()) {
                    $selections[$id] = $id;
                }
                $stmt->close();
                return $selections;
            }else {
                return false;
            }
        }

        public function get_learned_lessons($user_id) {
            if($stmt = $this->con->prepare("SELECT DISTINCT vokabeln.lesson_id FROM vokabeln INNER JOIN voc_states ON vokabeln.id=voc_states.vokabel_id WHERE owner_id=?")) {
                $stmt->bind_param("i", $user_id);
                $stmt->bind_result($id);
                $stmt->execute();
                $selections = array();
                while ($stmt->fetch()) {
                    $selections[$id] = $id;
                }
                $stmt->close();
                return $selections;
            }else {
                return false;
            }
        }

        public function get_selections_by_user($user_id) {
            if($stmt = $this->con->prepare("SELECT name FROM selections WHERE owner_id=?")) {
                $stmt->bind_param("i", $user_id);
                $stmt->bind_result($name);
                $stmt->execute();
                $selections = array();
                while ($stmt->fetch()) {
                    $selections[$name] = $name;
                }
                $stmt->close();
                return $selections;
            }else {
                return false;
            }
        }


        public function get_query_from_selection($user_id, $selection_name, $max_vocs, $direction) {
            if($stmt = $this->con->prepare("call get_query(?,?,?,?)")) {
                $stmt->bind_param("isii", $user_id, $selection_name, $max_vocs, intval($direction));
                $stmt->execute();
                $stmt->bind_result($owner, $vocable_id, $direction, $n_text, $n_hints, $f_text, $f_hints);
                $vocables = array();
                while ($stmt->fetch()) {
                    $vocables["vocables"][$vocable_id]["id"] = $vocable_id;
                    $vocables["vocables"][$vocable_id]["n_text"] = $n_text;
                    $vocables["vocables"][$vocable_id]["n_hints"] = $n_hints;
                    $vocables["vocables"][$vocable_id]["f_text"] = $f_text;
                    $vocables["vocables"][$vocable_id]["f_hints"] = $f_hints;
                    $vocables["direction"] = $direction;
                    $vocables["owner"] = $owner;
                }
                $stmt->close();
                return $vocables;
            }else {
                return false;
            }
        }

        public function add_stats_to_vocabulary($user_id, $voc_id, $has_known, $direction) {
            if($stmt = $this->con->prepare("call store_result ( ?, ?, ?, ?)")) {
                $stmt->bind_param("iiii", $direction, $user_id, $voc_id, $has_known);
                $stmt->execute();
                $stmt->close();
                return true;
            }else {
                return false;
            }
        }

        public function delete_selection($user_id,$selection_name) {
            if($stmt = $this->con->prepare("DELETE FROM selections WHERE owner_id=? AND name=?")) {
                $stmt->bind_param("is", $user_id, $selection_name);
                $stmt->execute();

                $stmt->close();
                return true;
            }else {
                return false;
            }
        }

        public function search_vok($search_string) {
            if($stmt = $this->con->prepare("SELECT id, n_text, n_hints, f_text, f_hints FROM vokabeln WHERE n_text LIKE ? OR n_hints LIKE ? OR f_text LIKE ? OR f_hints LIKE ?")) {
                
                $sq = "%{$search_string}%";
                $stmt->bind_param("ssss", $sq, $sq, $sq, $sq);
                $stmt->bind_result($id, $n_text, $n_hints, $f_text, $f_hints);
                $stmt->execute();
                $vocabularys = [];
                while ($stmt->fetch()) {
                    $vocabularys[$id] = [$n_text, $n_hints, $f_text, $f_hints];
                }
                $stmt->close();
                return $vocabularys;

            }else {
                return false;
            }
        }

    }