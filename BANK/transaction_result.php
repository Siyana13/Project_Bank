<?php
    session_start();
    /*ТОВА МОЖЕ ПРИ ИЗВЪРШВАНЕ НА ПРЕВОД ОТ КЛИЕНТИ - КАТО СТРАНИЦА ЗА УСПЕХ И ДА ИМА ТЕКСТ - ОЧАКВА СЕ ПОТВЪРЖДЕНИЕ ОТ НАШ СЛУЖИТЕЛ*/
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
        <link rel="stylesheet" href="navbar.css">
        <link rel="stylesheet" href="style.css">
        <title>Успешна поръчка</title>
    </head>
    <body>
    <nav id="nav_guests">
                <a class="nav-link" href="index.php">Начало</a>
                <a class="nav-link" href="products_list.php">Всички продукти</a>
                <a class="nav-link active" href="find_order.php">Информация за поръчка</a>
                <a class="nav-link" href="about.php">За нас</a></div>
                <div class="container-cart">
                    <a class="nav-icon" href="cart.php"><img src="images/cart.png" alt="Кошница" width="35px" height = "35px">
                    <p class="cart-index"><?php echo $_SESSION["cart_items"]; ?></p>
                    </a>
                </div>
                <a class="nav-icon" href="signin_admin.php"><img src="images/admin.png" alt="Влез като собсвеник" width="35px" height = "35px"></a>
        </nav>
        <div class="product-search-form">
            <h2 class="heading">Успешно направена поръчка с номер <?php echo $_SESSION['id_order'];?> </h2>
            <span class='line-between-text'> </span>
            <h3> Детайли може да намерите в раздела <a href="find_order.php">Информация за поръчка</a>. На посочения от вас имейл е изпратено съобщение с кода на поръчката.</h3>
        </div>
        <?php
            include "send_mail.php";
            //sendMail($_SESSION['deliveryInfo']['buyer']['email'],$_SESSION['id_order']);
            //include "init_session_vars.php";
        ?>
    </body>
</html>
