<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Payment Portal</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
      display: flex;
      align-items: center;
      justify-content: center;
      height: 100vh;
    }

    .payment-container {
      background-color: #fff;
      border-radius: 8px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      width: 300px;
      text-align: center;
    }

    .payment-options {
      display: flex;
      justify-content: space-around;
      margin-top: 20px;
    }

    .visa-logo {
      width: 50px;
    }

    .paypal-logo {
      width: 80px;
    }

    button {
      padding: 10px;
      background-color: #4caf50;
      color: #fff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
      font-size: 16px;
      margin-top: 20px;
    }
  </style>
</head>
<body>
  <div class="payment-container">
    <h2>Select Payment Method</h2>
    <div class="payment-options">
      <div>
        <img src="img/visacard.png" alt="Visa Logo" class="visa-logo" style="width: 80px;">
        <p>Visa Card</p>
      </div>
      <div>
        <img src="img/paypal.png" alt="PayPal Logo" class="paypal-logo">
        <p>PayPal</p>
      </div>
    </div>
    <h3>RM15 / month</h3>
    <button><a href="homepage.php" style="text-decoration:none; color:white;">Back</a></button>
    <button onclick="processPayment()">Proceed to Payment</button>
  </div>

  <script>
    function processPayment() {
      // Add logic to handle payment processing
      alert('Payment processed successfully!');
      header('Location: homepage.php');
    }
  </script>
</body>
</html>
