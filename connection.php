<!-- conn.php -->

<?php
//connect to the databases
//used for the workstations.php view only


//Connecting to MariaDB is first connection
try
{
  $dsn1 = "mysql:host=courses;dbname=z1853066"; 


  include("pswrds.php");
  $pdo1 = new PDO($dsn1, $username1, $password1);
}

//handle the error if there is an errors and display the error code and info
catch(PDOexception $exception1)
{
  echo "Database connection failed: " . $exception1->getMessage();
}


//Try connecting to the server with the DB data on it for the project. (blitz)
try
{
  $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
  include("pswrds.php"); //passwords file
  $pdo2 = new PDO($connection2, $username2, $password2); 
}

// Error hadnling if there is one, display the error
catch(PDOexception $exception2)
{
  echo "Database connection failed: " . $exception2->getMessage();
}
//USed for the workstations.php file only.
?>


