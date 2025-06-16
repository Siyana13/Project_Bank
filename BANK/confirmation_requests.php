<?php
session_start();
include "configurate_database.php";
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Потвърждение на транзакции</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 95%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #aaa;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .btn {
            padding: 6px 10px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
        }
        .approve { background-color: green; }
        .reject { background-color: crimson; }
    </style>
</head>
<body>
<nav id="nav_guests">
                <a class="nav-link" href="index_admin.php">Начало</a>
                 <a class="nav-link active" href="manage_transactions.php">Заявки за потвърждаване</a>
                <a class="nav-link" href="manage_transactions.php">Списък с одобрени/неодобрени транзакции</a>
                <a class="nav-link" href="index.php">Изход от профила</a></div>
    </nav>
<h2 style="text-align:center;">Транзакции в изчакване</h2>

<?php
// Обработка на формата за потвърждение/отказ
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['transaction_id'], $_POST['action'], $_POST['employee_id'])) {
    $transaction_id = intval($_POST['transaction_id']);
    $employee_id = intval($_POST['employee_id']);
    $new_status = $_POST['action'] === 'approve' ? 1 : 2;

    $update = "UPDATE `Transaction` SET id_status = $new_status, id_employee = $employee_id WHERE id_transaction = $transaction_id";
    if (mysqli_query($dbConn, $update)) {
        echo "<p style='color:green; text-align:center;'>Транзакцията #$transaction_id беше успешно обновена.</p>";
    } else {
        echo "<p style='color:red; text-align:center;'>Грешка при обновяване на транзакцията.</p>";
    }
}

// Вземане на всички служители
$employees = [];
$emp_result = mysqli_query($dbConn, "SELECT id_employee, name_employee FROM Employee");
while ($row = mysqli_fetch_assoc($emp_result)) {
    $employees[$row['id_employee']] = $row['name_employee'];
}

// Извличане на транзакции в изчакване
$sql = "
    SELECT T.id_transaction, T.amount, T.date_transaction, T.id_account, T.id_account_affected, TT.type AS transaction_type, C.name_client
    FROM `Transaction` T
    JOIN Account A ON T.id_account = A.id_account
    JOIN Client C ON A.id_client = C.id_client
    JOIN TransactionType TT ON T.id_type = TT.id_type
    WHERE T.id_status = 3
    ORDER BY T.date_transaction DESC
";
$res = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($res) > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Тип транзакция</th>
                <th>Сума</th>
                <th>От сметка</th>
                <th>Към сметка</th>
                <th>Дата</th>
                <th>Служител</th>
                <th>Действия</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($res)) {
        echo "<tr>
            <form method='post'>
                <td>{$row['id_transaction']}</td>
                <td>{$row['name_client']}</td>
                <td>{$row['transaction_type']}</td>
                <td>{$row['amount']}</td>
                <td>{$row['id_account']}</td>
                <td>" . ($row['id_account_affected'] ?? '-') . "</td>
                <td>{$row['date_transaction']}</td>
                <td>
                    <select name='employee_id' required>";
                        foreach ($employees as $id => $name)
                            echo "<option value='$id'>$name</option>";
        echo       "</select>
                </td>
                <td>
                    <input type='hidden' name='transaction_id' value='{$row['id_transaction']}'>
                    <button class='btn approve' type='submit' name='action' value='approve'>Одобри</button>
                    <button class='btn reject' type='submit' name='action' value='reject'>Отхвърли</button>
                </td>
            </form>
        </tr>";
    }

    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма транзакции за потвърждение.</p>";
}
?>

</body>
</html>
