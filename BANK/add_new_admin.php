<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="style.css">
    <title>Добави нов профил</title>
    <script>
        function toggleUpdateForm() {
            const form = document.getElementById("updateForm");
            form.style.display = form.style.display === "none" ? "block" : "none";
        }
    </script>
</head>
<body>
    <nav id="nav_guests">
        <a class="nav-link active" href="add_new_admin.php">Персонал</a>
        <a class="nav-link" href="add_new_client.php">Нов клиент</a>
        <a class="nav-link" href="product_list_admin.php">Нова сделка</a>
        <a class="nav-link" href="product_list_admin.php">Отвори сделка</a>
        <a class="nav-link" href="product_list_admin.php">Справка за операции</a>
        <a class="nav-icon" href="index.php"><img src="images/admin.png" alt="Изход от профила" width="35px" height="35px"></a>
        <a class="nav-icon" href="cart.php"><img src="images/confirm_icon.png" alt="Потвърди операция" width="35px" height="35px">
        <?php 
            include "configurate_database.php";
            $sql ="SELECT COUNT(STATE_ID) AS TOTAL_COUNT FROM TRANSACTIONS WHERE STATE_ID = 1";
            if($count = mysqli_fetch_array(mysqli_query($dbConn,$sql)))
                echo $count['TOTAL_COUNT']; 
        ?>
        </a>
    </nav>

    <h2 class="heading">Добавяне на нов профил на служител:</h2>
    <section id='group-section'>
        <form action="#" method="post">
            <h3>Данни на лице:</h3><br> 
            <div class="row">
                <div class="col-50">
                    Име и фамилия:<input type="text" name="name" required><br>
                </div>
                <div class="col-50">
                    Потребителско име:<input type="text" name="username" required><br>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    Позиция: 
                    <select name="position_type" required>
                        <?php 
                            $sql="SELECT * FROM POSITIONS";
                            $result=mysqli_query($dbConn, $sql);
                            while($row=mysqli_fetch_assoc($result))
                                echo "<option value='" . $row['ID'] . "'>" . $row['TYPE'] . "</option>";
                        ?>
                    </select><br>
                </div>
                <div class="col-50">
                    Парола:<input type="password" name="password" required>
                </div>
            </div>
            <div class="row">
                <div class="col-50">
                    Телефонен номер:<input type="text" name="phone" required><br>
                </div>
            </div>
            <input type="submit" class="btn" name="submit" value="Добави профил"><br>
        </form>

        <!-- Бутон за показване на форма за актуализация -->
        <button type="button" class="btn" onclick="toggleUpdateForm()">Актуализирай профил на служител</button>

        <!-- Форма за избор и редакция на служител -->
        <div id="updateForm" style="display: none; margin-top: 20px;">
            <form action="#" method="post">
                <label>Избери служител:</label>
                <select name="employee_id" required onchange="this.form.submit()">
                    <option value="">-- Избери --</option>
                    <?php 
                        $sql = "SELECT ID, NAME FROM EMPLOYEES";
                        $result = mysqli_query($dbConn, $sql);
                        while($row = mysqli_fetch_assoc($result)) {
                            $selected = isset($_POST['employee_id']) && $_POST['employee_id'] == $row['ID'] ? "selected" : "";
                            echo "<option value='" . $row['ID'] . "' $selected>" . $row['NAME'] . "</option>";
                        }
                    ?>
                </select>
            </form>

            <?php
            if (isset($_POST['employee_id'])) {
                $id = $_POST['employee_id'];
                $sql = "SELECT E.ID, E.NAME, E.PHONE, E.POSITIONS, L.USERNAME, L.PASSWORD 
                        FROM EMPLOYEES E 
                        JOIN LOGINS L ON E.ID = L.ID 
                        WHERE E.ID = $id";
                $result = mysqli_query($dbConn, $sql);
                if ($row = mysqli_fetch_assoc($result)) {
            ?>
            <form action="#" method="post">
                <input type="hidden" name="edit_id" value="<?php echo $row['ID']; ?>">
                Име и фамилия:<input type="text" name="edit_name" value="<?php echo $row['NAME']; ?>" required><br>
                Потребителско име:<input type="text" name="edit_username" value="<?php echo $row['USERNAME']; ?>" required><br>
                Телефонен номер:<input type="text" name="edit_phone" value="<?php echo $row['PHONE']; ?>" required><br>
                Позиция:
                <select name="edit_position" required>
                    <?php
                        $sql2 = "SELECT * FROM POSITIONS";
                        $result2 = mysqli_query($dbConn, $sql2);
                        while($pos = mysqli_fetch_assoc($result2)) {
                            $selected = ($pos['ID'] == $row['POSITIONS']) ? "selected" : "";
                            echo "<option value='" . $pos['ID'] . "' $selected>" . $pos['TYPE'] . "</option>";
                        }
                    ?>
                </select><br>
                Парола:<input type="text" name="edit_password" value="<?php echo $row['PASSWORD']; ?>" required><br>
                <input type="submit" name="update_employee" class="btn" value="Запази промените">
            </form>
            <?php }} ?>
        </div>
    </section>

<?php
include "configurate_database.php";

// Добавяне на нов служител
if (isset($_POST["submit"])) {
    $name = $_POST['name'];
    $phone= $_POST['phone'];
    $position= $_POST['position_type'];
    $username =$_POST['username'];
    $password = $_POST['password'];

    $sqlEmp = "SELECT * FROM EMPLOYEES WHERE PHONE='$phone'";
    $resultEmp = mysqli_query($dbConn, $sqlEmp);

    $sqlLog = "SELECT * FROM LOGINS WHERE USERNAME='$username' AND PASSWORD='$password'";
    $resultLog = mysqli_query($dbConn, $sqlLog);

    if (!mysqli_fetch_assoc($resultEmp) && !mysqli_fetch_assoc($resultLog)) {
        $sql = "INSERT INTO EMPLOYEES (NAME, PHONE, POSITIONS) VALUES ('$name','$phone', '$position')";
        mysqli_query($dbConn, $sql);

        // Вземи последното ID
        $employeeId = mysqli_insert_id($dbConn);

        $sql = "INSERT INTO LOGINS (ID, USERNAME, PASSWORD) VALUES ('$employeeId','$username','$password')";
        mysqli_query($dbConn, $sql);

        header("Location: add_new_admin.php");
        exit();
    } else {
        echo "<script>alert('Вече има служител с тези данни.');</script>";
    }
}

// Обновяване на данни на служител
if (isset($_POST['update_employee'])) {
    $id = $_POST['edit_id'];
    $name = $_POST['edit_name'];
    $username = $_POST['edit_username'];
    $phone = $_POST['edit_phone'];
    $position = $_POST['edit_position'];
    $password = $_POST['edit_password'];

    $sql1 = "UPDATE EMPLOYEES SET NAME='$name', PHONE='$phone', POSITIONS='$position' WHERE ID=$id";
    $sql2 = "UPDATE LOGINS SET USERNAME='$username', PASSWORD='$password' WHERE ID=$id";

    if (mysqli_query($dbConn, $sql1) && mysqli_query($dbConn, $sql2)) {
        echo "<script>alert('Профилът е успешно обновен.'); window.location.href='add_new_admin.php';</script>";
    } else {
        echo "<script>alert('Грешка при обновяване на профила.');</script>";
    }
}
?>
</body>
</html>
