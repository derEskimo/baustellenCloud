<?php
include('settings.conf'); //import Data for mySQL DB

session_start();

//log in to mysql
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<script>console.log("Connected successfully to DB: ' . $database . '")</script>';
} catch (PDOException $e) {
    echo '<script>console.log("Connection to DB failed: ' . $e->getMessage() . '")</script>';
}


if (isset($_GET['login'])) {
    $email = $_POST['email'];
    $passwort = $_POST['passwort'];

    $statement = $pdo->prepare("SELECT * FROM users WHERE email = :email");
    $result = $statement->execute(array('email' => $email));
    $user = $statement->fetch();

    //Überprüfung des Passworts
    if ($user !== false && password_verify($passwort, $user['passwort'])) {
        $_SESSION['userid'] = $user['id'];
        header( 'Location: cloud.php' );
    } else {
        $errorMessage = "E-Mail oder Passwort war ungültig<br>";
    }

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
    <title>Login</title>
</head>
<body>

<div>
    <img src="media/baustelle.png" style="height: 300px">
</div>
<div>
    <h1 style="color: #e41408;">baustellenCloud</h1>
</div>

<form action="?login=1" method="post">
    E-Mail:<br>
    <input type="email" size="40" maxlength="250" name="email"><br><br>

    Passwort:<br>
    <input type="password" size="40" maxlength="250" name="passwort"><br>
    <br>
    <?php
    if (isset($errorMessage)) {
        echo "<div style='color: #e41408;'>".$errorMessage."</div>";
    }else{echo "<br>";}
    ?>
    <br>

    <div style="text-align:center">
        <input type="submit" value="Abschicken" style="display:inline-block"><br>
        <br>
        Sie haben noch keinen Account? Dann registrieren Sie sich <a href="register.php">hier</a>!
    </div>
</form>


</body>
</html>