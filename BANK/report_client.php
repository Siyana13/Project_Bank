<?php
session_start();
include "configurate_database.php";

// Инициализация
$client = null;
$transactions = [];

if (isset($_POST['search'])) {
    $search = mysqli_real_escape_string($dbConn, $_POST['client_id']);

    // 🔎 Търси клиент по ID или Име
    $sqlClient = "SELECT * FROM Client WHERE id_client = '$search' OR name_client LIKE '%$search%'";
    $resClient = mysqli_query($dbConn, $sqlClient);

    if (mysqli_num_rows($resClient) == 1) {
        $client = mysqli_fetch_assoc($resClient);
        $client_id = $client['id_client'];

        // 🔄 Вземи всички транзакции
        $sqlTx = "
            SELECT 
                T.id_transaction,
                T.amount,
                T.date_transaction,
                T.id_account,
                T.id_account_affected,
                TT.type AS transaction_type,
                E.name_employee
            FROM `Transaction` T
            JOIN TransactionType TT ON T.id_type = TT.id_type
            JOIN Employee E ON T.id_employee = E.id_employee
            WHERE T.id_account IN (SELECT id_account FROM Account WHERE id_client = $client_id)
               OR T.id_account_affected IN (SELECT id_account FROM Account WHERE id_client = $client_id)
            ORDER BY T.date_transaction DESC";

        $transactions = mysqli_query($dbConn, $sqlTx);
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Справка за транзакции на клиент</title>
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #aaa;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        form {
            text-align: center;
            margin-top: 30px;
        }
        input[type="text"] {
            padding: 8px;
            width: 300px;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Админ панел</a>
    <a class="nav-link" href="view_clients.php">Клиенти</a>
    <a class="nav-link active" href="client_transactions_report.php">Справка по клиент</a>
</nav>

<h2 class="heading">Справка за транзакции на клиент</h2>

<form method="post">
    <label>Търси по ID или име:</label><br>
    <input type="text" name="client_id" required>
    <input type="submit" name="search" value="Покажи справка" class="btn">
</form>

<?php if ($client): ?>
    <h3 style="text-align:center;">Клиент: <?php echo $client['name_client']; ?> (ID: <?php echo $client['id_client']; ?>)</h3>

    <?php if (mysqli_num_rows($transactions) > 0): ?>
        <table>
            <tr>
                <th>ID</th>
                <th>Тип</th>
                <th>Сума</th>
                <th>От сметка</th>
                <th>Към сметка</th>
                <th>Служител</th>
                <th>Дата</th>
            </tr>
            <?php while ($tx = mysqli_fetch_assoc($transactions)): ?>
                <tr>
                    <td><?php echo $tx['id_transaction']; ?></td>
                    <td><?php echo $tx['transaction_type']; ?></td>
                    <td><?php echo number_format($tx['amount'], 2); ?></td>
                    <td><?php echo $tx['id_account']; ?></td>
                    <td><?php echo $tx['id_account_affected'] ?? '-'; ?></td>
                    <td><?php echo $tx['name_employee']; ?></td>
                    <td><?php echo $tx['date_transaction']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Няма транзакции за този клиент.</p>
    <?php endif; ?>
<?php elseif (isset($_POST['search'])): ?>
    <p style="text-align:center; color:red;">Клиентът не е намерен.</p>
<?php endif; ?>

<footer>
    <p style="text-align:center;">&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
