<?php
    session_start();

    include("connection.php");
    include("functions.php");

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // something was posted
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {

            // check if user already exists
            $query = "SELECT * FROM users WHERE user_name = '$user_name'";
            $result = mysqli_query($con, $query);

            if(mysqli_num_rows($result) > 0) {
                echo "User with this username already exists!";
            } else {
                // save to database
                $user_id = random_num(20);
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $query = "INSERT INTO users (user_id, user_name, password) VALUES ('$user_id', '$user_name', '$hashed_password')";

                mysqli_query($con, $query);

                header("Location: login.php");
                die;
            }
        } else {
            echo "Please enter valid information!";
        }
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
            <div style="font-size: 20px; margin: 10px; color: white;">Sign Up</div>

            <input id="text" type="text" name="user_name"><br><br>
            <input id="text" type="password" name="password"><br><br>

            <input id="button" type="submit" name="Signup"><br><br>

            <a href="login.php">Click to Login</a>


        </form>
    </div>
</body>
</html>