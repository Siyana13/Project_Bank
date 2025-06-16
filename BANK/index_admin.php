<?php
     session_start();

?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <link rel="stylesheet" href="navbar.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <title>Начало за администратор</title>
    </head>
    <body>
    <nav id="nav_guests">
                <a class="nav-link" href="add_new_employee.php">Впиши нов служител</a>
                <a class="nav-link" href="add_new_client.php">Вписване на нов клиент</a>
                <a class="nav-link" href="client_transactions.php">Справки за транзакции</a>
                <a class="nav-link" href="confirmation_requests.php">Заявки за потвърждение</a>
                <a class="nav-link" href="account_menu.php">Управление на сметките на клиенти</a>
                <a class="nav-link" href="transfer_money.php">Преводи</a>
                <a class="nav-link" href="index.php">Изход от профила</a></div>
    </nav>
        <h2 class="heading">Добър ден, 
        <?php 
            include "configurate_database.php";
            $id_user = $_SESSION["id_user"];
            $sql = "SELECT * FROM EMPLOYEE WHERE id_employee ='$id_user'";
            if($row=mysqli_fetch_assoc(mysqli_query($dbConn, $sql)))
                echo $row['name_employee']."</h2>";
            
            if (isset($_GET['index.html']))
                $_SESSION["id_user"]=null;

        ?>
    </body>
</html>
