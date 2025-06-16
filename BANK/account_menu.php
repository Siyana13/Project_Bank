<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <title>Управление на сметки</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="navbar.css">
    <style>
        body {
            background-color:# #98c1e1;;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .menu-container {
            margin-top: 50px;
        }
        .account-button {
            display: inline-block;
            padding: 12px 24px;
            margin: 10px;
            background-color: #98c1e1;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            transition: 0.3s;
        }
        .account-button:hover {
            background-color: #98c1e1;
        }
    </style>
</head>
<body>

<nav id="nav_guests">
    <a class="nav-link" href="index_admin.php">Начало</a>
    <a class="nav-link active" href="account_menu.php">Управление на сметки</a>
    <a class="nav-link" href="transfer_money.php">Преводи</a>
    <a class="nav-link" href="confirmation_requests.php">Заявки за потвърждение</a>
    <a class="nav-link" href="index.php">Изход</a>
</nav>

<h2>Управление на клиентски сметки</h2>

<div class="menu-container">
    <a class="account-button" href="view_account.php">Преглед на всички сметки</a>
    <a class="account-button" href="delete_account.php">Изтриване на сметка</a>
    <a class="account-button" href="update_account.php">Актуализиране на сметка</a>
</div>

</body>
</html>
