<?php
session_start();
include "configurate_database.php";

//  Добавяне на нов служител
if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $position = $_POST['position_type'];
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // криптирана парола

    $checkEmp = mysqli_query($dbConn, "SELECT * FROM Employee WHERE phone_employee='$phone'");
    $checkLog = mysqli_query($dbConn, "SELECT * FROM Logins WHERE username='$username'");

    if (!mysqli_fetch_assoc($checkEmp) && !mysqli_fetch_assoc($checkLog)) {
        mysqli_query($dbConn, "INSERT INTO Employee (name_employee, phone_employee, id_position)
                               VALUES ('$name','$phone','$position')");
        $employeeId = mysqli_insert_id($dbConn);
        mysqli_query($dbConn, "INSERT INTO Logins (username, password, id_employee)
                               VALUES ('$username','$password','$employeeId')");
        echo "<script>alert('Служителят е добавен успешно!'); window.location.href='add_new_employee.php';</script>";
        exit();
    } else {
        echo "<script>alert('Вече има служител с този телефон или потребителско име.');</script>";
    }
}

//  Обновяване на служител
if (isset($_POST['update_employee'])) {
    $id = $_POST['edit_id'];
    $name = $_POST['edit_name'];
    $username = $_POST['edit_username'];
    $phone = $_POST['edit_phone'];
    $position = $_POST['edit_position'];
    $password = password_hash($_POST['edit_password'], PASSWORD_DEFAULT);

    $sql1 = "UPDATE Employee SET name_employee='$name', phone_employee='$phone', id_position='$position' WHERE id_employee=$id";
    $sql2 = "UPDATE Logins SET username='$username', password='$password' WHERE id_employee=$id";

    if (mysqli_query($dbConn, $sql1) && mysqli_query($dbConn, $sql2)) {
        echo "<script>alert('Профилът е успешно обновен.'); window.location.href='add_new_employee.php';</script>";
        exit();
    } else {
        echo "<script>alert('Грешка при обновяване на профила.');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Добави нов профил</title>
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <script>
        function toggleUpdateForm() {
            const form = document.getElementById("updateForm");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
<nav id="nav_guests">
    <a class="nav-link " href="index_admin.php">Начало</a>
    <a class="nav-link active" href="add_new_employee.php">Въвеждане на нов служител</a>
    <a class="nav-link " href="visualize_employee.php">Списък със служители</a>
    <a class="nav-link " href="delete_employee.php">Изтриване на служител</a>
    <a class="nav-icon" href="index.php"><img src="images/admin.png" alt="Изход от профила" width="35px" height="35px"></a>
</nav>

<h2 class="heading">Добавяне на нов профил на служител:</h2>
<section id='group-section'>
    <form method="post">
        <div class="row">
            <div class="col-50">
                <h3>Име и фамилия:</h3><input type="text" name="name" required>
            </div>
            <div class="col-50">
                <h3>Потребителско име:</h3><input type="text" name="username" required>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <h3>Позиция:</h3>
                <select name="position_type" required>
                    <?php
                    $sql = "SELECT * FROM Position";
                    $result = mysqli_query($dbConn, $sql);
                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "<option value='{$row['id_position']}'>{$row['position_type']}</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-50">
                <h3>Парола:</h3><input type="password" name="password" required>
            </div>
        </div>
        <div class="row">
            <div class="col-50">
                <h3>Телефонен номер:</h3><input type="text" name="phone" required>
            </div>
        </div>
        <input type="submit" class="btn" name="submit" value="Добави профил">
    </form>

    <button type="button" class="btn" onclick="toggleUpdateForm()">Актуализирай профил на служител</button>

    <div id="updateForm" style="display: none; margin-top: 20px;">
        <form method="post">
            <label>Избери служител:</label>
            <select name="employee_id" required onchange="this.form.submit()">
                <option value="">-- Избери --</option>
                <?php
                $sql = "SELECT id_employee, name_employee FROM Employee";
                $result = mysqli_query($dbConn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    $selected = isset($_POST['employee_id']) && $_POST['employee_id'] == $row['id_employee'] ? "selected" : "";
                    echo "<option value='{$row['id_employee']}' $selected>{$row['name_employee']}</option>";
                }
                ?>
            </select>
        </form>

        <?php
        if (isset($_POST['employee_id'])) {
            $id = $_POST['employee_id'];
            $sql = "SELECT E.id_employee, E.name_employee, E.phone_employee, E.id_position, L.username, L.password 
                    FROM Employee E JOIN Logins L ON E.id_employee = L.id_employee WHERE E.id_employee = $id";
            $result = mysqli_query($dbConn, $sql);
            if ($row = mysqli_fetch_assoc($result)) {
        ?>
        <form method="post">
            <input type="hidden" name="edit_id" value="<?php echo $row['id_employee']; ?>">
            <h3>Име:</h3><input type="text" name="edit_name" value="<?php echo $row['name_employee']; ?>" required><br>
            <h3>Потребителско име:</h3><input type="text" name="edit_username" value="<?php echo $row['username']; ?>" required><br>
            <h3>Телефон:</h3><input type="text" name="edit_phone" value="<?php echo $row['phone_employee']; ?>" required><br>
            <h3>Позиция:</h3>
            <select name="edit_position" required>
                <?php
                $sql2 = "SELECT * FROM Position";
                $result2 = mysqli_query($dbConn, $sql2);
                while ($pos = mysqli_fetch_assoc($result2)) {
                    $selected = ($pos['id_position'] == $row['id_position']) ? "selected" : "";
                    echo "<option value='{$pos['id_position']}' $selected>{$pos['position_type']}</option>";
                }
                ?>
            </select><br>
            <h3>Парола:</h3><input type="text" name="edit_password" required><br>
            <input type="submit" name="update_employee" class="btn" value="Запази промените">
        </form>
        <?php }} ?>
    </div>

 