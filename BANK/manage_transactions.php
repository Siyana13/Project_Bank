<?php
session_start();
include "configurate_database.php";
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Одобрени и отхвърлени транзакции</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 12px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link" href="client_transactions.php">Заявка за потвърждаване</a>
    <a class="nav-link active" href="manage_transactions.php">Одобрени/Отхвърлени транзакции</a>
</nav>

<h2 class="heading">Списък на одобрени и отхвърлени транзакции</h2>

<?php
$sql = "
SELECT 
    T.id_transaction,
    C.name_client,
    TT.type AS transaction_type,
    T.amount,
    T.id_account,
    T.id_account_affected,
    T.date_transaction,
    TS.type_status AS status
FROM `Transaction` T
JOIN Account A ON T.id_account = A.id_account
JOIN Client C ON A.id_client = C.id_client
JOIN TransactionType TT ON T.id_type = TT.id_type
JOIN transaction_states TS ON T.id_status = TS.id_status
WHERE T.id_status IN (1, 2)
ORDER BY T.date_transaction DESC
";

$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Тип транзакция</th>
                <th>Сума</th>
                <th>От сметка</th>
                <th>Към сметка</th>
                <th>Дата</th>
                <th>Статус</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_transaction']}</td>
                <td>{$row['name_client']}</td>
                <td>{$row['transaction_type']}</td>
                <td>" . number_format($row['amount'], 2) . "</td>
                <td>{$row['id_account']}</td>
                <td>" . ($row['id_account_affected'] ?? '-') . "</td>
                <td>{$row['date_transaction']}</td>
                <td>{$row['status']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма одобрени или отхвърлени транзакции.</p>";
}
?>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
