<?php
$error="";
$h1 ="Створити новий акаунт";
$button = "Реєстрація";

$id = -1;
$lastname="";
$name="";
$phone="";
$email="";
$photo="";
$password="";
$uploads_dir = '/uploads';

include $_SERVER['DOCUMENT_ROOT'] . "/connection_database.php";
if($_SERVER["REQUEST_METHOD"]=="POST")
{
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirmPassword"];
    $hash_password = password_hash($password, PASSWORD_DEFAULT);

    $photo = $_FILES["photo"]["tmp_name"];
    $filename = uniqid() . '.jpg';
    $filesavepath = $_SERVER['DOCUMENT_ROOT'] . "$uploads_dir/$filename";

    if(!empty(trim($_GET["id"]))) {

        if(empty($photo)){
            $filename = $_POST["savephoto"];
        }
        else{
            If (unlink($_SERVER['DOCUMENT_ROOT'] . "$uploads_dir/" . $_POST["savephoto"])) {
                // file was successfully deleted
            } else {
                // there was a problem deleting the file
            }
            move_uploaded_file($photo, $filesavepath);
        }

        try {
            $pdo_statement = $conn->prepare("UPDATE `users` SET email='" . $_POST["email"] . "', phone='" . $_POST["phone"] . "', firstname='" .
                $_POST["name"] . "', lastname='" . $_POST["lastname"] . "', photo= '". $filename ."', password='" . $hash_password . "' where id=" . $_GET["id"]);
            $pdo_statement->execute();
            header("location: /");
            exit();
        }
        catch (PDOException $e) {
            echo "<br/><br/><br/>Error Database: " . $e->getMessage();
        }
    }
    else {
        move_uploaded_file($photo, $filesavepath);
        try {
            $sql = "INSERT INTO `users` (`email`, `phone`, `firstname`, `lastname`, `photo`, `password`) " .
                "VALUES (?, ?, ?, ?, ?, ?);";
            $hash_password = password_hash($password, PASSWORD_DEFAULT);
            $conn->prepare($sql)->execute([$_POST["email"], $_POST["phone"], $_POST["name"], $_POST["lastname"], $filename, $hash_password]);
            header("location: /");
            exit();
        }
        catch (PDOException $e) {
            echo "<br/><br/><br/>Error Database: " . $e->getMessage();
        }
    }
}
else {
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        if (!empty(trim($_GET["id"]))) {
            $id = $_GET["id"];
            $h1 = "Редагувати користувача";
            $button = "Зберегти";

            $pdo_statement = $conn->prepare("SELECT * FROM `users` where id=" . $_GET["id"]);
            $pdo_statement->execute();
            $result = $pdo_statement->fetch();

            $lastname = $result["lastname"];
            $name = $result["firstname"];
            $phone = $result["phone"];
            $email = $result["email"];
            $photo = $result["photo"];
        }
    }
}
?>

<?php include $_SERVER['DOCUMENT_ROOT']."/_haed.php" ?>

    <div class="row">
        <div class=" offset-md-3 col-md-6 ">
            <h1 class="text-center"><?php echo($h1)?></h1>
            <form class="needs-validation" method="post" novalidate enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="lastname" class="form-label">Прізвище</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required value="<?php echo ($lastname)?>">
                    <div class="invalid-feedback">
                        Вкажіть Прізвище
                    </div>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Ім'я</label>
                    <input type="text" class="form-control" name="name" id="name" required value="<?php echo ($name)?>">
                    <div class="invalid-feedback">
                        Вкажіть Ім'я
                    </div>
                </div>

                <div class="mb-3">
                    <label for="phone" class="form-label">Телефон</label>
                    <input type="text" class="form-control" name="phone" id="phone" required value="<?php echo ($phone)?>">
                    <div class="invalid-feedback">
                        Вкажіть телефон
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Електронна пошта</label>
                    <input type="email" class="form-control" name="email" id="email" required value="<?php echo ($email)?>">
                    <div class="invalid-feedback">
                        Вкажіть пошту
                    </div>
                </div>

                <div class="mb-3">
                    <label for="file" class="form-label">Оберіть фото</label>
                    <input class="form-control form-control-sm" id="file" type="file" name="photo" accept="image/*">
                    <input type="text" hidden name="savephoto" id="savephoto" value="<?php echo ($photo)?>">
                    <span>
                        <img  id="output" class="thumb" height="150" witht="150" title="" alt="test" src='/uploads/<?php echo($photo) ?>' />
                    </span>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Пароль</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3">
                    <label for="confirmPassword" class="form-label">Повтор пароля</label>
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword">
                </div>
                <button type="submit" class=" btn btn-primary"><?php echo ($button)?></button>
            </form>
        </div>
    </div>

    <script src="/js/imask.js"></script>

    <script type="text/JavaScript">
        function handleFileSelect(evt) {
            let file = evt.target.files; // FileList object
            let f = file[0];
            if(f)
            {
                // Only process image files.
                if (!f.type.match('image.*')) {
                    alert("Image only please....");
                    return;
                }
                const url = URL.createObjectURL(f);
                document.getElementById('output').setAttribute("src", url);
            }
        }
        document.getElementById('file').addEventListener('change', handleFileSelect, false);
    </script>

    <script>
        var phoneMask = IMask(
            document.getElementById('phone'), {
                mask: '+{38}(000)000-00-00'
            });
    </script>

<?php include $_SERVER['DOCUMENT_ROOT']."/_footer.php"; ?>