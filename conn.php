<?php
//connect to the databases
//used for the workstations.php view only


//Connecting to MariaDB is first connection
try
{
  $dsn1 = "mysql:host=courses;dbname=z1853066"; 


  include("Project8Apswrd.php");
  $pdo1 = new PDO($dsn1, $username1, $password1);
}

//handle the error if there is an errors and display the error code and info
catch(PDOexception $e1)
{
  echo "Database connection failed: " . $e1->getMessage();
}


//Try connecting to the server with the DB data on it for the project. (blitz)
try
{
  $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
  include("Project8Apswrd.php"); //passwords file
  $pdo2 = new PDO($dsn2, $username2, $password2); 
}

// Error hadnling if there is one, display the error
catch(PDOexception $e2)
{
  echo "Database connection failed: " . $e2->getMessage();
}
//USed for the workstations.php file only.
?>
