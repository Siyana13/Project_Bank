<?php/

     if (session_status() === PHP_SESSION_NONE) {
        session_start();
     }
     
     if(!isset($_SESSION["cart_number"])){
        $sql = "SELECT * FROM cart  ORDER BY id_cart DESC Limit 1";
        $row=mysqli_fetch_assoc(mysqli_query($dbConn,$sql));
        $_SESSION["cart_number"] = $row['id_cart']+1;
        $_SESSION["cart_items"] = 0;
     }
        $_SESSION['paymentInfo'] = array(
            "id_payment_type" => null,
            "id_card_type" => null,
            "card_number" => null,
            "expires" => null,
            "cvv" =>null);
        $_SESSION['deliveryInfo'] = array(
            "buyer" =>  array(
                "name" => null,
                "phone" => null,
                "email" => null),
            "address" => null,
            "id_type" => null,
            "id_delively_company" => null);
        $_SESSION['promo_code']=null;
?>