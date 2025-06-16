<?php
session_start();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Вход за служители</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <style>
    .login-form {
    width: 50%;
    margin: auto;
    background-color: #98c1e1;
    padding: 50px 0px;
    border: 2px solid #244b94;
    border-bottom-right-radius: 5px;
    border-bottom-left-radius: 5px;
    color: #244b94;
}

.login-form input {
    display: block;
    width: 60%;
    margin: auto;
    padding: 8px 15px;
    font-size: 18px;
}

.login-form label {
    display: block;
    text-align: center;
    font-size: 18px;
    margin-bottom: 5px;
}

input.btn {
    margin-top: 30px;
}
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link active" href="index.php">Начало</a>
    <a class="nav-link " href="index_admin.php">Вписване като администратор</a>
       <a class="nav-link active " href="index_admin.php">Вписване като служител</a>
    <a class="nav-link" href="about.php">За нас</a>
</nav>

<h2 style="text-align:center;">Вход за служители</h2>

<form action="#" method="post" class="login-form">
    <label>Потребителско име:</label>
    <input type="text" name="username" required>

    <label>Парола:</label>
    <input type="password" name="password" required>

    <input type="submit" name="login" value="Вход">
</form>

<?php
include "configurate_database.php";

if (isset($_POST['login'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Подготвена заявка за проверка
    $stmt = mysqli_prepare($dbConn, "SELECT id_employee FROM Logins WHERE USERNAME = ? AND PASSWORD = ?");
    mysqli_stmt_bind_param($stmt, "ss", $username, $password);
    mysqli_stmt_execute($stmt);
    $res = mysqli_stmt_get_result($stmt);

    if ($user = mysqli_fetch_assoc($res)) {
        $_SESSION['id_employee'] = $user['id_employee']; // съхраняваме ID на логнатия служител
        header("Location: index_employee.php");
        exit();
    } else {
        echo "<p style='color:red; text-align:center;'>Невалидно потребителско име или парола.</p>";
    }

    mysqli_stmt_close($stmt);
}
?>

</body>
</html>
