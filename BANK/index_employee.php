<?php
session_start();
include "configurate_database.php";

// Проверка дали служителят е логнат
if (!isset($_SESSION["id_employee"])) {
    header("Location: signin_employee.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Начало за служител</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="add_new_client.php">Вписване на нов клиент</a>
    <a class="nav-link" href="client_transactions.php">Справки за транзакции</a>
    <a class="nav-link" href="confirmation_requests.php">Заявки за потвърждение</a>
    <a class="nav-link" href="account_menu.php">Управление на сметките на клиенти</a>
    <a class="nav-link" href="transfer_money.php">Преводи</a>
    <a class="nav-link" href="index.php">Изход от профила</a>
</nav>

<h2 class="heading">
    Добър ден,
    <?php
    $id_employee = $_SESSION["id_employee"];
    $query = "SELECT name_employee FROM Employee WHERE id_employee = $id_employee";
    $result = mysqli_query($dbConn, $query);
    if ($row = mysqli_fetch_assoc($result)) {
        echo htmlspecialchars($row['name_employee']);
    }
    ?>
</h2>

</body>
</html>
