<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="register.css">
    <title>Account erstellen</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  </head>
  <body>
    <?php
    require_once("function.php");
    if(isset($_POST["submit"])){
      $db = getdb();
      init_db();
      $stmt = $db->prepare("SELECT USERNAME, PASSWORD FROM accounts WHERE USERNAME = :user"); //Username überprüfen
      $stmt->bindParam(":user", $_POST["username"]);
      $result = $stmt->execute();

      $count = countRows($result);
  
      if($count == 0){
        //Username ist frei
        if($_POST["pw"] == $_POST["pw2"]){
          //User anlegen
          $stmt = $db->prepare("INSERT INTO accounts (USERNAME, PASSWORD) VALUES (:user, :pw)");
          $stmt->bindParam(":user", $_POST["username"]);
          $hash = password_hash($_POST["pw"], PASSWORD_BCRYPT);
          $stmt->bindParam(":pw", $hash);
          $stmt->execute();
          echo "Dein Account wurde angelegt";
 
          sleep(2);
          header("Location: index.php");

        } else {
          echo "Die Passwörter stimmen nicht überein";
        }
      } else {
        echo "Der Username ist bereits vergeben";
      }
    }
     ?>
    <div class="wrapper"> 
      <form action="register.php" method="post">
        <h1>Account erstellen</h1>
        <div class="input-box">
          <input type="text" name="username" placeholder="Username" required><br>
          <i class='bx bxs-user'></i>
        </div>
        <div class="input-box">
          <input type="password" name="pw" placeholder="Passwort" required><br>
          <i class='bx bxs-lock-alt' ></i>
        </div>
        <div class="input-box">
          <input type="password" name="pw2" placeholder="Passwort wiederholen" required><br>
          <i class='bx bxs-lock-alt' ></i>
        </div>
        <div class="input-box">
          <button type="submit" name="submit" class="btn">Erstellen</button>
        </div>
        <div class="register-link">
          <a href="index.php">Hast du bereits einen Account?</a>
        </div>
      </from>
    </div>
  </body>
</html>
