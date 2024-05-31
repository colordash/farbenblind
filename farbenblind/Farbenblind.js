window.addEventListener('load', function(){
    // Variabeln Definieren
    let farbe1; // = getComputedStyle(document.documentElement).getPropertyValue('--farbe1');
    const spieldfeld = this.document.getElementById('spielfeld');
    const feldklassen = ["feldleer", "feldeins", "feldzwei"];
    const anzeige = this.document.getElementById('score')
    const resetButton = document.getElementById('resetButton');
    const highscore_screen = this.document.getElementById('highscore');
    let score = 0;
    let dataArray = [];
    let platz = null;

    var startTime;
    var timerInterval;
    var hours
    var minutes
    var seconds
    var milliseconds
    var elapsedTime
    //------------------------------------------------------------------------------------------------------

    // EventListeners
    resetButton.addEventListener('click', reset);
    document.addEventListener('keydown', keypress);
    // -JavaScript-Code, um den Logout-Button mit der Weiterleitung zu verknüpfen-
    document.getElementById("logoutButton").addEventListener("click", function() {
        window.location.href = "logout.php";
    });
    //------------------------------------------------------------------------------------------------------

    // Funktionen Definieren
    const setServerData = function(){
        try {
            // Anfrage an den Server
            fetch('server.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: dataArray,
            }).then(response => {
                response.text().then(data => {
                    console.log("Der Server gibt zurück: " + data);
                    try{
                        const dataArraydecoded = JSON.parse(data);
                        if(Array.isArray(dataArraydecoded)){
                            dataArray = data;
                            updateHighscore();
                        }
                    } catch (error) {
                        console.log("Der Server gibt kein Array zurück")
                    }
                });
                
            }
            );
        } catch (error) {
            console.error('Fehler beim Senden der Daten:', error);
            throw error;
        }
    }
    function keypress(event){
        if (event.code === 'Enter' || event.code === 'Space'){
            if (document.getElementById("gameOverModal").style.display === "flex"){
                reset();
            }      
        }   
    }
    var msString = function(a){
        hours = Math.floor(a / (1000 * 60 * 60));
        minutes = Math.floor((a % (1000 * 60 * 60)) / (1000 * 60));
        seconds = Math.floor((a % (1000 * 60)) / 1000);
        milliseconds = Math.floor((a % 1000) / 10);

        // Führende Nullen hinzufügen, wenn nötig
        hours = (hours < 10 ? "0" : "") + hours;
        minutes = (minutes < 10 ? "0" : "") + minutes;
        seconds = (seconds < 10 ? "0" : "") + seconds;
        milliseconds = (milliseconds < 10 ? "0" : "") + milliseconds;

        // Zeit in das HTML-Element mit der ID "timer" einfügen
        return hours + ":" + minutes + ":" + seconds + ":" + milliseconds;
    }
    const updateHighscore = function(){
        console.log("in den LocalStorage wird gespeichert: " + dataArray);
        localStorage.setItem('highscore', dataArray);
        platz = JSON.parse(dataArray)[0].platz
        highscore_screen.innerText = "Highscore: " + JSON.parse(localStorage.getItem("highscore"))[0].score + "     Time: " + msString(JSON.parse(localStorage.getItem("highscore"))[0].time);
    }
    const getDiv = function(spalte, zeile) {
        return spieldfeld.children.item(spalte+10*zeile);
    }
    const setWert = function(div, wert) {
        div.setAttribute('wert', wert);
        div.className = feldklassen[wert];
    }
    const getfeldeins = function(){
        let hexfarbe1 = Math.floor(Math.random()*360)
        farbe1 = `hsl(${hexfarbe1}, 100%, 50%)`;
        console.log("farbe 1:" + farbe1)

        return {farbe1: farbe1, hexfarbe1: hexfarbe1};
    }
    const getfeldzwei = function(hexfarbe1){
        let sätigungfarbe2 = 50 + (Math.pow(score, 0.45)* 10.7);
        farbe2 = `hsl(${hexfarbe1}, ${sätigungfarbe2}%, 50%)`;
        console.log("prozent: " + sätigungfarbe2);
        console.log("farbe 2: " + farbe2);
        let randomx = Math.floor(Math.random()*10);
        console.log("x: " + "nix für dii");
        let randomy = Math.floor(Math.random()*6);
        console.log("y: "+ "nix für dii");
        document.documentElement.style.setProperty('--farbe2', farbe2);
        setWert(getDiv(randomx, randomy), 2);
        console.log("------------")
        console.log(" ")
    }
    const getwert = function(div) {
        return Number(div.attributes.wert.value);
    }
    const feldzeigen = function(){
        let div = document.querySelectorAll('.feldzwei').item(0);
        setWert(div, 0);
    }
    function showgewonnen() {
        document.getElementById("gewonnenModal").style.display = "flex";
    }
    function showgameover() {
        document.getElementById("gameOverModal").style.display = "flex";
    }
    function hidegameover() {
        document.getElementById("gameOverModal").style.display = "none";
    }
    const feldclick = function(){
        if (score <= 0){
             startTimer();
        }
        let div = this; 
        if (getwert(div) == 2 ){
            nächsterunde();
        }
        else{
            stopTimer();
            feldzeigen();
            setTimeout(function(){
                showgameover();
            }, 1500)
        }
    }
    function startTimer() {
        startTime = new Date().getTime();

        // Aktualisiere die Zeit alle 10 Millisekunden
        timerInterval = setInterval(updateTimer, 10);
        }
        function stopTimer() {
        schlussZeit = elapsedTime;
        clearInterval(timerInterval);
        }
        function resetTimer() {
        clearInterval(timerInterval);
        document.getElementById("timer").innerHTML = "00:00:00:00";
        }
        function updateTimer() {
        var currentTime = new Date().getTime();
        elapsedTime = currentTime - startTime;

        timerElement = document.getElementById("timer");
        timerElement.innerHTML = msString(elapsedTime);
    }           
    const makeFeld = function(farbe1) {
        document.documentElement.style.setProperty('--farbe1', farbe1);

        for (let zeile=0; zeile<6; zeile++) {
            for (let spalte=0; spalte<10; spalte++) {
                // Unterelement erzeugen
                let div = document.createElement('div');
                // Attribute setzen
                div.setAttribute('spalte', spalte);
                div.setAttribute('zeile', zeile);
                
                // Klick-Funktion definieren
                div.addEventListener('click', feldclick);
                // Klasse für die Darstellung hinzufügen
                setWert(div, 1);
                // Dem Feld hinzufügen
                spieldfeld.appendChild(div);
            }
        }
    }
    const rasterlöschen = function(){
        while (spieldfeld.firstChild){
            spieldfeld.removeChild(spieldfeld.firstChild);
        }
    }
    const nächsterunde = function(){
         score = score + 1;
        if (score == 30){
            showgewonnen();
        }
        else{
            console.log(score +". move")
            rasterlöschen();
            FeldErstellen();
            anzeige.innerHTML = "score: " + score; 
        }
       
    }
    function reset(){
        if(TimeCheck() || score > JSON.parse(localStorage.getItem("highscore"))[0].score  ){
            console.log("Event vom reset wurde durchgeführt");
            dataArray = JSON.stringify([{"platz" : platz, "score" : score, "time": elapsedTime}])
            if (window.navigator.onLine) {
                try {
                    console.log("Dem server wird dieser String geschickt: " + dataArray)
                    setServerData();
                } catch (error) {
                    console.log("es gab ein error");
                    // Handle error if necessary
                }
            }
        }
        updateHighscore();
        console.log("dini time isch: " + elapsedTime);
        console.log("dine score isch " + score);

        score = 0;
        anzeige.innerHTML = "score: " + score; 
        document.getElementById('spielfeld').innerHTML = ""
        FeldErstellen();
        hidegameover();
        resetTimer();
    }
    const FeldErstellen = function() {
        let feldeins = getfeldeins();
        makeFeld(feldeins.farbe1);
        getfeldzwei(feldeins.hexfarbe1);
    }
    const TimeCheck = function(){
        if(score === JSON.parse(localStorage.getItem("highscore"))[0].score){
            if(elapsedTime > JSON.parse(localStorage.getItem("highscore"))[0].time){
                return true
            }
            else{
                return false
            }
        }
        else{
            return false
        }
    }
    //----------------------------------------------------------------------------------------------------

    // Spiel Beginn
    if (this.navigator.onLine) {
        setServerData();
        console.log('versuche Daten vom Server zu laden');   
    } else {
        let gibts = this.localStorage.getItem("highscore");
        if (!gibts) {
            dataArray = JSON.stringify([{"platz": "offline", "score": 1, "time": 0}]);
            updateHighscore();
        }
    }
    FeldErstellen();
})