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
        color: #fff;
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

    input::placeholder,
    select option[disabled] {
        color: rgba(255, 255, 255, 0.7);
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
</head>

<body>
    <div class="signup-container">
        <h2>Sign Up</h2>
        <form action="signup_process.php" method="POST">
            <div class="name-fields">
                <input type="text" name="first_name" placeholder="First Name" required>
                <input type="text" name="last_name" placeholder="Last Name" required>
            </div>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="password" placeholder="Password" required>
            <input type="text" name="contact_number" placeholder="Contact Number" required>

            <select name="special_status" required>
                <option value="">Special Status</option>
                <option>None</option>
                <option>PWD</option>
                <option>Senior Citizen</option>
                <option>Pregnant</option>
            </select>

            <label>Date of Birth</label>
            <div class="dob">
                <input type="number" name="year" placeholder="YYYY" min="1900" max="2025" required>
                <input type="number" name="month" placeholder="MM" min="1" max="12" required>
                <input type="number" name="day" placeholder="DD" min="1" max="31" required>
            </div>

            <button type="submit">Sign Up</button>
        </form>
        <a href="login.php">Already have an account? Log in</a>
    </div>
</body>

</html>