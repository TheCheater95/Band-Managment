<!DOCTYPE html>
<html lang="de">

  <head>
    <link rel="stylesheet" href="style.css" />
    <meta charset="utf-8">
    <title>StreamerNetwork</title>
  </head>
  <body >
    <div class="nav">
      <ul>
        <li>
          <a  href="index.html">Startseite</a >
        </li>
        <li  class="login">
          <a  href="login.html">Login</a >
        </li>
        <li class="login">
          <a class="active" href="register.html">Register</a>
        </li>
      </ul>
    </div>

    <div class="container">
      <div class="item">
  <h1 class="lh1">Login</h1>
  <hr class="lhline">
        <form action="?register=1" method="post">
          <ul>
            <label for="name">Username:</label>
            <br>
            <input class="input" type="text" name="username"  placeholder="Username">
          </ul>
          <ul>
              E-Mail:<br
              <input type="email" size="40" maxlength="250" name="email"><br><br>
          </ul>
          <ul>
            <label for="password">Passwort:</label>
            <br>
            <input class="input" type="password" name="password" placeholder="Passwort">
          </ul>
          <br>
          <ul>
            <input type="submit" value="Abschicken">
          </ul>
        </form>
    </div>
      </div>
  </body>
</html>
<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '');
?>


<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll

if(isset($_GET['register'])) {
    $error = false;
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];

    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'Bitte eine gültige E-Mail-Adresse eingeben<br>';
        $error = true;
    }
    if(strlen($passwort) == 0) {
        echo 'Bitte ein Passwort angeben<br>';
        $error = true;
    }
    if($passwort != $passwort2) {
        echo 'Die Passwörter müssen übereinstimmen<br>';
        $error = true;
    }

    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    if(!$error) {
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if($user !== false) {
            echo 'Diese E-Mail-Adresse ist bereits vergeben<br>';
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO users (email, passwort) VALUES (:email, :passwort)");
        $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash));

        if($result) {
            echo 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
            $showFormular = false;
        } else {
            echo 'Beim Abspeichern ist leider ein Fehler aufgetreten<br>';
        }
    }
}


?>

</body>
</html>
