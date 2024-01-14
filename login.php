<?php
    session_start();

    include("connection.php");
    include("functions.php");

    if ($_SERVER['REQUEST_METHOD'] == "POST") {
        // something was posted
        $user_name = $_POST['user_name'];
        $password = $_POST['password'];

        if (!empty($user_name) && !empty($password) && !is_numeric($user_name)) {
            // read to database
            $query = "SELECT * FROM users WHERE user_name = '$user_name' limit 1";    //we check if the user name is in the database

            $result = mysqli_query($con, $query); // Added $conn as the first argument

            if($result){
                if($result && mysqli_num_rows($result) > 0){    //if the result exists and the number of rows is greater than 0, basically we check if its real
                    $user_data = mysqli_fetch_assoc($result);   //we use associative array and store user data
                    if($user_data['password'] == $password)
                    {
                        $_SESSION['user_id'] = $user_data['user_id'];
                        header("Location: index.php");
                        die;
                    }
                }
            }
            echo "Wrong username or password!";
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