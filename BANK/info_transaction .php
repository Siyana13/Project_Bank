<?php
     session_start();
     
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Информация за сметка</title>
        <link rel="stylesheet" href="navbar.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
    <nav id="nav_guests">
                <a class="nav-link" href="index.php">SBank</a>
                <a class="nav-link" href="products_list.php">Заяви нова услуга</a>
                <a class="nav-link active" href="find_order.php">Информация за сметка</a>
                <a class="nav-link" href="transaction.php">Направи превод</a>
                <a class="nav-link" href="about.php">За нас</a></div>
                <a class="nav-icon" href="signin_admin.php"><img src="images/admin.png" alt="Влез като собсвеник" width="35px" height = "35px"></a>
        </nav>
        <section id='container-replace-data'>
            <section id="find-deal">
                <h1 class='heading'>Информация за сметка</h1>
                <form action="" method="post" class="product-search-form" enctype="multipart/form-data">
                    <label>Номер на сметка</label>
                    <input type="text" name="DEAL_NUMBER"/><br>
                    <input class='btn' type='submit' name='find' value='Провери'>
                </form>
            </section>
        </section>
    <?php
        include "configurate_database.php";
        include "order_data.php";

        if (isset($_POST["find"])){
            $sql =" SELECT DL.*, CUR.INI AS CURR_INI, CL.*,DP.TITLE AS TITLE_PR, DT.TYPE AS TYPE_PRODUCT, CL.TYPE_ID AS CLIENT_TYPE_ID, CLT.TYPE AS CLT_TYPE
                    FROM DEALS AS DL
                    INNER JOIN CURRENCIES AS CUR
                        ON CUR.ID = DL.CURRENCY_ID  
                    INNER JOIN DEAL_PRODUCTS AS DP
                        ON DP.ID = DL.PRODUCT_ID 
                    INNER JOIN DEAL_TYPES AS DT 
                        ON DT.ID = DP.TYPE_ID
                    INNER JOIN CLIENTS AS CL
                        ON CL.ID = DL.CLIENT_ID  
                    INNER JOIN CLIENT_TYPES AS CLT
                        ON CLT.ID = CL.TYPE_ID
                    WHERE NUMBER='".$_POST['DEAL_NUMBER']."' ";

            if($deal = mysqli_fetch_array(mysqli_query($dbConn,$sql))){
                echo "<section id='group-section'>";
                    echo '<script src="script.js"></script>';
                    echo "<script>replaceSection();</script>";

                    echo "<h2 class='heading'>IBAN:".$deal['NUMBER']."</h2>";
                    echo "<h3>Информация за сметка</h3>";
                    echo "<div class='container-deal-data'>";
                        echo "<b>Тип продукт:</b> ". $deal['TITLE_PR'].", ".$deal['TYPE_PRODUCT']."<br>";
                        echo "<b>Наличност:</b> " .$deal['SALDO'].$deal['CURR_INI']."<br>";
                        echo "<b>Дата на отваряне:</b> " .$deal['DATE_OPEN']."<br>";
                        echo "<b>Дата на затваряне:</b> " . $deal['DATE_CLOSE']."<br>";
                    echo "</div>";

                    echo "<h3>Информация за клиент</h3>";
                    echo "<div class='container-deal-data'>";
                        echo "<b>Име:</b> " .$deal['NAME']."<br>";
                        echo "<b>Тип:</b> " .$deal['CLT_TYPE']."<br>";
                        if($deal['CLIENT_TYPE_ID'] == 1)
                            echo "<b>ЕГН:</b> ";
                        else echo "<b>БУЛСТАТ:</b> ";
                        echo $deal['IDENTIFIER']."<br>";
                        echo "<b>Телефонен номер:</b> ".$deal['PHONE_NUMBER']."<br>";
                        echo "<b>Адрес: \"</b> " . $deal['ADDRESS']."\"<br>";
                    echo "</div>";
                    echo "<form action='' method='post'><input class='btn' type='submit' name='getTransactions' value='Справка опeрации'></form>";
                }
                else 
                {
                    echo '<script src="script.js"></script>';
                    echo "<script>addNewSection('Не е открита партида с този номер!');</script>";
                }
                echo "</section>";
        }


    ?>
    <footer>
            <p>&copy; 2025 Sbank. Всички права запазени.</p>
            <a href="#"><img src="images/facebook.png" alt="facebook" width="35px" height = "35px"></a>
            <a href="#"><img src="images/instagram.png" alt="instagram" width="35px" height = "35px"></a>
            <a href="#"><img src="images/pinterest.png" alt="pinterest" width="35px" height = "35px"></a>    
        </footer>
    </body>
</html>