<?php
include "configurate_database.php";

$message = "";


if (isset($_GET['get_balance'])) {
    $account_id = intval($_GET['get_balance']);
    $result = mysqli_query($dbConn, "SELECT availability_amount FROM Account WHERE id_account = $account_id");
    if ($row = mysqli_fetch_assoc($result)) {
        echo number_format($row['availability_amount'], 2, '.', '');
    } else {
        echo "0.00";
    }
    exit;
}

// Обработка на формата
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $from_account = intval($_POST['from_account']);
    $to_account = intval($_POST['to_account']);
    $amount = floatval($_POST['amount']);
    $id_employee = intval($_POST['id_employee']);
    $id_type = intval($_POST['id_type']);

    if ($from_account === $to_account) {
        $message = "<p style='color:red; text-align:center;'>Не можете да превеждате към същата сметка.</p>";
    } elseif ($amount <= 0) {
        $message = "<p style='color:red; text-align:center;'>Невалидна сума.</p>";
    } elseif (!$id_employee) {
        $message = "<p style='color:red; text-align:center;'>Моля, изберете служител.</p>";
    } elseif (!$id_type) {
        $message = "<p style='color:red; text-align:center;'>Моля, изберете тип на превода.</p>";
    } else {
        $check = mysqli_query($dbConn, "SELECT availability_amount FROM Account WHERE id_account = $from_account");
        $balance = mysqli_fetch_assoc($check)['availability_amount'];

        if ($balance >= $amount) {
            $stmt = mysqli_prepare($dbConn, "
                INSERT INTO Transaction 
                    (id_employee, id_account, id_type, amount, id_account_affected, date_transaction, id_status)
                VALUES (?, ?, ?, ?, ?, NOW(), ?)
            ");
            $id_status = 3; // Изчаква одобрение
            mysqli_stmt_bind_param($stmt, "iiidii", $id_employee, $from_account, $id_type, $amount, $to_account, $id_status);
            $success = mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);

            if ($success) {
                $balance_after = $balance - $amount;
                $message = "<p style='color:green; text-align:center;'>
                    Транзакцията е подадена успешно. Служител ще я обработи.<br>
                    Ако бъде одобрена, наличността ще бъде: " . number_format($balance_after, 2, '.', '') . " лв.
                </p>";
            } else {
                $message = "<p style='color:red; text-align:center;'>Грешка при създаване на транзакцията.</p>";
            }
        } else {
            $message = "<p style='color:red; text-align:center;'>Недостатъчна наличност.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Превод между сметки</title>
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
       <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link" href="add_new_employee.php">Впиши нов служител</a>
    <a class="nav-link" href="add_new_client.php">Вписване на нов клиент</a>
    <a class="nav-link" href="client_transactions.php">Справки за транзакции</a>
    <a class="nav-link" href="confirmation_requests.php">Заявки за потвърждение</a>
    <a class="nav-link active" href="transfer_money.php">Преводи</a>
    <a class="nav-link" href="index.php">Изход от профила</a>
</nav>

<h2 style="text-align:center;">Превод между сметки</h2>

<?= $message ?>

<form method="post" id="transferForm">

    <label><h3>Служител, който въвежда превода:</h3></label>
    <select name="id_employee" required>
        <option value="">-- Избери --</option>
        <?php
        $employees = mysqli_query($dbConn, "
            SELECT E.id_employee, E.name_employee, P.position_type
            FROM Employee E
            JOIN Position P ON E.id_position = P.id_position
        ");
        while ($emp = mysqli_fetch_assoc($employees)) {
            echo "<option value='{$emp['id_employee']}'>[{$emp['position_type']}] {$emp['name_employee']}</option>";
        }
        ?>
    </select>

    <label><h3>Тип на превода:</h3></label>
    <select name="id_type" required>
        <option value="">-- Избери --</option>
        <?php
        $types = mysqli_query($dbConn, "SELECT id_type, type FROM TransactionType ORDER BY id_type");
        while ($row = mysqli_fetch_assoc($types)) {
            echo "<option value='{$row['id_type']}'>{$row['type']}</option>";
        }
        ?>
    </select>

    <label><h3>Сметка за теглене (от):</h3></label>
    <select name="from_account" id="from_account" required onchange="loadBalance()">
        <option value="">-- Избери --</option>
        <?php
        $accs = mysqli_query($dbConn, "
            SELECT A.id_account, A.number_account, C.name_client
            FROM Account A
            JOIN Client C ON A.id_client = C.id_client
        ");
        while ($acc = mysqli_fetch_assoc($accs)) {
            echo "<option value='{$acc['id_account']}'>[{$acc['name_client']}] {$acc['number_account']}</option>";
        }
        ?>
    </select>

    <div id="balance_info"></div>

    <label><h3>Сметка за получаване (до):</h3></label>
    <select name="to_account" required>
        <option value="">-- Избери --</option>
        <?php
        $accs2 = mysqli_query($dbConn, "
            SELECT A.id_account, A.number_account, C.name_client
            FROM Account A
            JOIN Client C ON A.id_client = C.id_client
        ");
        while ($acc = mysqli_fetch_assoc($accs2)) {
            echo "<option value='{$acc['id_account']}'>[{$acc['name_client']}] {$acc['number_account']}</option>";
        }
        ?>
    </select>

    <label><h3>Сума за превод:</h3></label>
    <input type="number" name="amount" step="0.01" required>

    <input type="submit" name="submit" value="Създай транзакция">
</form>

<script>
function loadBalance() {
    const accId = document.getElementById("from_account").value;
    const info = document.getElementById("balance_info");

    if (accId !== "") {
        fetch("transfer_money.php?get_balance=" + accId)
            .then(res => res.text())
            .then(data => {
                info.innerText = "Наличност: " + data + " лв.";
            })
            .catch(err => {
                info.innerText = "Грешка при зареждане на наличността.";
            });
    } else {
        info.innerText = "";
    }
}
</script>

</body>
</html>
