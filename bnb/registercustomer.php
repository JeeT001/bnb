<!DOCTYPE HTML>
<html><head><title>Register new customer</title> </head>
 <body>

<?php
//function to clean input 
function cleanInput($data) {  
  return htmlspecialchars(stripslashes(trim($data)));
}


if (isset($_POST['submit']) and !empty($_POST['submit']) and ($_POST['submit'] == 'Register')) {
   
    include "config.php"; //load in any variables
    $DBC = mysqli_connect("127.0.0.1", DBUSER, DBPASSWORD, DBDATABASE);

    if (mysqli_connect_errno()) {
        echo "Error: Unable to connect to MySQL. ".mysqli_connect_error() ;
        exit; //stop processing the page further
    };

//validate incoming data 
    $error = 0; 
    $msg = 'Error: ';
    if (isset($_POST['firstname']) and !empty($_POST['firstname']) and is_string($_POST['firstname'])) {
       $fn = cleanInput($_POST['firstname']); 
       $firstname = (strlen($fn)>50)?substr($fn,1,50):$fn; //check length and clip if too big
       //we would also do context checking here for contents, etc       
    } else {
       $error++; 
       $msg .= 'Invalid firstname '; 
       $firstname = '';  
    } 
//lastname
       $lastname = cleanInput($_POST['lastname']);        
//email
       $email = cleanInput($_POST['email']);        
    
//password    
       $password = cleanInput($_POST['password']);        
       

    if ($error == 0) {
        $query = "INSERT INTO customer (firstname,lastname,email,password) VALUES (?,?,?,?,?)";
        $stmt = mysqli_prepare($DBC,$query); //prepare the query
        mysqli_stmt_bind_param($stmt,'sssss', $firstname, $lastname, $email,$username,$password); 
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);    
        echo "<h2>customer saved</h2>";        
    } else { 
      echo "<h2>$msg</h2>".PHP_EOL;
    }      
    mysqli_close($DBC); //close the connection once done
}
?>
<h1>New Customer Registration</h1>
<h2><a href='listcustomers.php'>[Return to the Customer listing]</a><a href='/bnb/'>[Return to the main page]</a></h2>

<form method="POST" action="registercustomer.php">
  <p>
    <label for="firstname">Name: </label>
    <input type="text" id="firstname" name="firstname" minlength="5" maxlength="50" required> 
  </p> 
  <p>
    <label for="lastname">Name: </label>
    <input type="text" id="lastname" name="lastname" minlength="5" maxlength="50" required> 
  </p>  
  <p>  
    <label for="email">Email: </label>
    <input type="email" id="email" name="email" maxlength="100" size="50" required> 
   </p>
  <p>
    <label for="password">Password: </label>
    <input type="password" id="password" name="password" minlength="8" maxlength="32" required> 
  </p> 
  
   <input type="submit" name="submit" value="Register">
 </form>
</body>
</html>
  