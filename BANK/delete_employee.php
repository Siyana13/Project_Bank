<?php
session_start();
include "configurate_database.php";

// Обработка на изтриване
if (isset($_POST['delete_id'])) {
    $id = intval($_POST['delete_id']);

    // Първо изтриваме от Logins (ако има запис)
    mysqli_query($dbConn, "DELETE FROM Logins WHERE id_employee = $id");

    // После от Employee
    $delete = mysqli_query($dbConn, "DELETE FROM Employee WHERE id_employee = $id");

    if ($delete) {
        echo "<script>alert('Служителят беше успешно изтрит.');</script>";
    } else {
        echo "<script>alert('Грешка при изтриване на служителя.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Изтриване на служители</title>
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
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #eee;
        }
        .btn-delete {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 6px 10px;
            cursor: pointer;
        }
        .btn-delete:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link" href="visualize_employee.php">Списък със служители</a>
    <a class="nav-link" href="add_new_employee.php">Добави профил на служител</a>
    <a class="nav-link active" href="delete_employee.php">Изтрий служител</a>
    <a class="nav-icon" href="index.php"><img src="images/admin.png" alt="Изход от профила" width="35px" height="35px"></a>
</nav>

<h2 class="heading">Изтриване на служители</h2>

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
                <th>Име</th>
                <th>Телефон</th>
                <th>Позиция</th>
                <th>Действие</th>
            </tr>";
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>
                <td>{$row['id_employee']}</td>
                <td>{$row['name_employee']}</td>
                <td>{$row['phone_employee']}</td>
                <td>{$row['position_type']}</td>
                <td>
                    <form method='post' onsubmit=\"return confirm('Наистина ли искате да изтриете този служител?');\">
                        <input type='hidden' name='delete_id' value='{$row['id_employee']}'>
                        <input type='submit' class='btn-delete' value='Изтрий'>
                    </form>
                </td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p style='text-align:center;'>Няма служители за показване.</p>";
}
?>

<footer>
    <p>&copy; 2025 SBank. Всички права запазени.</p>
</footer>
</body>
</html>
