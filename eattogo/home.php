<?php
session_start();
ob_start(); // Prevents header issues and ensures session works
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Eat To Go</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Playfair+Display:wght@600&display=swap"
    rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    body {
      background: url("images/bg1.jpg") no-repeat center center fixed;
      background-size: cover;
      font-family: "Poppins", sans-serif;
      color: #f5f5f5;
      padding-top: 80px;
      scrollbar-width: none;
      -ms-overflow-style: none;
    }

    body::-webkit-scrollbar {
      display: none;
    }

    .glass {
      position: relative;
      border-radius: 16px;
      background: linear-gradient(135deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
      border: 1px solid rgba(255,255,255,0.12);
      -webkit-backdrop-filter: blur(18px) saturate(140%);
      backdrop-filter: blur(18px) saturate(140%);
      box-shadow: 0 10px 30px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.02);
      overflow: hidden;
      isolation: isolate;
    }

    .glass::before {
      content: "";
      position: absolute;
      left: -30%;
      top: -40%;
      width: 160%;
      height: 120%;
      background: radial-gradient(600px 300px at 20% 20%, rgba(255,255,255,0.12), rgba(255,255,255,0) 30%);
      transform: rotate(-12deg);
      pointer-events: none;
      mix-blend-mode: overlay;
      opacity: 0.9;
    }

    .glass::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 18%;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.12));
      pointer-events: none;
    }

      .restaurant-card.glass::after,
      .restaurant-card::after {
        display: none;
        content: none;
      }

    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      height: 72px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding: 0 32px;
      z-index: 1000;
      background: linear-gradient(180deg, rgba(255,255,255,0.06), rgba(255,255,255,0.02));
      -webkit-backdrop-filter: blur(18px) saturate(140%);
      backdrop-filter: blur(18px) saturate(140%);
      border-bottom: 1px solid rgba(255, 255, 255, 0.12);
      box-shadow: 0 8px 24px rgba(0,0,0,0.35);
    }

    .navbar .logo img {
      width: 300px;
      height: 200px;
      object-fit: contain;
    }

    .navbar .nav-links {
      display: flex;
      gap: 25px;
    }

    .navbar a {
      color: white;
      text-decoration: none;
      font-weight: 500;
      font-size: 1rem;
      padding: 8px 16px;
      border-radius: 30px;
      transition: 0.3s ease;
    }

    .navbar a:hover {
      background: rgba(255, 255, 255, 0.25);
      backdrop-filter: blur(5px);
    }

    .restaurant-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 28px;
      max-width: 1100px;
      margin: 60px auto 120px;
      padding: 18px;
    }

    .restaurant-card {
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
      overflow: hidden;
      padding: 14px;
      transition: transform 0.28s cubic-bezier(.2,.9,.2,1), box-shadow 0.22s ease;
      cursor: pointer;
      text-decoration: none;
      color: inherit;
      border-radius: 14px;
      background: linear-gradient(180deg, rgba(255,255,255,0.03), rgba(255,255,255,0.01));
      border: 1px solid rgba(255,255,255,0.08);
      -webkit-backdrop-filter: blur(12px) saturate(120%);
      backdrop-filter: blur(12px) saturate(120%);
      box-shadow: 0 8px 20px rgba(0,0,0,0.36), inset 0 1px 0 rgba(255,255,255,0.02);
    }

    .restaurant-card:hover {
      transform: translateY(-6px) scale(1.02);
      box-shadow: 0 18px 50px rgba(0,0,0,0.48);
      background: linear-gradient(180deg, rgba(255,255,255,0.045), rgba(255,255,255,0.01));
    }

    .restaurant-card img {
      width: 100%;
      height: 220px;
      object-fit: cover;
      border-radius: 12px;
      margin-bottom: 12px;
      display: block;
      box-shadow: 0 6px 20px rgba(0,0,0,0.35);
    }

    .restaurant-card h3 {
      margin: 5px 0;
      font-family: "Playfair Display", serif;
      font-size: 1.6rem;
      letter-spacing: 0.5px;
      color: #ffd166;
      text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
    }

    .restaurant-card p {
      margin: 4px 0;
      color: #f0f0f0;
      font-size: 0.95rem;
    }

    footer {
      padding: 5px 0;
    }
  </style>
</head>
<body>
  <div class="navbar">
    <div class="logo">
  <img src="images/logo.png" alt="logo">
    </div>
    <div class="nav-links">
      <?php if (isset($_SESSION['customer_id'])): ?>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="signup.php">Sign Up</a>
      <?php endif; ?>
    </div>
  </div>

  <div class="restaurant-container">
    <a href="restaurant_click.php?id=1" class="restaurant-card glass">
  <img src="images/res1.jpg" alt="Restaurant 1">
      <h3>Café Ilang-Ilang</h3>
      <p>Ermita, Manila</p>
      <p>09175500511</p>
    </a>

    <a href="restaurant_click.php?id=2" class="restaurant-card glass">
  <img src="images/res2.jpg" alt="Restaurant 2">
      <h3>Uma Nota Manila</h3>
      <p>BGC, Taguig</p>
      <p>09173072766</p>
    </a>

    <a href="restaurant_click.php?id=3" class="restaurant-card glass">
  <img src="images/res3.jpg" alt="Restaurant 3">
      <h3>The Aristocrat Restaurant</h3>
      <p>Malate, Manila</p>
      <p>09778940000</p>
    </a>

    <a href="restaurant_click.php?id=4" class="restaurant-card glass">
  <img src="images/res4.jpg" alt="Restaurant 4">
      <h3>Harbor View Restaurant</h3>
      <p>Ermita, Manila</p>
      <p>09177100060</p>
    </a>

    <a href="restaurant_click.php?id=5" class="restaurant-card glass">
  <img src="images/res5.jpg" alt="Restaurant 5">
      <h3>Restaurant Uno</h3>
      <p>Tomas Morato, Quezon City</p>
      <p>09173740774</p>
    </a>

    <a href="restaurant_click.php?id=6" class="restaurant-card glass">
  <img src="images/res6.jpg" alt="Restaurant 6">
      <h3>Las Flores</h3>
      <p>BGC, Taguig</p>
      <p>09175245058</p>
    </a>
  </div>

  <footer></footer>
</body>
</html>
