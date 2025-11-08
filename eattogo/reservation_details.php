<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  header("Location: login.php");
  exit();
}

include 'db_connect.php';

// Get restaurant info from URL (e.g. home.php?res_id=1)
if (isset($_GET['res_id'])) {
    $_SESSION['restaurant_id'] = $_GET['res_id'];
}

// Get reservation form data from POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $selected_date = $_POST['date'];
  $current_year = date('Y');
  $today = date('Y-m-d');

  // Validate that the date is in the current year and not in the past
  if (date('Y', strtotime($selected_date)) !== $current_year || $selected_date < $today) {
    // Invalid date: redirect back to reservation.php with error
    header("Location: reservation.php?error=invalid_date");
    exit();
  }

  $selected_time = $_POST['time'];
  $min_time = '09:00';
  $max_time = '22:00';

  // Validate that the time is within restaurant open hours
  if ($selected_time < $min_time || $selected_time > $max_time) {
    // Invalid time: redirect back to reservation.php with error
    header("Location: reservation.php?error=invalid_time");
    exit();
  }

  $_SESSION['reservation_date'] = $selected_date;
  $_SESSION['reservation_time'] = $selected_time;
  $_SESSION['number_of_people'] = $_POST['people'];
  $_SESSION['parking_required'] = $_POST['parking'];
  $dietary = isset($_POST['dietary']) ? trim($_POST['dietary']) : '';
  if ($dietary === '') {
    $dietary = 'None';
  }
  $_SESSION['dietary_needs'] = $dietary;
  $_SESSION['discount_type'] = $_POST['discount'];
}

// Fetch restaurant details from DB
$restaurant_id = $_SESSION['restaurant_id'] ?? null;
$restaurant = null;

if ($restaurant_id) {
    $stmt = $conn->prepare("SELECT * FROM restaurant WHERE restaurant_id = ?");
    $stmt->bind_param("i", $restaurant_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $restaurant = $result->fetch_assoc();
    $stmt->close();
}

// Prepare a display-friendly time (convert 24-hour "HH:MM" to 12-hour with AM/PM)
$display_time = '';
if (!empty($_SESSION['reservation_time'])) {
  $time_str = $_SESSION['reservation_time'];
  // Try parsing as 24-hour time first
  $dt = DateTime::createFromFormat('H:i', $time_str);
  if ($dt && $dt->format('H:i') === $time_str) {
    $display_time = $dt->format('g:i A');
  } else {
    // Fallback: try strtotime for other formats
    $ts = strtotime($time_str);
    if ($ts !== false) {
      $display_time = date('g:i A', $ts);
    } else {
      // If parsing fails, show the raw value
      $display_time = $time_str;
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation Details - Eat To Go</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
    <style>
    body {
        margin: 0;
        font-family: "Poppins", sans-serif;
        background: url("images/bg4.jpg") no-repeat center center fixed;
        background-size: cover;
        height: 100vh;
        overflow-x: hidden;
        color: #fff;
        position: relative;
    }

    .details-container {
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
    }

    .details-container::before {
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

    .details-container::after {
        content: "";
        position: absolute;
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

    .info {
        margin-bottom: 20px;
        position: relative;
        z-index: 1;
    }

    .info p {
        margin: 8px 0;
        font-size: 0.96rem;
        color: rgba(255, 255, 255, 0.92);
    }

    .info p strong {
        color: #fff;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.2);
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 16px;
        position: relative;
        z-index: 1;
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

    .buttons button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 160, 30, 0.2), 0 2px 6px rgba(0, 0, 0, 0.28) inset;
    }
    </style>
</head>

<body>
    <div class="details-container">
        <h2>Reservation</h2>

        <div class="info">
            <p><strong>Restaurant:</strong> <?php echo htmlspecialchars($restaurant['name'] ?? 'Unknown'); ?></p>
            <p><strong>Location:</strong> <?php echo htmlspecialchars($restaurant['location'] ?? 'N/A'); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($restaurant['phone_number'] ?? 'N/A'); ?></p>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($_SESSION['reservation_date']); ?></p>
            <p><strong>Time:</strong> <?php echo htmlspecialchars($display_time); ?></p>

        </div>

        <form action="menu.php" method="POST">
            <div class="buttons">
                <button type="button" onclick="window.location.href='reservation.php'">Back</button>
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
</body>

</html>
