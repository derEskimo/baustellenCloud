<?php
include('settings.conf'); //import Data for mySQL DB

session_start();

//log in to mysql
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<script>console.log("Connected successfully to DB: '.$database.'")</script>';
}
catch(PDOException $e)
{
    echo '<script>console.log("Connection to DB failed: '.$e->getMessage().'")</script>';
}

?>

<style type="text/css">
    html{
        height: 100vh;
        width: 100vw;
        margin: 0;
        font-family: "Trebuchet MS", "Lucida Grande", "Lucida Sans Unicode", "Lucida Sans", sans-serif;
    }

    body {
        padding: 50px;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    form {
        background: rgba(244, 183, 13, 0.92);
        padding: 50px;
        border-radius: 10px;
    }
</style>

<!DOCTYPE html>
<html>
<head>
    <title>Registrierung</title>
</head>
<body>

<?php
$showFormular = true; //Variable ob das Registrierungsformular anezeigt werden soll

if(isset($_GET['register'])) {
    $error = false;
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];
    $passwort2 = $_POST['passwort2'];
    $vorname = $_POST['vorname'];
    $nachname = $_POST['nachname'];

    //Prüfe Eingaben
    if(strlen($vorname) == 0) {
        $Message = 'Bitte ein Vornamen angeben';
        $error = true;
    }
    elseif(strlen($nachname) == 0) {
        $Message = 'Bitte ein Nachnamen angeben';
        $error = true;
    }
    elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $Message = 'Bitte eine gültige E-Mail-Adresse eingeben';
        $error = true;
    }
    elseif(strlen($passwort) == 0) {
        $error = true;
        $Message = 'Bitte ein Passwort angeben';
    }
    elseif($passwort != $passwort2) {
        $error = true;
        $Message = 'Die Passwörter müssen übereinstimmen';
    }
    //Überprüfe, dass die E-Mail-Adresse noch nicht registriert wurde
    elseif(!$error){
        $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $result = $statement->execute(array('email' => $email));
        $user = $statement->fetch();

        if($user !== false) {
            $Message = 'Diese E-Mail-Adresse ist bereits vergeben';
            $error = true;
        }
    }

    //Keine Fehler, wir können den Nutzer registrieren
    if(!$error) {
        $passwort_hash = password_hash($passwort, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("INSERT INTO users (email, passwort, vorname, nachname) VALUES (:email, :passwort, :vorname, :nachname);");
        $result = $statement->execute(array('email' => $email, 'passwort' => $passwort_hash, 'vorname' => $vorname, 'nachname' => $nachname));

        if($result) {
            $Message = 'Du wurdest erfolgreich registriert. <a href="login.php">Zum Login</a>';
            $showFormular = false;
        } else {
            $Message = 'Beim Abspeichern ist leider ein Fehler aufgetreten';
        }
    }


}?>
    <h1>Willkommen</h1>
    <form action="?register=1" method="post">
        Vorname:<br>
        <input type="text" size="40" maxlength="25" name="vorname" value="<?php echo isset($_POST['vorname']) ? $_POST['vorname'] : '' ?>" ><br>
        Nachname:<br>
        <input type="text" size="40" maxlength="25" name="nachname" value="<?php echo isset($_POST['nachname']) ? $_POST['nachname'] : '' ?>"><br><br>

        E-Mail:<br>
        <input type="email" size="40" maxlength="250" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>"><br><br>

        Dein Passwort:<br>
        <input type="password" size="40"  maxlength="25" name="passwort"><br>

        Passwort wiederholen:<br>
        <input type="password" size="40" maxlength="25" name="passwort2"><br><br>

        <?php
        if (isset($Message)) {
            echo "<div style='color: #e41408;'>".$Message."</div>";
        }else{echo "<br>";}
        ?>
        <br>
        <input type="submit" value="Abschicken">
    </form>
</body>
</html>