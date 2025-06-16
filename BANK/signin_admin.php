<?php
     session_start(); 
     
?>

<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <title>Влез като администратор</title>
        <link rel="stylesheet" href="navbar.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <nav id="nav_guests">
     <a class="nav-link" href="index.php">Начало</a>
       <a class="nav-link active " href="signin_admin.php">Вписване като администратор</a>
     <a class="nav-link " href="signin_employee.php">Вписване като служител</a>
    <a class="nav-link" href="create_account.php">Отваряне на нова сметка</a>
    <a class="nav-link" href="about.php">За нас</a></div>
        <div class="container-cart">
        <a class="nav-icon" href="signin_admin.php"><img src="images/admin.png" alt="Административен профил" width="35px" height = "35px"></a>
    </nav>
        
        <div class="form-heading">
            Впиши се като админ
        </div>
        <form action="#" method="post" class="login-form">
            <label> Потребителско име: </label>
            <input type="text" name="username" required/><br>
            <label>Парола:</label>
            <input type="password" name="password" required />
            <input class="btn" type="submit" name="submit" value="Влез" /><br>
        </form>
<?php
	include "configurate_database.php";    
    if (isset($_POST["submit"])){
        $username =$_POST['username'];
        $password = $_POST['password'];

        $sql = "SELECT id_employee FROM LOGINS WHERE USERNAME='$username' AND PASSWORD = '$password' ";
        if($sql_user=mysqli_fetch_assoc(mysqli_query($dbConn, $sql))){
            $_SESSION["id_user"] = $sql_user['id_employee']; /* запазваме в сесията ид-на потребителя*/
            header("Location: index_admin.php");
            exit();
        }
        else {
            echo '<script src="script.js"></script>';
            echo "<script>addNewSection('Неправилно потребителско име или парола!');</script>";
        }
    }
?>
</body>
</html>