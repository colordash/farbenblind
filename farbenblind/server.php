<?php
    require_once("function.php");
    session_start();
    if(!isset($_SESSION["username"])){
        header("Location: index.php");
        exit;
    }
    $username = $_SESSION['username'];
    
    $db = getdb();

    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST)){     // könnte vielleicht falsch sein 
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data)){
            $query = "SELECT HIGHSCORE, TIME, ID FROM accounts WHERE USERNAME = :username";
            $stmt = $db->prepare($query);
            $stmt->bindValue(':username', $username, SQLITE3_TEXT);
            $result = $stmt->execute();
            if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            
                $highscore = $row['HIGHSCORE'];
                $time = $row['TIME'];
                $id = $row['ID'];
                
                // Ein Array, das ein assoziatives Array (Objekt-ähnlich) enthält
                $dataArray = [
                    [
                        "platz" => $id,
                        "score" => $highscore,
                        "time" => $time,
                    ]
                ];

                echo json_encode($dataArray);
            } 
            else {
                // Benutzer nicht gefunden
                echo "Benutzer nicht gefunden.";
            }
        }
        elseif (!empty($data)) {
        
            
        
            // Überprüfen, ob die Parameter time und highscore vorhanden sind
            if (isset($data[0]) && array_key_exists("score", $data[0]) && array_key_exists("time", $data[0])) {
                // Werte in neuen Variablen speichern
                $platz = $data[0]["platz"];
                $highscore = $data[0]["score"];
                $time = $data[0]["time"];
        
                // Hier kannst du nun mit den Variablen $time und $highscore arbeiten
                $query = "UPDATE accounts SET TIME = :time, HIGHSCORE = :highscore WHERE USERNAME = :username";
                $stmt = $db->prepare($query);

                if ($stmt) {
                    $stmt->bindValue(':time', $time, SQLITE3_INTEGER);
                    $stmt->bindValue(':highscore', $highscore, SQLITE3_INTEGER);
                    $stmt->bindValue(':username', $username, SQLITE3_TEXT);

                    $result = $stmt->execute();

                    if ($result) {
                        //echo "Daten erfolgreich aktualisiert.";
                        
                        $query = "SELECT * FROM accounts ORDER BY HIGHSCORE DESC, TIME ASC";
                        $result = $db->query($query);

                         /*//Überprüfen, ob die Abfrage erfolgreich war
                        if ($result) {
                            // Ergebnisse durchlaufen und verarbeiten
                            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                                echo "SCORE: " . $row['HIGHSCORE'] . ", TIME: " . $row['TIME'] . "<br>";
                            }
                        } else {
                            // Fehler beim Ausführen der Abfrage
                            echo "Fehler beim Ausführen der Abfrage: " . $db->lastErrorMsg();
                        }  */

                        $query = "SELECT HIGHSCORE, TIME, ID FROM accounts WHERE USERNAME = :username";
                        $stmt = $db->prepare($query);
                        $stmt->bindValue(':username', $username, SQLITE3_TEXT);
                        $result = $stmt->execute();
                        if ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                        
                            $highscore = $row['HIGHSCORE'];
                            $time = $row['TIME'];
                            $id = $row['ID'];
                            
                            // Ein Array, das ein assoziatives Array (Objekt-ähnlich) enthält
                            $dataArray = [
                                [
                                    "platz" => $id,
                                    "score" => $highscore,
                                    "time" => $time,
                                ]
                            ];

                            echo json_encode($dataArray);
                        }

                    } else {
                        echo "Fehler beim Ausführen der Aktualisierungsabfrage.";
                    }
                } else {
                    echo "Fehler beim Vorbereiten der Aktualisierungsabfrage.";
                }
            } else {
                echo "Ungültiges Datenformat: time und highscore müssen vorhanden sein.";
            }
        } else {
            echo "Das Array wurde nicht als solches erkannt";
        }
    }


    
?>
    

