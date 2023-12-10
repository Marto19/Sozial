<?php
  // Define the correct username and password
  $correct_username = 'nbu';
  $correct_password = 123;
  
  // Get the submitted username and password from the form
  $submitted_username = $_POST['email'];
  $submitted_password = $_POST['password'];
  
  // Check if the submitted username and password are correct
  if ($submitted_username == $correct_username && $submitted_password == $correct_password) {
      // Redirect to a success page
      header("Location: landing.html");
      exit();
  } else{
    echo "Incorrect password. Please try again.";
  }
  ?>