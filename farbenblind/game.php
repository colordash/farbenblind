<?php
// Alle php-Fehler anzeigen
error_reporting(E_ALL); 
require_once("function.php");
checkLogin();

 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <title>Farbenblind</title>
    <link rel="stylesheet" href="Farbenblind.css?v=1.0">
    <script src="Farbenblind.js?=1.0"></script>
    
</head>
<body>


    <div id="gewonnenModal" class="modal">
        <div class="modal-content">
        <h1>Herzlichen Glückwunsch du hast das Maximum erreicht</h1>
        </div>
    </div>


    <div id="gameOverModal" class="modal">
        <div class="modal-content">
        <h1>War wohl ein falsches Feld</h1>
        <button id="resetButton">Neuer Versuch</button>
        </div>
    </div>

    <div id="header">
        <button id="logoutButton">Logout</button>
    </div>
    <div id="title">
        <div id="div-score"><p id="score">score: 0</p></div>
        <div id="div-timer"><p id="timer">00:00:00:00</p></div>
        <div id="div-highscore"><p id="highscore">...</p></div>
        <div id="div-titel"><p id="titel">Klicke auf das nicht dazupassende Feld</p></div>
        <div id="bestenliste"></div>        
    </div>
            
        
    <div id="spielfeld"></div>
   
</body>
</html>
