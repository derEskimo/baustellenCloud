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
$baustellenAuswahl = $_SESSION['baustellenAuswahl'];

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
    }
    header('Location: cloud.php');
}
if (isset($_GET['select'])) {
    $_SESSION["baustellenAuswahl"] = $_POST['baustellen_auswahl'];
    header('Location: cloud.php');
}

if (isset($_GET['remove'])) {
    if (!empty($baustellenAuswahl)) {
        $statement = $pdo->prepare("DELETE FROM baustellen WHERE baustellen_Name = :baustellenName");
        $statement->execute(array('baustellenName' => $baustellenAuswahl));
    }
    header('Location: cloud.php');
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
        <button><img src="media/material.io/addBaustelle.svg"/></button>
    </form>

    <form action="?select=1" method="post">
        <select size="30" name="baustellen_auswahl" style="width: 14vw" onchange="this.form.submit();">
            <?php
            for ($i = 0; $i < count($baustellenSelectData); $i++) {
                if ($baustellenSelectData[$i]["baustellen_Name"]== $baustellenAuswahl){
                    echo '<option selected>'.$baustellenSelectData[$i]["baustellen_Name"].'</option>';}
                else {
                    echo '<option>'.$baustellenSelectData[$i]["baustellen_Name"].'</option>';}
            }
            ?>
        </select><br>
    </form>

    <form action="?remove=1" method="post">
        Ausgewählte Baustelle löschen<br>
        <button><img src="media/material.io/deleteBaustelle.svg"/></button>
    </form>
</left>

<right>
    <menue>
        <form action="help.php" method="post">
            <button><img src="media/material.io/help.svg"/></button>
        </form>
        <form action="info.php" method="post">
            <button><img src="media/material.io/info.svg"/></button>
        </form>
        <form>
            <button><img src="media/material.io/settings.svg"/></button>
        </form>
        <form action="logout.php" method="post">
            <button><img src="media/material.io/logout.svg"/></button>
        </form>
    </menue>

    <content>
        <div class="file-container">
            <?php
            $statement = $pdo->prepare("SELECT bau_id FROM baustellen WHERE baustellen_Name = :baustellenName");
            $result = $statement->execute(array('baustellenName' => $baustellenAuswahl));
            $bau_id = $statement->fetch()[0];
            $directory = scandir ( 'uploads/'.$userid.'/'.$bau_id, SCANDIR_SORT_ASCENDING);

            for ($i = 2; $i < count($directory); $i++) {
                echo "<div>".$directory[$i]."</div>";
            }
            ?>
        </div>
        <button class="addShare"><img src="media/material.io/unlock.svg"></button>

        <button class="downloadFile"><img src="media/material.io/downloadFile.svg"></button>

        <form action="upload.php?bau=<?=$baustellenAuswahl?>" method="post" enctype="multipart/form-data">
            <label class="addFile">
                <img src="media/material.io/uploadFile.svg" style="   position:absolute; top: 18px; left: 18px;">
                <input type="file" name="fileToUpload" id="fileToUpload" onchange="form.submit()"/>
            </label>
        </form>

    </content>
</right>

</body>
