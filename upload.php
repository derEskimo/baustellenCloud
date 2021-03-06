<!--Pay attention to permission of uploads Folder!!! and post_max_size=0 in php.ini-->
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


$baustellenAuswahl = $_GET["bau"];
$statement = $pdo->prepare("SELECT bau_id, user_id FROM baustellen WHERE baustellen_Name = :baustellenName");
$result = $statement->execute(array('baustellenName' => $baustellenAuswahl));
$res = $statement->fetchall();
$bau_id = $res[0]["bau_id"];
$user_id = $res[0]["user_id"];


$target = 'uploads/'.$user_id.'/'.$bau_id.'/';
$res = mkdir ($target ,0777 , true);

$counter = 0;
foreach ($_FILES["fileToUpload"]["name"] as $filename){
    echo print($_FILES["fileToUpload"]);
    $target_file = $target.basename($filename);
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"][$counter], $target_file)) {
        $statement = $pdo->prepare("INSERT INTO files (bau_id, path, type, size) VALUES (:bauid, :filePath, :typ, :size);");
        $statement->execute(array('bauid' => $bau_id, 'filePath' => $target_file, 'typ' => $_FILES["fileToUpload"]["type"][$counter], 'size' => $_FILES["fileToUpload"]["size"][$counter]));
        header('Location: cloud.php');
    } else {
        echo "Sorry, there was an error uploading one of your files.";
    }
    $counter +=1;
}
?>
