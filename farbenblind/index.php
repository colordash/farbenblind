<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="login.css">
    <link rel="manifest" href="manifest.json" />
    <!-- ios support -->
    <link rel="apple-touch-icon" href="images/icons/icon-512x512.png" />
    <meta name="apple-mobile-web-app-status-bar" content="#db4938" />
    <meta name="theme-color" content="#db4938" />
  </head>
<script>
if ("serviceWorker" in navigator) {
        navigator.serviceWorker
           .register("serviceWorker.js")
           .then(res => console.log("service worker registered"))
           .catch(err => console.log("service worker not registered", err))
        
    }
</script>

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
        
        echo "<script>console.log('$count');</script>";

        if($count == 1){
            //Username ist frei
            $row = $result->fetchArray();

            if (password_verify($_POST["pw"], $row["PASSWORD"])) {
                session_start();
                $_SESSION["username"] = $row["USERNAME"];
                header("Location: game.php");
            } else {
                echo "Der Login ist fehlgeschlagen";
            }
        } 
        else {
            echo "Den benutzer gibts nicht :(";
        }
      }
     ?>
    <div class="wrapper">
      <form action="index.php" method="post">
        <h1>Login</h1>
        <div class="input-box">
          <input type="text" name="username" placeholder="Username" required>
          <i class='bx bxs-user'></i>
        </div>
        <div class="input-box">
          <input type="password" name="pw" placeholder="Passwort" required>
          <i class='bx bxs-lock-alt' ></i>
        </div>
        <div class="remember-forgot">
          <label><input type="checkbox">Remember me</label>
          <a href="#">Forgot password</a>
        </div>
        <button type="submit" name="submit" class="btn">Einloggen</button>
        <div class="register-link">
          <p>Noch keinen Account? <a href="register.php">Register</a></p>
        </div>
      </form>
    </div>
  </body>
</html>
