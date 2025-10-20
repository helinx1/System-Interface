<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit();
}

include 'db_connect.php';

$customer_id = $_SESSION['customer_id'];
$restaurant_id = $_SESSION['restaurant_id'];
$reservation_date = $_SESSION['reservation_date'];
$reservation_time = $_SESSION['reservation_time'];
$number_of_people = $_SESSION['number_of_people'];
$parking_required = $_SESSION['parking_required'];
$dietary_needs = $_SESSION['dietary_needs'];
$discount_type = $_SESSION['discount_type'];
$order_details = $_SESSION['order_details'] ?? [];

// Calculate total
$total_amount = 0;
foreach ($order_details as $item) {
  $total_amount += $item['price'] * $item['quantity'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // If user clicked Cancel, handle it first to avoid processing payment
  if (isset($_POST['cancel'])) {
    unset($_SESSION['restaurant_id']);
    unset($_SESSION['reservation_date']);
    unset($_SESSION['reservation_time']);
    unset($_SESSION['number_of_people']);
    unset($_SESSION['parking_required']);
    unset($_SESSION['dietary_needs']);
    unset($_SESSION['discount_type']);
    unset($_SESSION['selected_items']);
    unset($_SESSION['order_details']);
    header("Location: home.php");
    exit();
  }

  if (isset($_POST['method_of_payment'])) {
    $method_of_payment = $_POST['method_of_payment'];

    // Insert into reservation
    $stmt = $conn->prepare("INSERT INTO reservation (customer_id, restaurant_id, reservation_date, reservation_time, number_of_people, dietary_needs, parking_required, discount_type) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissisis", $customer_id, $restaurant_id, $reservation_date, $reservation_time, $number_of_people, $dietary_needs, $parking_required, $discount_type);
    $stmt->execute();
    $reservation_id = $stmt->insert_id;
    $stmt->close();

    // Insert into orders
    $stmt = $conn->prepare("INSERT INTO orders (reservation_id) VALUES (?)");
    $stmt->bind_param("i", $reservation_id);
    $stmt->execute();
    $order_id = $stmt->insert_id;
    $stmt->close();

    // Insert into order_details
    $stmt = $conn->prepare("INSERT INTO order_details (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($order_details as $item) {
      $stmt->bind_param("iiid", $order_id, $item['item_id'], $item['quantity'], $item['price']);
      $stmt->execute();
    }
    $stmt->close();

    // Insert into payment
    $stmt = $conn->prepare("INSERT INTO payment (order_id, total_amount, method_of_payment) VALUES (?, ?, ?)");
    $stmt->bind_param("ids", $order_id, $total_amount, $method_of_payment);
    $stmt->execute();
    $stmt->close();

    // Clear temporary data
    unset($_SESSION['restaurant_id']);
    unset($_SESSION['reservation_date']);
    unset($_SESSION['reservation_time']);
    unset($_SESSION['number_of_people']);
    unset($_SESSION['parking_required']);
    unset($_SESSION['dietary_needs']);
    unset($_SESSION['discount_type']);
    unset($_SESSION['selected_items']);
    unset($_SESSION['order_details']);

    echo "<script>alert('Thank you for purchasing!'); window.location.href='home.php';</script>";
    exit();
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Eat To Go</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
    body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background: url("images/bg3.jpg") no-repeat center center fixed;
        background-size: cover;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        color: #fff;
    }


    .payment-container {
        position: relative;
        width: clamp(320px, 92%, 560px);
        padding: 34px;
        border-radius: 16px;
        color: #fff;
        text-align: left;

        background: linear-gradient(135deg, rgba(255, 255, 255, 0.08), rgba(255, 255, 255, 0.03));
        border: 1px solid rgba(255, 255, 255, 0.16);
        -webkit-backdrop-filter: blur(18px) saturate(140%);
        backdrop-filter: blur(18px) saturate(140%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.02);
        overflow: hidden;
        isolation: isolate;
        max-height: 80vh;
    }

    .payment-container::before {
        content: "";
        position: absolute;
        left: -30%;
        top: -40%;
        width: 160%;
        height: 120%;
        background: radial-gradient(600px 300px at 20% 20%, rgba(255, 255, 255, 0.12), rgba(255, 255, 255, 0) 30%);
        transform: rotate(-12deg);
        pointer-events: none;
        mix-blend-mode: overlay;
        opacity: 0.9;
    }

    .payment-container::after {
        content: "";
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 20%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(0, 0, 0, 0.12));
        pointer-events: none;
    }

    h2 {
        text-align: center;
        margin-bottom: 18px;
        font-size: 1.6rem;
        color: #ffd166;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
        letter-spacing: 0.2px;
    }

    .order-summary {
        margin-bottom: 20px;
    }

    .order-item {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }

    .total {
        font-weight: bold;
        font-size: 1.2rem;
        text-align: right;
        margin-top: 15px;
    }

    .payment-options {
        margin-top: 20px;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        gap: 10px;
    }

    .buttons button {
        flex: 1;
        padding: 12px;
        background: #ffd166;
        border: none;
        border-radius: 20px;
        font-size: 1rem;
        font-weight: bold;
        cursor: pointer;
        transition: 0.3s;
    }

    .buttons button:hover {
        background: #ffca3a;
    }
    </style>
</head>

<body>
    <div class="payment-container">
        <h2>Payment</h2>

        <div class="order-summary">
            <?php foreach ($order_details as $item): ?>
            <div class="order-item">
                <span><?php echo htmlspecialchars($item['item_name']); ?> x <?php echo $item['quantity']; ?></span>
                <span>₱<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
            </div>
            <?php endforeach; ?>
            <div class="total">Total: ₱<?php echo number_format($total_amount, 2); ?></div>
        </div>

        <form action="" method="POST">
            <div class="payment-options">
                <label><input type="radio" name="method_of_payment" value="Cash" required> Cash</label><br>
                <label><input type="radio" name="method_of_payment" value="E-wallet"> E-wallet</label><br>
                <label><input type="radio" name="method_of_payment" value="Credit Card"> Credit Card</label>
            </div>

            <div class="buttons">
                <button type="submit" name="cancel" formnovalidate>Cancel</button>
                <button type="submit">OK</button>
            </div>
        </form>
    </div>
</body>

</html>