<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up - Eat To Go</title>
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
    }

    .signup-container {
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
    }

    .signup-container::before {
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

    .signup-container::after {
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

    form {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }

    .name-fields {
        display: flex;
        gap: 10px;
    }

    .name-fields input {
        width: 100%;
    }

    input,
    select {
        width: 100%;
        padding: 12px 12px;
        border-radius: 10px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.18);
        color: #fff;
        font-size: 1rem;
        box-sizing: border-box;
        outline: none;
        transition: box-shadow 0.18s ease, transform 0.12s ease;
        backdrop-filter: blur(6px);
    }

    .password-container {
        position: relative;
        width: 100%;
    }

    .password-container input {
        padding-right: 44px;
    }

    .password-toggle {
        position: absolute;
        right: 12px;
        top: 0px;
        background: none;
        border: none;
        color: rgba(255, 255, 255, 0.7);
        cursor: pointer;
        font-size: 1.2rem;
        padding: 0;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 2px;
    }

    .password-toggle:hover {
        color: #fff;
    }

    input::placeholder,
    select option[disabled] {
        color: rgba(255, 255, 255, 0.7);
    }

    select option {
        background: rgba(0, 0, 0, 0.9);
        color: #fff;
    }

    input:focus,
    select:focus {
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.45), 0 0 0 4px rgba(255, 209, 102, 0.07);
        transform: translateY(-1px);
        border-color: rgba(255, 209, 102, 0.6);
    }

    label {
        font-size: 0.9rem;
        margin-bottom: -5px;
        color: #fff;
    }

    .dob {
        display: flex;
        gap: 10px;
    }

    .dob input {
        flex: 1;
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
        background: linear-gradient(90deg, rgba(255, 209, 102, 0.98), rgba(255, 180, 55, 0.95));
        color: #2a2a2a;
        box-shadow: 0 8px 20px rgba(255, 160, 30, 0.15), 0 2px 6px rgba(0, 0, 0, 0.25) inset;
        transition: transform 0.16s ease, box-shadow 0.16s ease;
    }

    button:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(255, 160, 30, 0.2), 0 2px 6px rgba(0, 0, 0, 0.28) inset;
    }

    .error-message {
        background-color: rgba(255, 0, 0, 0.8);
        color: #fff;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
        text-align: center;
        font-size: 0.9rem;
    }

    a {
        display: block;
        text-align: center;
        margin-top: 12px;
        color: rgba(255, 255, 255, 0.92);
        text-decoration: none;
        font-size: 0.9rem;
        opacity: 0.94;
    }
    </style>
    <script>
    function validateEmail() {
        const email = document.querySelector('input[name="email"]').value;
        const emailRegex = /^[^\s@]+@(gmail\.com|yahoo\.com|outlook\.com|hotmail\.com|icloud\.com|aol\.com|protonmail\.com|zoho\.com|yandex\.com|mail\.com)$/i;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address');
            return false;
        }
        return true;
    }

    function validateContactNumber() {
        const contactNumber = document.querySelector('input[name="contact_number"]').value;
        const contactRegex = /^((\+63\d{10})|(09\d{9}))$/;
        if (!contactRegex.test(contactNumber)) {
            alert('Please enter a valid Contact Number ');
            return false;
        }
        return true;
    }

    const openEyeSVG = '<svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M1 12C1 12 5 4 12 4S23 12 23 12S19 20 12 20S1 12 1 12Z\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/><circle cx=\"12\" cy=\"12\" r=\"3\" stroke=\"currentColor\" stroke-width=\"2\"/></svg>';
    const closedEyeSVG = '<svg width=\"20\" height=\"20\" viewBox=\"0 0 24 24\" fill=\"none\" xmlns=\"http://www.w3.org/2000/svg\"><path d=\"M2.99902 3L20.999 21M9.8433 9.91364C9.32066 10.4536 8.99902 11.1892 8.99902 12C8.99902 13.6569 10.3422 15 11.999 15C12.8215 15 13.5667 14.669 14.1086 14.133M6.49902 6.64715C4.59972 7.90034 3.15305 9.78394 2.45703 12C3.73128 16.0571 7.52159 19 11.9992 19C13.9881 19 15.8414 18.4194 17.3988 17.4184M10.999 5.04939C11.328 5.01673 11.6617 5 11.9992 5C16.4769 5 20.2672 7.94291 21.5414 C21.2607 12.894 20.8577 13.7338 20.3522 14.5\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"/></svg>';

    function togglePassword() {
        const passwordInput = document.querySelector('input[name="password"]');
        const toggleButton = document.querySelector('.password-toggle');
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            toggleButton.innerHTML = closedEyeSVG;
        } else {
            passwordInput.type = 'password';
            toggleButton.innerHTML = openEyeSVG;
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.querySelector('.password-toggle');
        toggleButton.innerHTML = openEyeSVG;
    });

    document.querySelector('form').addEventListener('submit', function(e) {
        if (!validateEmail() || !validateContactNumber()) {
            e.preventDefault();
        }
    });
    </script>
</head>

<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <?php
        if (isset($_SESSION['error'])) {
            echo '<div class="error-message">' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>
        <form action="signup_process.php" method="POST">
            <div class="name-fields">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>
            <input type="email" name="email" placeholder="Email" required>
            <div class="password-container">
                <input type="password" name="password" placeholder="Password" required>
                <button type="button" class="password-toggle" onclick="togglePassword()"></button>
            </div>
            <input type="text" name="contact_number" placeholder="Contact Number (+63)" required>

            <select name="special_status" required>
                <option value="">Special Status</option>
                <option>None</option>
                <option>PWD</option>
                <option>Senior Citizen</option>
                <option>Pregnant</option>
            </select>

            <label>Date of Birth</label>
            <div class="dob">
                <select name="year" required>
                    <option value="">Year</option>
                    <?php for ($i = 2013; $i >= 1950; $i--): ?>
                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                    <?php endfor; ?>
                </select>
                <select name="month" required>
                    <option value="">Month</option>
                    <option value="01">January</option>
                    <option value="02">February</option>
                    <option value="03">March</option>
                    <option value="04">April</option>
                    <option value="05">May</option>
                    <option value="06">June</option>
                    <option value="07">July</option>
                    <option value="08">August</option>
                    <option value="09">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                </select>
                <select name="day" required>
                    <option value="">Day</option>
                    <?php for ($i = 1; $i <= 31; $i++): ?>
                        <option value="<?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?>"><?php echo str_pad($i, 2, '0', STR_PAD_LEFT); ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <a href="login.php">Already have an account? Log in</a>
    </div>
</body>

</html>
