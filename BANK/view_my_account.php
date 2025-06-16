<?php
include "configurate_database.php";

$step = 1; // Стъпка на формата
$clients = [];
$accounts = [];
$selected_client = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Стъпка 1: Търси клиенти по име
    if (isset($_POST['search_name'])) {
        $name = trim($_POST['client_name']);
        $stmt = mysqli_prepare($dbConn, "SELECT * FROM Client WHERE name_client = ?");
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        while ($row = mysqli_fetch_assoc($result)) {
            $clients[] = $row;
        }
        mysqli_stmt_close($stmt);

        if (count($clients) === 1) {
            // Само един клиент – директно показваме сметки
            $selected_client = $clients[0];
            $step = 3;
        } elseif (count($clients) > 1) {
            $step = 2;
        } else {
            $step = 1;
            $message = "Не са намерени клиенти с това име.";
        }
    }

    // Стъпка 2: Клиент е избран от падащо меню
    if (isset($_POST['select_client_id'])) {
        $client_id = intval($_POST['select_client_id']);
        $res = mysqli_query($dbConn, "SELECT * FROM Client WHERE id_client = $client_id");
        $selected_client = mysqli_fetch_assoc($res);
        $step = 3;
    }
}

if ($step === 3 && $selected_client) {
    $accounts_query = mysqli_query($dbConn, "
        SELECT A.*, C.currency 
        FROM Account A
        JOIN Currency C ON A.id_currency = C.id_currency
        WHERE A.id_client = {$selected_client['id_client']}
    ");
    while ($acc = mysqli_fetch_assoc($accounts_query)) {
        $accounts[] = $acc;
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Моите сметки</title>
    <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="navbar.css">
    <style>
        form, table { width: 90%; margin: 20px auto; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: center; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>

<nav id="nav_guests">
        <a class="nav-link " href="index.php">Начало</a>
        <a class="nav-link " href="create_account.php">Създаване на сметка</a>
          <a class="nav-link active " href="view_my_account.php">Преглед на сметка</a>
       
    </nav>    
<h2 style="text-align:center;">Преглед на моите сметки</h2>

<?php if (!empty($message)): ?>
    <p style="color:red; text-align:center;"><?= $message ?></p>
<?php endif; ?>

<?php if ($step === 1): ?>
    <form method="post">
        <label><h3>Въведете вашето име:</h3></label>
        <input type="text" name="client_name" required>
        <input type="submit" name="search_name" value="Провери">
    </form>
<?php endif; ?>

<?php if ($step === 2): ?>
    <form method="post">
        <label><h3>Изберете клиент (по ЕГН/телефон):</h3></label>
        <select name="select_client_id" required>
            <option value="">-- Избери --</option>
            <?php foreach ($clients as $cl): ?>
                <option value="<?= $cl['id_client'] ?>">
                    <?= $cl['name_client'] ?> | ЕГН: <?= $cl['egn'] ?> | Телефон: <?= $cl['phone_client'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="Покажи сметки">
    </form>
<?php endif; ?>

<?php if ($step === 3 && $selected_client): ?>
    <h3 style="text-align:center;">Сметки на <?= htmlspecialchars($selected_client['name_client']) ?></h3>

    <?php if (count($accounts) > 0): ?>
        <table>
            <tr>
                <th>Номер на сметка</th>
                <th>Налично</th>
                <th>Лихва</th>
                <th>Валута</th>
            </tr>
            <?php foreach ($accounts as $acc): ?>
                <tr>
                    <td><?= $acc['number_account'] ?></td>
                    <td><?= number_format($acc['availability_amount'], 2) ?></td>
                    <td><?= $acc['interest'] ?>%</td>
                    <td><?= $acc['currency'] ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Няма намерени сметки за този клиент.</p>
    <?php endif; ?>
<?php endif; ?>

</body>
</html>
