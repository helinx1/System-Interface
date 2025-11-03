<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login - Eat To Go</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: "Poppins", sans-serif;
      background: url("images/bg2.jpg") no-repeat center center fixed;
      background-size: cover;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      color: #fff;
    }

    .login-container {
      position: relative;
      width: clamp(320px, 86%, 420px);
      padding: 36px;
      border-radius: 16px;
      color: #fff;
      text-align: center;

      background: linear-gradient(135deg, rgba(255,255,255,0.08), rgba(255,255,255,0.03));
      border: 1px solid rgba(255,255,255,0.16);

      -webkit-backdrop-filter: blur(18px) saturate(140%);
      backdrop-filter: blur(18px) saturate(140%);

      box-shadow: 0 10px 30px rgba(0,0,0,0.45), inset 0 1px 0 rgba(255,255,255,0.02);

      overflow: hidden;
      isolation: isolate;
    }

    .login-container::before {
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

    .login-container::after {
      content: "";
      position: absolute;
      left: 0;
      right: 0;
      bottom: 0;
      height: 20%;
      background: linear-gradient(180deg, rgba(255,255,255,0.02), rgba(0,0,0,0.12));
      pointer-events: none;
    }

    h2 {
      margin-bottom: 22px;
      font-size: 1.6rem;
      color: #ffd166;
      text-shadow: 0 1px 0 rgba(0,0,0,0.4);
      letter-spacing: 0.2px;
    }

    input {
      width: 100%;
      padding: 12px 12px;
      border-radius: 10px;
      border: 1px solid rgba(255,255,255,0.08);
      background: rgba(0,0,0,0.18);
      color: #fff;
      font-size: 1rem;
      margin-bottom: 14px;
      outline: none;
      transition: box-shadow 0.18s ease, transform 0.12s ease;
      backdrop-filter: blur(6px);
    }

    input::placeholder { color: rgba(255,255,255,0.7); }

    input:focus {
      box-shadow: 0 6px 18px rgba(0,0,0,0.45), 0 0 0 4px rgba(255,209,102,0.07);
      transform: translateY(-1px);
      border-color: rgba(255,209,102,0.6);
    }

    button {
      width: 100%;
      padding: 12px 14px;
      margin-top: 10px;
      border-radius: 999px;
      font-size: 1rem;
      font-weight: 700;
      cursor: pointer;
      border: none;

      background: linear-gradient(90deg, rgba(255,209,102,0.98), rgba(255,180,55,0.95));
      color: #2a2a2a;
      box-shadow: 0 8px 20px rgba(255,160,30,0.15), 0 2px 6px rgba(0,0,0,0.25) inset;
      transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    button:hover {
      transform: translateY(-2px);
      box-shadow: 0 12px 30px rgba(255,160,30,0.2), 0 2px 6px rgba(0,0,0,0.28) inset;
    }

    a {
      display: block;
      margin-top: 12px;
      color: rgba(255,255,255,0.92);
      text-decoration: none;
      font-size: 0.9rem;
      opacity: 0.94;
    }
  </style>
</head>
<body>
  <div class="login-container">
    <h2>Log In</h2>
    <form action="login_process.php" method="POST">
      <input type="email" name="email" placeholder="Email" required>
      <input type="text" name="password" placeholder="Password" required>
      <button type="submit">Login</button>
    </form>
  <a href="reset_password.php">Forgot Password?</a>
    <a href="signup.php">Don't have an account? Sign up</a>
  </div>
</body>
</html>
