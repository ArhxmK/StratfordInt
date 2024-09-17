<?php
session_start();
define("PAGE_TITLE", "Admission Payment");
require "assets/dbh/connector.php";
require "assets/includes/header.php";
require "assets/components/navbar.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admission Payment</title>
    <style>
        body {
            background-image: url('assets/img/sebanner2.png');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            margin: 0;
        }

        .payment-container {
            text-align: center;
            background-color: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .payment-button {
            display: block;
            width: 200px;
            padding: 15px;
            margin: 20px auto;
            border: none;
            border-radius: 12px;
            background-color: #000223;
            color: #fff;
            font-size: 16px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .payment-button:hover {
            background-color: #003366;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.2);
            transform: translateY(-2px);
        }

        .payment-button:active {
            background-color: #0001b0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
            transform: translateY(0);
        }

        .payment-field {
            margin: 20px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .payment-field input {
            padding: 10px;
            width: 100%;
            max-width: 300px;
            border: 1px solid #000223;
            border-radius: 5px;
            transition: border-color 0.3s ease;
        }

        .payment-field input:focus {
            border-color: #000223;
            outline: none;
        }

        .payment-field label {
            margin-bottom: 10px;
            font-size: 16px;
            font-weight: bold;
        }

        footer {
            background-color: #000223;
            margin-top: auto;
        }
    </style>
</head>
<body>
    <br><br>
    <div class="content-wrapper">
        <div class="payment-container">
            <h1>Admission Payment</h1>
            <form action="process_admissionpayment.php" method="POST" enctype="multipart/form-data">
    <div class="payment-field">
        <label for="admission-number">Student ID</label>
        <input type="text" id="admission-number" name="admission_number" required>
    </div>
    <div class="payment-field">
        <label for="amount">Amount (LKR)</label>
        <input type="number" id="amount" name="amount" required>
    </div>
    <div class="payment-field">
        <label for="mobile">Mobile No</label>
        <input type="text" id="mobile" name="mobile" required>
    </div>
    <div class="payment-field">
        <label for="payment-receipt">Attach Payment Receipt</label>
        <input type="file" id="payment-receipt" name="payment_receipt" accept="image/*" required>
    </div>
    <button type="submit" class="payment-button">Make Payment</button>
</form>
        </div>
    </div>
    <br><br>
    <footer>
        <?php
        require "assets/includes/footer.php";
        require "flask_chatbot/chatbot.php";
        ?>
    </footer>
</body>
</html>
