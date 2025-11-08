<?php
session_start();
if (!isset($_SESSION['customer_id'])) {
  // User is not logged in
  header("Location: login.php");
  exit();
}

include 'db_connect.php';

// Determine Discount Type based on special_status and birthday
$discountType = "None";
$specialStatus = $_SESSION['special_status'];
$birthday = $_SESSION['birthday'];

if ($specialStatus !== "None") {
  $discountType = $specialStatus;
} else {
  // Check if today is birthday
  $today = date("m-d");
  $birth_md = date("m-d", strtotime($birthday));
  if ($today === $birth_md) {
    $discountType = "Birthday";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservation - Eat To Go</title>
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

    .reservation-container {
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

    .reservation-container::before {
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

    .reservation-container::after {
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
        font-size: 1.5rem;
        color: #ffd166;
        text-shadow: 0 1px 0 rgba(0, 0, 0, 0.45);
    }

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    input,
    select,
    textarea {
        width: 100%;
        padding: 10px 12px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.18);
        color: #fff;
        outline: none;
        font-size: 0.96rem;
        box-sizing: border-box;
        transition: box-shadow 0.16s ease, transform 0.12s ease;
        backdrop-filter: blur(6px);
    }

    input::placeholder,
    textarea::placeholder {
        color: rgba(255, 255, 255, 0.7);
    }

    input:focus,
    select:focus,
    textarea:focus {
        box-shadow: 0 8px 22px rgba(0, 0, 0, 0.46), 0 0 0 4px rgba(255, 209, 102, 0.06);
        transform: translateY(-1px);
        border-color: rgba(255, 209, 102, 0.6);
    }

    #people {
        background: rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 209, 102, 0.5);
        box-shadow: 0 2px 8px rgba(255, 209, 102, 0.1);
    }

    .parking-row {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: nowrap;
    }

    .parking-row>label:first-child {
        white-space: nowrap;
        margin: 0;
        font-size: 0.98rem;
    }

    .parking-options {
        display: flex;
        gap: 12px;
        align-items: center;
    }

    .parking-options label {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin: 0;
        font-size: 0.96rem;
        cursor: pointer;
    }

    textarea {
        height: 56px;
        resize: none;
    }

    .buttons {
        display: flex;
        justify-content: space-between;
        gap: 10px;
        margin-top: 10px;
    }

    .buttons button {
        flex: 1;
        padding: 10px 12px;
        border-radius: 999px;
        font-size: 0.95rem;
        font-weight: 700;
        cursor: pointer;
        border: none;
        background: linear-gradient(90deg, rgba(255, 209, 102, 0.98), rgba(255, 180, 55, 0.95));
        color: #2a2a2a;
        box-shadow: 0 8px 20px rgba(255, 160, 30, 0.14), 0 2px 6px rgba(0, 0, 0, 0.22) inset;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    .buttons button:hover {
        transform: translateY(-3px);
        box-shadow: 0 14px 36px rgba(255, 160, 30, 0.2), 0 2px 6px rgba(0, 0, 0, 0.28) inset;
    }
    </style>
</head>

<body>
    <div class="reservation-container">
        <h2>Reservation</h2>
        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] === 'invalid_date'): ?>
                <p style="color: #ff6b6b; text-align: center; margin-bottom: 10px;">Invalid date selected. Please choose a date in the current year that is not in the past.</p>
            <?php elseif ($_GET['error'] === 'invalid_time'): ?>
                <p style="color: #ff6b6b; text-align: center; margin-bottom: 10px;">Invalid time selected. Please choose a time between 9:00 AM and 10:00 PM.</p>
            <?php endif; ?>
        <?php endif; ?>
        <form action="reservation_details.php" method="POST">
            <label for="date">Date:</label>
            <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" max="<?php echo date('Y-m-31'); ?>" required>

            <label for="time">Time:</label>
            <input type="time" name="time" id="time" min="09:00" max="22:00" required>

            <label for="people">Number of People:</label>
            <select name="people" id="people" required>
                <option value="" disabled selected>Select number of people</option>
                <?php for ($i = 1; $i <= 20; $i++): ?>
                    <option value="<?php echo $i; ?>"><?php echo $i == 1 ? '1 person' : $i . ' people'; ?></option>
                <?php endfor; ?>
            </select>

            <div class="parking-row">
                <label>Parking Required:</label>
                <div class="parking-options">
                    <label><input type="radio" name="parking" value="1" required> Yes</label>
                    <label><input type="radio" name="parking" value="0"> No</label>
                </div>
            </div>

            <label for="dietary">Dietary Needs:</label>
            <textarea name="dietary" id="dietary" rows="1" placeholder="Optional"></textarea>

            <label for="discount">Discount Type:</label>
            <input type="text" name="discount" id="discount" value="<?php echo $discountType; ?>" readonly>

            <div class="buttons">
                <button type="button" onclick="window.location.href='home.php'">Cancel</button>
                <button type="submit">Next</button>
            </div>
        </form>
    </div>
</body>

</html>
