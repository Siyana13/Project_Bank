<?php
include "configurate_database.php";

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Вземане и филтриране на данните
    $name    = isset($_POST['name']) ? trim($_POST['name']) : '';
    $egn     = isset($_POST['egn']) ? trim($_POST['egn']) : '';
    $phone   = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $address = isset($_POST['address']) ? trim($_POST['address']) : '';
    $amount  = isset($_POST['amount']) ? floatval($_POST['amount']) : 0;
    $interest = isset($_POST['interest']) ? floatval($_POST['interest']) : 0;
    $currency = isset($_POST['currency']) ? intval($_POST['currency']) : 0;

    if ($name && $egn && $phone && $address && $currency && $amount >= 0) {
        // Вмъкване на клиент
        $stmtClient = mysqli_prepare($dbConn, "INSERT INTO Client (name_client, egn, phone_client, adress) VALUES (?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmtClient, "ssss", $name, $egn, $phone, $address);
        mysqli_stmt_execute($stmtClient);
        $client_id = mysqli_insert_id($dbConn);
        mysqli_stmt_close($stmtClient);

        // Генериране на номер на сметка
        $account_number = rand(100000000, 999999999);

        // Вмъкване на сметка
        $stmtAccount = mysqli_prepare($dbConn, "INSERT INTO Account (number_account, id_client, interest, availability_amount, id_currency) VALUES (?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmtAccount, "iiddi", $account_number, $client_id, $interest, $amount, $currency);
        $success = mysqli_stmt_execute($stmtAccount);
        mysqli_stmt_close($stmtAccount);

        if ($success) {
            $message = "<p style='color: green; text-align: center;'>Сметката беше създадена успешно!</p>";
        } else {
            $message = "<p style='color: red; text-align: center;'>Грешка при създаване на сметка.</p>";
        }
    } else {
        $message = "<p style='color: red; text-align: center;'>Моля, попълнете всички полета коректно.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Създаване на сметка</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        body {
    background-color: #d5e7f5;
    margin: 0;
    font-family: "Courier New", sans-serif;
    scroll-behavior: smooth;
}

h3{
    margin-left: 10px;
    margin-bottom: 2px;
    font-size: 20px;
    color: #244b94;
}


* {
    box-sizing: border-box;
  }

footer {
    background-color: #244b94;
    text-align: center;
    padding: 2px;
    color: #d4d8f2;
}

.invalid-data{
    width: auto;
    height: 20px;
    color:#fff;
    background-color: #000;
    text-align: center;
}

.intro-section {
    background-image: url("images/start.jpg");
    min-height: 800px;
    background-attachment: fixed;
    background-position: center;
    background-repeat: no-repeat;
    background-size: cover;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;
}

.intro-section h2{
    font-size: 50px;
}

.intro-section p{
    font-size: 35px;
}

.intro-section h2, .intro-section p{
    color: #fff;
    text-shadow: 2px 2px #000000;
    animation: 3s lineUp ease-out 1;
}

  @keyframes lineUp {
    0% {
      opacity: 0;
      transform: translateY(80%);
    }
    20% {
      opacity: 0;
    }
    50% {
      opacity: 1;
      transform: translateY(0%);
    }
    100% {
      opacity: 1;
      transform: translateY(0%);
    }
}

    </style>
</head>
<body>
<nav id="nav_guests">
        <a class="nav-link " href="index.php">Начало</a>
        <a class="nav-link active" href="create_account.php">Създаване на сметка</a>
          <a class="nav-link " href="view_my_account.php">Преглед на сметка</a>
       
    </nav>    
<header>
    <h2 style="text-align:center;">Създаване на нова клиентска сметка</h2>
</header>

<?= $message ?>

<form method="post">
    <label><h3>Име на клиента:</h3></label>
    <input type="text" name="name" required>

    <label><h3>ЕГН:</h3></label>
    <input type="text" name="egn" pattern="\d{10}" required placeholder="10 цифри">

    <label><h3>Телефон:</h3></label>
    <input type="text" name="phone" pattern="\d{10}" required placeholder="0888123456">

    <label><h3>Адрес:</h3></label>
    <input type="text" name="address" required>

    <label><h3>Начален баланс:</h3></label>
    <input type="number" step="0.01" name="amount" required>

    <label><h3>Лихвен процент (%):</h3></label>
    <input type="number" step="0.01" name="interest" required>

    <label><h3>Валута:</h3></label>
    <select name="currency" required>
        <option value=""><h3>-- Изберете --</h3></option>
        <?php
        $res = mysqli_query($dbConn, "SELECT id_currency, currency FROM Currency ORDER BY currency");
        while ($row = mysqli_fetch_assoc($res)) {
            echo "<option value='{$row['id_currency']}'>{$row['currency']}</option>";
        }
        ?>
    </select>

    <input type="submit" name="submit" value="Създай сметка">
</form>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
