
<?php
include "configurate_database.php";
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Транзакции на клиент</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <style>
        form, table {
            width: 90%;
            margin: 20px auto;
        }
        select, input[type="submit"] {
            padding: 8px;
            font-size: 16px;
        }
        table {
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
                <a class="nav-link" href="index_admin.php">Начало</a>
                <a class="nav-link" href="manage_transactions.php">Одобрени/неодобрени транзакции</a>
    </nav>

<h2 style="text-align:center;">Справка за транзакции на клиент</h2>

<form method="post">
    <label for="client_id"><h3>Изберете клиент:</h3></label>
    <select name="client_id" id="client_id" required>
        <option value="">-- Изберете --</option>
        <?php
        $res = mysqli_query($dbConn, "SELECT id_client, name_client FROM Client ORDER BY name_client");
        while ($row = mysqli_fetch_assoc($res)) {
            $selected = isset($_POST['client_id']) && $_POST['client_id'] == $row['id_client'] ? 'selected' : '';
            echo "<option value='{$row['id_client']}' $selected>{$row['name_client']}</option>";
        }
        ?>
    </select>
    <input type="submit" name="submit" value="Покажи транзакции">
</form>

<?php
if (isset($_POST['submit'])) {
    $client_id = intval($_POST['client_id']);

    $query = "
    SELECT 
        T.id_transaction,
        T.amount,
        T.date_transaction,
        T.id_account,
        T.id_account_affected,
        TT.type AS type_name,
        TS.type_status AS status
    FROM `Transaction` T
    JOIN Account A ON T.id_account = A.id_account
    JOIN TransactionType TT ON T.id_type = TT.id_type
    JOIN Transaction_States TS ON T.id_status = TS.id_status
    WHERE A.id_client = $client_id
    ORDER BY T.date_transaction DESC";

    $result = mysqli_query($dbConn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<table>
                <tr>
                    <th>ID</th>
                    <th>Тип</th>
                    <th>Сума</th>
                    <th>От сметка</th>
                    <th>Към сметка</th>
                    <th>Дата</th>
                    <th>Статус</th>
                </tr>";
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$row['id_transaction']}</td>
                    <td>{$row['type_name']}</td>
                    <td>" . number_format($row['amount'], 2) . "</td>
                    <td>{$row['id_account']}</td>
                    <td>" . ($row['id_account_affected'] ?? '-') . "</td>
                    <td>{$row['date_transaction']}</td>
                    <td>{$row['status']}</td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='text-align:center;'>Няма транзакции за този клиент.</p>";
    }
}
?>

</body>
</html>
