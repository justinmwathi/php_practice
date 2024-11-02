<?php
include("database.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <h2>Welcome to Tubonge</h2>
        Username:<br>
        <input type="text" name="username"><br>
        Password:<br>
        <input type="password" name="password"><br>
        <input type="submit" name="submit" value="Register">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if (empty($username)) {
            echo "Please enter a username";
        } elseif (empty($password)) {
            echo "Please enter a password";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            
            // Use prepared statements to prevent SQL injection
            $stmt = $conn->prepare("INSERT INTO users (user, password) VALUES (?, ?)");
            $stmt->bind_param("ss", $username, $hash); // "ss" means two string parameters

            try {
                $stmt->execute();
                echo "You are now registered!";
            } catch (mysqli_sql_exception $e) {
                echo "Username is taken: " . $e->getMessage();
            }

            $stmt->close(); // Close the statement
        }
    }

    mysqli_close($conn); // Close the connection
    ?>
</body>
</html>
