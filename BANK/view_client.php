<?php
session_start();
include "configurate_database.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Списък с клиенти</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <style>
        table {
            width: 90%;
            margin: 20px auto;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #999;
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        .btn {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
            border-radius: 4px;
        }
        .btn:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link active" href="view_client.php">Списък с клиенти</a>
     <a class="nav-link " href="edit_client.php">Промяна на клиенти</a>
    <a class="nav-link" href="add_new_client.php">Добави клиент</a>
</nav>

<h2 class="heading">Списък с клиенти</h2>

<?php
$sql = "SELECT * FROM Client ORDER BY id_client DESC";
$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Име</th>
                <th>ЕГН</th>
                <th>Телефон</th>
                <th>Адрес</th>
                <th>Действие</th>
            </tr>";
    
   while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['id_client']}</td>
            <td>{$row['name_client']}</td>
            <td>{$row['egn']}</td>
            <td>{$row['phone_client']}</td>
            <td>{$row['adress']}</td>
            <td>
                <form method='get' action='edit_client.php' style='display:inline;'>
                 <input type='hidden' name='id' value='{$row['id_client']}'>
                    <input type='submit' value='Редактирай / Изтрий' class='btn'>
                </form>
            </td>
          </tr>";
}


    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма добавени клиенти.</p>";
}
?>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>
</body>
</html>
