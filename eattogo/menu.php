<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit();
}

include 'db_connect.php';

// Get restaurant ID from session
$restaurant_id = $_SESSION['restaurant_id'] ?? null;
$menu_items = [];

// Fetch menu items for this restaurant
if ($restaurant_id) {
    $stmt = $conn->prepare("SELECT * FROM menu WHERE restaurant_id = ?");
    $stmt->bind_param("i", $restaurant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $menu_items[] = $row;
    }
    $stmt->close();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $selected_items = $_POST['menu_items'] ?? [];
    $_SESSION['selected_items'] = $selected_items;
    header("Location: order_details.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu - Eat To Go</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
    body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background: url("images/bg3.jpg") no-repeat center center fixed;
        background-size: cover;
        height: 100vh;
        overflow-x: hidden;
        color: #fff;
        position: relative;
    }

    .menu-container {
        width: clamp(340px, 44%, 520px);
        max-width: 520px;
        padding: 26px 28px;
        border-radius: 16px;
        color: #fff;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.06), rgba(255, 255, 255, 0.02));
        border: 1px solid rgba(255, 255, 255, 0.12);
        -webkit-backdrop-filter: blur(18px) saturate(140%);
        backdrop-filter: blur(18px) saturate(140%);
        box-shadow: 0 14px 36px rgba(0, 0, 0, 0.45), inset 0 1px 0 rgba(255, 255, 255, 0.02);
        box-sizing: border-box;
        position: fixed;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        margin-top: -10px;
        isolation: isolate;
        max-height: min(460px, 80vh);
        overflow: hidden;
    }

    .menu-container::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .menu-container::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.06);
        border-radius: 4px;
    }

    .menu-container::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 4px;
        transition: background 0.2s ease;
    }

    .menu-container::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .menu-container::before {
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

    .menu-container::after {
        content: "";
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
        height: 16%;
        background: linear-gradient(180deg, rgba(255, 255, 255, 0.02), rgba(0, 0, 0, 0.12));
        pointer-events: none;
    }

    h2 {
        text-align: center;
        margin: 0 0 14px 0;
        font-size: 1.6rem;
        color: #ffd166;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.4);
        letter-spacing: 0.2px;
    }

    form {
        height: calc(100% - 80px);
        overflow-y: auto;
        padding-right: 4px;
    }

    .menu-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        transition: background-color 0.2s ease;
    }

    .menu-item:hover {
        background: rgba(255, 255, 255, 0.04);
    }

    .menu-item label {
        flex: 1;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.92);
    }

    .menu-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #ffd166;
    }

    .menu-item span {
        font-weight: 500;
        color: #ffd166;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 20px;
        gap: 10px;
        background: inherit;
        padding: 4px 0;
    }

    .buttons button {
        flex: 1;
        padding: 12px 14px;
        border-radius: 999px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        background: linear-gradient(90deg, rgba(255, 209, 102, 0.98), rgba(255, 180, 55, 0.95));
        color: #2a2a2a;
        box-shadow: 0 8px 20px rgba(255, 160, 30, 0.15), 0 2px 6px rgba(0, 0, 0, 0.25) inset;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    form::-webkit-scrollbar {
        width: 6px;
    }

    form::-webkit-scrollbar-track {
        background: transparent;
    }

    form::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 3px;
    }

    form::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.25);
    }

    .buttons button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 160, 30, 0.2), 0 2px 6px rgba(0, 0, 0, 0.28) inset;
    }
    </style>
</head>

<body>
    <div class="menu-container">
        <h2>Menu</h2>
        <form action="" method="POST">
            <?php if (count($menu_items) > 0): ?>
            <?php foreach ($menu_items as $item): ?>
            <div class="menu-item">
                <label>
                    <input type="checkbox" name="menu_items[]" value="<?php echo $item['item_id']; ?>">
                    <?php echo htmlspecialchars($item['item_name']); ?>
                </label>
                <span>₱<?php echo number_format($item['price'], 2); ?></span>
            </div>
            <?php endforeach; ?>
            <?php else: ?>
            <p>No menu items available for this restaurant.</p>
            <?php endif; ?>

            <div class="buttons">
                <button type="button" onclick="window.location.href='reservation_details.php'">Back</button>
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
</body>

</html>