<?php
session_start();
include "configurate_database.php";

//  Обработка на добавяне
if (isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($dbConn, $_POST['name']);
    $egn = mysqli_real_escape_string($dbConn, $_POST['egn']);
    $phone = mysqli_real_escape_string($dbConn, $_POST['phone']);
    $address = mysqli_real_escape_string($dbConn, $_POST['address']);

    // Проверка дали вече има клиент със същото ЕГН
    $check = mysqli_query($dbConn, "SELECT * FROM Client WHERE egn = '$egn'");
    if (mysqli_num_rows($check) > 0) {
        echo "<script>alert('Клиент с това ЕГН вече съществува!');</script>";
    } else {
        $insert = "INSERT INTO Client (name_client, egn, phone_client, adress)
                   VALUES ('$name', '$egn', '$phone', '$address')";
        if (mysqli_query($dbConn, $insert)) {
            echo "<script>alert('Клиентът беше успешно добавен!'); window.location.href='add_new_client.php';</script>";
        } else {
            echo "<script>alert('Грешка при добавяне на клиента.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добави нов клиент</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <style>
        form {
            width: 50%;
            margin: 0 auto;
        }
        input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin: 8px 0;
        }
    </style>
</head>
<body>
<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link active" href="add_new_client.php">Добави клиент</a>
      <a class="nav-link " href="view_client.php">Списък с клиенти</a>
    <a class="nav-icon" href="index.php">
        <img src="images/admin.png" alt="Изход" width="35px" height="35px">
    </a>
</nav>

<h2 class="heading">Добавяне на нов клиент</h2>

<form method="post">
    <label><h3>Име и фамилия:</h3></label>
    <input type="text" name="name" required>

    <label><h3>ЕГН:</h3></label>
    <input type="text" name="egn" maxlength="10" pattern="\d{10}" title="Въведете точно 10 цифри" required>

    <label><h3>Телефонен номер:</h3></label>
    <input type="text" name="phone" maxlength="10" pattern="\d{10}" required>

    <label><h3>Адрес:</h3></label>
    <input type="text" name="address" required>

    <input type="submit" name="submit" class="btn" value="Добави клиент">
</form>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>
</body>
</html>
