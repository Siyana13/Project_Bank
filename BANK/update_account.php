<?php
include "configurate_database.php";

$message = "";

// Избор на клиент от падащото меню
$selected_client_id = isset($_POST['client_id']) ? intval($_POST['client_id']) : 0;

// Избор на сметка за редакция
$selected_account_id = isset($_POST['account_id']) ? intval($_POST['account_id']) : 0;

// Обработка на обновяване на сметка
if (isset($_POST['update'])) {
    $account_id = intval($_POST['account_id']);
    $interest = floatval($_POST['interest']);
    $amount = floatval($_POST['amount']);
    $currency = intval($_POST['currency']);

    $stmt = mysqli_prepare($dbConn, "UPDATE Account SET interest = ?, availability_amount = ?, id_currency = ? WHERE id_account = ?");
    mysqli_stmt_bind_param($stmt, "ddii", $interest, $amount, $currency, $account_id);
    $success = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    $message = $success
        ? "<p style='color:green; text-align:center;'>Сметката беше успешно обновена.</p>"
        : "<p style='color:red; text-align:center;'>Възникна грешка при обновяването.</p>";
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редактиране на сметка</title>
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
        <a class="nav-link active" href="index.php">Начало</a>
        <a class="nav-link" href="create_account.php">Създаване на сметка</a>
        <a class="nav-link" href="update_account.php">Актуализиране на сметка</a>
        <a class="nav-link" href="delete_account.php">Изтриване на сметка</a>
    </nav> 
<h2 style="text-align:center;">Актуализиране на сметка по клиент</h2>

<?= $message ?>

<form method="post">
    <label><h3>Избери клиент:</h3></label>
    <select name="client_id" onchange="this.form.submit()">
        <option value="">-- Избери --</option>
        <?php
        $clients = mysqli_query($dbConn, "SELECT id_client, name_client FROM Client ORDER BY name_client");
        while ($client = mysqli_fetch_assoc($clients)) {
            $selected = ($selected_client_id == $client['id_client']) ? "selected" : "";
            echo "<option value='{$client['id_client']}' $selected>{$client['name_client']}</option>";
        }
        ?>
    </select>

    <?php if ($selected_client_id): ?>
        <label><h3>Избери сметка на клиента:</h3></label>
        <select name="account_id" onchange="this.form.submit()">
            <option value=""><h3>-- Избери сметка --</h3></option>
            <?php
            $accounts = mysqli_query($dbConn, "
                SELECT id_account, number_account FROM Account
                WHERE id_client = $selected_client_id
            ");
            while ($acc = mysqli_fetch_assoc($accounts)) {
                $selected = ($selected_account_id == $acc['id_account']) ? "selected" : "";
                echo "<option value='{$acc['id_account']}' $selected>Сметка № {$acc['number_account']}</option>";
            }
            ?>
        </select>
    <?php endif; ?>

    <?php if ($selected_account_id): ?>
        <?php
        $account = mysqli_fetch_assoc(mysqli_query($dbConn, "
            SELECT * FROM Account WHERE id_account = $selected_account_id
        "));
        ?>
        <label><h3>Лихва (%):</h3></label>
        <input type="number" name="interest" step="0.01" value="<?= $account['interest'] ?>" required>

        <label><h3>Наличност:</h3></label>
        <input type="number" name="amount" step="0.01" value="<?= $account['availability_amount'] ?>" required>

        <label><h3>Валута:</h3></label>
        <select name="currency" required>
            <?php
            $currencies = mysqli_query($dbConn, "SELECT id_currency, currency FROM Currency ORDER BY currency");
            while ($cur = mysqli_fetch_assoc($currencies)) {
                $selected = ($cur['id_currency'] == $account['id_currency']) ? "selected" : "";
                echo "<option value='{$cur['id_currency']}' $selected>{$cur['currency']}</option>";
            }
            ?>
        </select>

        <input type="submit" name="update" value="Обнови сметката">
    <?php endif; ?>
</form>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
