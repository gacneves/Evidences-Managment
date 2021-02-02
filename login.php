<?php
session_start();
require_once "config.php";

if(isset($_POST["login"])){
$login = trim($_POST["login"]);
$pass = trim($_POST["pass"]);
}
if(isset($login) and isset($pass)){
  $sql = "SELECT * FROM user WHERE login=? AND password=?";
         
        if($stmt = mysqli_prepare($connection, $sql))
        {
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ss", $login, $pass);
            
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
              $stmt->store_result();
               if($stmt->num_rows > 0){
                
                $stmt->bind_result($user_id, $user_name, $user_tp);

                $_SESSION['login'] = $login;
                $_SESSION['user_tp'] = $user_tp;
                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                $stmt->free_result();
                header('location:index.php');
               }
               else{
                echo "Invalid Login or Password";
               }
            }
            else
            {
                echo "Something went wrong. Please try again.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    
    // Close connection
    mysqli_close($connection);
}
else{
  echo "
  <!DOCTYPE html>
<html>
<head>
<meta name='viewport' content='width=device-width, initial-scale=1'>
<style>
body {font-family: Arial, Helvetica, sans-serif;}
form {border: 3px solid #f1f1f1;}

input[type=text], input[type=password] {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
  border-radius: 5px;
}

button {
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  cursor: pointer;
  width: 100%;
  border-radius: 5px;
}

button:hover {
  opacity: 0.9;
}

.container {
  padding: 16px;
}

span.psw {
  float: right;
  padding-top: 16px;
}

/* Change styles for span and cancel button on extra small screens */
@media screen and (max-width: 300px) {
  span.psw {
     display: block;
     float: none;
  }

}
</style>
</head>
<body>

<h2>User Login</h2>

<form action='login.php' method='post'>

  <div class='container'>
    <label for='uname'><b>Username</b></label>
    <input type='text' placeholder='Enter Username' name='login' required>

    <label for='psw'><b>Password</b></label>
    <input type='password' placeholder='Enter Password' name='pass' required>
        
    <button type='submit'>Login</button>
  </div>

</form>

</body>
</html>
  ";
}
?>
