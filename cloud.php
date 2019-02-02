<?php
include('settings.conf'); //import Data for mySQL DB
session_start();


if (!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="index.php">einloggen</a>');
}

//log in to mysql
try {
    $pdo = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    // set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo '<script>console.log("Connected successfully to DB: ' . $database . '")</script>';
} catch (PDOException $e) {
    echo '<script>console.log("Connection to DB failed: ' . $e->getMessage() . '")</script>';
}

//Abfrage der Nutzer ID vom Login
$userid = $_SESSION['userid'];

$statement = $pdo->prepare("SELECT vorname,nachname FROM users WHERE user_id = :userid");
$result = $statement->execute(array('userid' => $userid));
$user = $statement->fetch();
$vorname = $user['vorname'];
$nachname = $user['nachname'];

$statement = $pdo->prepare("SELECT baustellen_Name FROM baustellen WHERE user_id = :userid");
$result = $statement->execute(array('userid' => $userid));
$baustellenSelectData = $statement->fetchAll();

if (isset($_GET['add'])) {
    $baustellenName = $_POST['baustellen_name'];
    if (!empty($baustellenName)) {
        $statement = $pdo->prepare("INSERT INTO baustellen (user_id, baustellen_name) VALUES (:userid, :baustellenName);");
        $statement->execute(array('userid' => $userid, 'baustellenName' => $baustellenName));
        header('Location: cloud.php');
    }
}

if (isset($_GET['remove'])) {
    $baustellenAuswahl = $_POST['baustellen_auswahl'];
    if (!empty($baustellenAuswahl)) {
        $statement = $pdo->prepare("DELETE FROM baustellen WHERE baustellen_Name = :baustellenName");
        $statement->execute(array('baustellenName' => $baustellenAuswahl));
        header('Location: cloud.php');
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>baustellenCloud</title>
    <link rel="shortcut icon" type="image/x-icon" href="media/favicon.ico">
    <link rel="stylesheet" type="text/css" href="cloud.css">
</head>

<body>

<left>
    <img src="media/Profilbild.png" style="width: 8vw"/>

    <div style="padding-top: 2vh">Hallo <?php echo $vorname . " " . $nachname; ?></div>

    <form action="?add=1" method="post" style="padding-top: 4vh">
        Baustelle hinzufügen:
        <input type="text" name="baustellen_name" style="width: 14vw"/>
        <button><img src="media/addBaustelle.svg"/></button>
    </form>

    <form action="?remove=1" method="post">
        <select size="30" name="baustellen_auswahl" style="width: 14vw">
            <?php
            for ($i = 0; $i < count($baustellenSelectData); $i++) {
                echo '<option value="' . $baustellenSelectData[$i]["baustellen_Name"] . '">' . $baustellenSelectData[$i]["baustellen_Name"] . '</option>';
            }
            ?>
        </select><br>
        Ausgewählte Baustelle löschen<br>
        <button><img src="media/deleteBaustelle.svg"/></button>
    </form>
</left>

<right>
    <menue>
        <form action="info.php" method="post">
            <button><img src="media/info.svg"/></button>
        </form>
        <form>
            <button><img src="media/settings.svg"/></button>
        </form>
        <form action="logout.php" method="post">
            <button><img src="media/logout.svg"/></button>
        </form>
    </menue>

    <content>
        <button class="addShare"><img src="media/unlock.svg"></button>

        <form action="upload.php" method="post" enctype="multipart/form-data">
            <label class="addFile">
                <img src="media/uploadFile.svg" style="   position:absolute; top: 18px; left: 18px;">
                <input type="file" name="fileToUpload" id="fileToUpload" onchange="form.submit()"/>
            </label>
        </form>

    </content>
</right>

</body>
