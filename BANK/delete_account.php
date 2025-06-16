<?php
include "configurate_database.php";

// Ако е подадено ID за изтриване
if (isset($_GET['delete_id'])) {
    $accountId = intval($_GET['delete_id']);

    // Изтриване на всички транзакции, свързани с тази сметка (ако има)
    mysqli_query($dbConn, "DELETE FROM Transaction WHERE id_account = $accountId OR id_account_affected = $accountId");

    // Изтриване на самата сметка
    $deleteResult = mysqli_query($dbConn, "DELETE FROM Account WHERE id_account = $accountId");

    if ($deleteResult) {
        echo "<p style='text-align:center; color:green;'>Сметката беше успешно изтрита.</p>";
    } else {
        echo "<p style='text-align:center; color:red;'>Възникна грешка при изтриването на сметката.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Управление на сметки</title>
    <link rel="stylesheet" href="style.css">
     <link rel="stylesheet" href="navbar.css">
    <style>
        table {
            width: 90%;
            margin: 30px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #bbb;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #98c1e1;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
            padding: 6px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
<nav id="nav_guests">
        <a class="nav-link active" href="index.php">Начало</a>
        <a class="nav-link" href="create_account.php">Създаване на сметка</a>
         <a class="nav-link" href="update_account.php">Актуализиране на сметка</a>
    </nav> 
<h2 style="text-align:center;">Списък с всички сметки</h2>

<?php
$sql = "
SELECT 
    A.id_account,
    A.number_account,
    C.name_client,
    A.interest,
    A.availability_amount,
    CU.currency
FROM Account A
JOIN Client C ON A.id_client = C.id_client
JOIN Currency CU ON A.id_currency = CU.id_currency
ORDER BY A.id_account ASC
";

$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Номер на сметка</th>
                <th>Клиент</th>
                <th>Лихва</th>
                <th>Наличност</th>
                <th>Валута</th>
                <th>Действие</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_account']}</td>
                <td>{$row['number_account']}</td>
                <td>{$row['name_client']}</td>
                <td>" . number_format($row['interest'], 2) . "</td>
                <td>" . number_format($row['availability_amount'], 2) . "</td>
                <td>{$row['currency']}</td>
                <td>
                    <a class='btn-delete' href='delete_account.php?delete_id={$row['id_account']}' onclick=\"return confirm('Сигурни ли сте, че искате да изтриете тази сметка?');\">Изтрий</a>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма налични сметки.</p>";
}
?>

<footer>
    <p style="text-align:center;">&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
