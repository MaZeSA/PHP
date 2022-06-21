<?php include $_SERVER['DOCUMENT_ROOT']."/_haed.php" ?>

<div class="row">
    <h1 class="text-center">Список користувачів</h1>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">Фото</th>
            <th scope="col">ПІБ</th>
            <th scope="col">Пошта</th>
            <th scope="col">Телефон</th>
            <th scope="col"></th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody>
        <?php
        include "connection_database.php";
        $sql="SELECT * FROM `users`";
        $result = $conn->query($sql);
        while($row=$result->fetch())
        {
            echo "
        <tr>
            <th><img src='/uploads/".$row["photo"]."' width='75' alt=''></th>
            <td>".$row["firstname"]." ".$row["lastname"]."</td>
            <td>".$row["email"]."</td>
            <td>@".$row["phone"].'</td>
            <td><a class="btn btn-success" href="/auth/register.php?id='. $row['id'] .'" role="button">Редагувати</a></td>
            <td><a class="btn btn-danger" href="/auth/remove.php?id='. $row['id'] .'" role="button">Видалити</a></td>
        </tr>
            ';
        }
        ?>
        

        </tbody>
    </table>
</div>


<?php $_SERVER['DOCUMENT_ROOT']."/_footer.php"; ?>