<?php
include "configurate_database.php";
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Всички служители</title>
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
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
    </style>
</head>
<body>
<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link active" href="view_employees.php">Списък със служители</a>
    <a class="nav-link" href="add_new_employee.php">Добави профил на служител</a>
    <a class="nav-link" href="delete_employee.php">Изтриване на служител</a>

</nav>

<h2 class="heading">Списък на всички служители</h2>

<?php
$sql = "SELECT E.id_employee, E.name_employee, E.phone_employee, P.position_type
        FROM Employee E
        JOIN Position P ON E.id_position = P.id_position
        ORDER BY E.id_employee ASC";

$result = mysqli_query($dbConn, $sql);

if (mysqli_num_rows($result) > 0) {
    echo "<table>
            <tr>
                <th>ID</th>
                <th>Име и фамилия</th>
                <th>Телефон</th>
                <th>Позиция</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_employee']}</td>
                <td>{$row['name_employee']}</td>
                <td>{$row['phone_employee']}</td>
                <td>{$row['position_type']}</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма служители в системата.</p>";
}
?>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>
</body>
</html>
