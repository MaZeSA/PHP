<?php

include $_SERVER['DOCUMENT_ROOT'] . "/connection_database.php";
$uploads_dir = '/uploads';

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    if (!empty(trim($_GET["id"]))) {

        $pdo_statement = $conn->prepare("SELECT photo FROM `users` where id=" . $_GET["id"]);
        $pdo_statement->execute();
        $result = $pdo_statement->fetch();

        If (unlink($_SERVER['DOCUMENT_ROOT'] . "$uploads_dir/" . $result["photo"])) {
            // file was successfully deleted
        } else {
            // there was a problem deleting the file
        }

        $pdo_statement=$conn->prepare("DELETE FROM `users` WHERE id=" . $_GET['id'])->execute();
        header("location: /");
        exit();
    }
}
?>
