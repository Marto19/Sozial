<?php
function check_login($con){
    if(isset($_SESSION['user_id'])){        //checks if the user id is set
        $id = $_SESSION['user_id'];        
        $query = "select * from users where user_id = $id limit 1";      //we make query

        $result = mysqli_query($con, $query);   //we store the result from the query
        if($result && mysqli_num_rows($result) > 0){    //if the result exists and the number of rows is greater than 0, basically we check if its real
            $user_data = mysqli_fetch_assoc($result);   //we use associative array and store user data
            return $user_data;
        }
    }

    //redirect to login
    header("Location: login.php");
    die;
}

function random_num($length){
    $text = "";
    if($length < 5){
        $length = 5;
    }

    $len = rand(4, $length); //between 4 and given number

    for($i = 0; $i < $len; ++$i){
        $text .= rand(0, 9);
    }

    return $text;
}