<?php
include "configurate_database.php";
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Всички сметки</title>
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
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
<nav id="nav_guests">
        <a class="nav-link " href="index.php">Начало</a>
        <a class="nav-link" href="create_account.php">Създаване на сметка</a>
          <a class="nav-link active" href="view_account.php">Преглед на всички сметки</a>
        <a class="nav-link" href="delete_account.php">Изтриване на сметка</a>
         <a class="nav-link" href="update_account.php">Актуализиране на сметка</a>
    </nav>    
<header>
    <h2>Списък с всички банкови сметки</h2>
</header>

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
                <th>ID на сметка</th>
                <th>Номер на сметка</th>
                <th>Клиент</th>
                <th>Лихва (%)</th>
                <th>Наличност</th>
                <th>Валута</th>
            </tr>";

    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_account']}</td>
                <td>{$row['number_account']}</td>
                <td>{$row['name_client']}</td>
                <td>" . number_format($row['interest'], 2) . "</td>
                <td>" . number_format($row['availability_amount'], 2) . "</td>
                <td>{$row['currency']}</td>
              </tr>";
    }

    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма налични сметки в системата.</p>";
}
?>

<footer>
    <p style="text-align:center;">&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
