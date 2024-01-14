<?php
session_start();

include("connection.php");
include("functions.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    // something was posted
    $user_name = $_POST['user_name'];
    $password = $_POST['password'];

    if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
        // read from database
        $query = "SELECT * FROM users WHERE user_name = '$user_name' LIMIT 1";

        $result = mysqli_query($con, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                $user_data = mysqli_fetch_assoc($result);
                // Assuming passwords are stored in plaintext (for demonstration purposes)
                if ($user_data['password'] == $password) {
                    $_SESSION['user_id'] = $user_data['user_id'];
                    echo '<script>window.location.href = "index.php";</script>';
                    die;
                } else {
                    echo "Wrong username or password!";
                }
            } else {
                echo "Wrong username or password!";
            }
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Wrong username or password!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <style type="text/css">
        #text{
            height: 25;
            border-radius: 5px;
            padding: 4px;
            border: solid thin #aaa;
            width: 100%;
        }
        #button{
            padding: 10px;
            width: 100px;
            color: white;
            background-color: lightblue;
            border: none;
        }
        #box{
            background-color: #aaa;
            margin: auto;
            width: 300px;
            padding: 20px;
        }
    </style>
    <div id="box">
        <form method="post">
            <div style="font-size: 20px; margin: 10px; color: white;">Login</div>

            <input id="text" type="text" name="user_name"><br><br>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" name="Login"><br><br>

            <a href="signup.php">Click to Sign Up</a>

        </form>
    </div>
</body>
</html>