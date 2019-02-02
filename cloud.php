<?php
session_start();
if(!isset($_SESSION['userid'])) {
    die('Bitte zuerst <a href="index.php">einloggen</a>');
}

//Abfrage der Nutzer ID vom Login
$userid = $_SESSION['userid'];
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
    <div>Hallo User <?php echo $userid; ?></div>

    <div class="baustellenSelect">
        <select size="40">
                <option>Baustelle 1Hallo</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1asdaaaaaaaaadsasdsdaasdadssdadsd</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3</option>
                <option>Baustelle 1</option>
                <option>Baustelle 2</option>
                <option>Baustelle 3Ende</option>
            </select>
    </div>
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
