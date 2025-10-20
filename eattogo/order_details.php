<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit();
}

include 'db_connect.php';

// If no selected items, redirect back to menu
if (!isset($_SESSION['selected_items']) || empty($_SESSION['selected_items'])) {
  header("Location: menu.php");
  exit();
}

$selected_items = $_SESSION['selected_items'];
$menu_details = [];

// Fetch menu details for selected items
$placeholders = implode(',', array_fill(0, count($selected_items), '?'));
$types = str_repeat('i', count($selected_items));

$stmt = $conn->prepare("SELECT item_id, item_name, price FROM menu WHERE item_id IN ($placeholders)");
$stmt->bind_param($types, ...$selected_items);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
  $menu_details[] = $row;
}
$stmt->close();

// When submitted, save quantities
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $quantities = $_POST['quantity'] ?? [];
  $_SESSION['order_details'] = [];

  foreach ($menu_details as $item) {
    $id = $item['item_id'];
    $qty = isset($quantities[$id]) ? max(1, (int)$quantities[$id]) : 1;
    $_SESSION['order_details'][] = [
      'item_id' => $id,
      'item_name' => $item['item_name'],
      'price' => $item['price'],
      'quantity' => $qty
    ];
  }

  header("Location: payment.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Details - Eat To Go</title>
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

    .order-container {
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

    .order-container::before {
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

    .order-container::after {
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

    .order-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 15px;
    }

    .order-item span {
        flex: 1;
    }

    .order-item input {
        width: 60px;
        padding: 5px;
        border: none;
        border-radius: 8px;
        text-align: center;
        font-size: 1rem;
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
    <div class="order-container">
        <h2>Order Details</h2>
        <form action="" method="POST">
            <?php foreach ($menu_details as $item): ?>
            <div class="order-item">
                <span><?php echo htmlspecialchars($item['item_name']); ?>
                    (₱<?php echo number_format($item['price'], 2); ?>)</span>
                <input type="number" name="quantity[<?php echo $item['item_id']; ?>]" value="1" min="1">
            </div>
            <?php endforeach; ?>

            <div class="buttons">
                <button type="button" onclick="window.location.href='menu.php'">Back</button>
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
</body>

</html>