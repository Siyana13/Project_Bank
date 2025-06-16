<?php
session_start();
include "configurate_database.php";

$message = "";
$client = null;

// Зареждане по ID от GET (напр. от view_clients.php?id=...)
if (isset($_GET['id'])) {
    $client_id = intval($_GET['id']);
    $res = mysqli_query($dbConn, "SELECT * FROM Client WHERE id_client = $client_id");
    $client = mysqli_fetch_assoc($res);
}

// Обработка на формата за обновяване
if (isset($_POST['update_client'])) {
    $id = intval($_POST['id_client']);
    $name = trim($_POST['name']);
    $egn = trim($_POST['egn']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    if ($name && $egn && $phone && $address) {
        $stmt = mysqli_prepare($dbConn, "UPDATE Client SET name_client = ?, egn = ?, phone_client = ?, adress = ? WHERE id_client = ?");
        mysqli_stmt_bind_param($stmt, "ssssi", $name, $egn, $phone, $address, $id);
        $success = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        if ($success) {
            $message = "<p style='color:green; text-align:center;'>Данните на клиента бяха успешно обновени.</p>";
            $res = mysqli_query($dbConn, "SELECT * FROM Client WHERE id_client = $id");
            $client = mysqli_fetch_assoc($res);
        } else {
            $message = "<p style='color:red; text-align:center;'>Възникна грешка при обновяването.</p>";
        }
    } else {
        $message = "<p style='color:red; text-align:center;'>Всички полета са задължителни.</p>";
    }
}

// Изтриване на клиента
if (isset($_POST['delete_client'])) {
    $id = intval($_POST['id_client']);
    $del = mysqli_query($dbConn, "DELETE FROM Client WHERE id_client = $id");

    if ($del) {
        $message = "<p style='color:green; text-align:center;'>Клиентът беше изтрит успешно.</p>";
        $client = null;
    } else {
        $message = "<p style='color:red; text-align:center;'>Грешка при изтриване на клиента.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Редактиране на клиент</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        form {
            width: 500px;
            margin: 30px auto;
            background: #ffffff;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
        }
        input[type="submit"] {
            background-color: #2980b9;
            color: white;
            border: none;
        }
        input[type="submit"]:hover {
            background-color: #1f6694;
        }
        .delete-btn {
            background-color: #e74c3c;
            color: white;
            border: none;
            margin-top: 10px;
        }
        .delete-btn:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link" href="view_client.php">Списък с клиенти</a>
    <a class="nav-link active" href="edit_client.php">Редакция на клиент</a>
</nav>

<h2 style="text-align:center;">Редактиране на данни на клиент</h2>

<?= $message ?>

<?php if ($client): ?>
<form method="post">
    <input type="hidden" name="id_client" value="<?= $client['id_client'] ?>">

    <label><h3>Име:</h3></label>
    <input type="text" name="name" value="<?= htmlspecialchars($client['name_client']) ?>" required>

    <label><h3>ЕГН:</h3></label>
    <input type="text" name="egn" value="<?= htmlspecialchars($client['egn']) ?>" pattern="\d{10}" required>

    <label><h3>Телефон:</h3></label>
    <input type="text" name="phone" value="<?= htmlspecialchars($client['phone_client']) ?>" pattern="\d{10}" required>

    <label><h3>Адрес:</h3></label>
    <input type="text" name="address" value="<?= htmlspecialchars($client['adress']) ?>" required>

    <input type="submit" name="update_client" value="Обнови клиента">

    <input type="submit" name="delete_client" value="Изтрий клиента" class="delete-btn"
           onclick="return confirm('Сигурни ли сте, че искате да изтриете този клиент?');">
</form>
<?php else: ?>
<p style="text-align:center;">Не е избран клиент за редакция.</p>
<?php endif; ?>

<footer style="text-align:center; margin-top: 30px;">
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>

</body>
</html>
