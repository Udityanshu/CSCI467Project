<!-- weights.php -->
<html><title> Weight Brackets</title>
	<center>
	<body bgcolor='FFA07A'>
  <?php

//connecting to MAria db 
    try
    {
      $dsn1 = "mysql:host=courses;dbname=z1853066";
      include("pswrds.php"); //connecting with pswd file
      $pdo1 = new PDO($dsn1, $username1, $password1);
    }


//if error dispaly error code
    catch(PDOexception $exception1)
    {
      echo "Database connection failed: " . $exception1->getMessage();
    }

//connecting to blitz server if error display error code
    try
    {
      $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
      include("pswrds.php");
      $pdo2 = new PDO($connection2, $username2, $password2);
    }

    catch(PDOexception $exception2)
    {
      echo "Database connection failed: " . $exception2->getMessage();
    }

    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/orders.php>"; 
//post tmethod to go to orders.php page (bacK)
      echo "<input type=submit name='goorder'
                   value='Go Back to Orders Page'/>";
    echo "</form>";

    echo "<h1>Adjusting Weight Bracket and Charges</h1>";

    if (array_key_exists('add', $_REQUEST))
    {
      $weightnum = $_REQUEST['addnum'];
      $floatnum1 = round(floatval($weightnum), 2);

      if ($floatnum1 < 0)
      {
        $floatnum1 = 0; //make sure more than 0, otehrwise set 0
      }

      $chargenum = $_REQUEST['addcharge'];
      $floatnum2 = round(floatval($chargenum), 2);

      if ($floatnum2 < 0) //make sure greater than 0, otherwise se t0
      {
        $floatnum2 = 0;
      }


      //reset the first element
      $sqlfnd = "SELECT * FROM admin WHERE bracket = $floatnum1";
      $queryfnd = $pdo1->query($sqlfnd);
      $fndarray = $queryfnd->fetchAll(PDO::FETCH_ASSOC);

      if (count($fndarray) != 0)
      {
        $sqlfo = "UPDATE admin SET charge = $floatnum2 WHERE bracket = $floatnum1";
        $queryfo = $pdo1->query($sqlfo);
      }

      else
      {
        //inerting the charge and weight into the table
        $sqladd = "INSERT INTO admin (bracket, charge)
                  VALUES ($floatnum1, $floatnum2)";
        $queryadd = $pdo1->query($sqladd);
     }
   }

    if (array_key_exists('delete', $_REQUEST))
    {
      $number = round(floatval($_REQUEST['weightdel']), 2);
      $sqldel = "DELETE FROM admin WHERE bracket = $number";
      $querydel = $pdo1->query($sqldel);
    }

    $sql1 = "SELECT * FROM admin ORDER BY bracket";
    $query1 = $pdo1->query($sql1);
    $brackets = $query1->fetchAll(PDO::FETCH_ASSOC);

    echo "<table border=5,cellspacing=5,cellpadding=5>";
echo '<tbody style="background-color:#FF726f">';
	//table settings


      echo "<tr>";
        echo "<th>Weights</th>";
        echo "<th>Charges</th>";
        echo "<th>Delete</th>";
      echo "</tr>";

      foreach($brackets as $bracket)
      {
        echo "<tr>";
          echo "<td>";
            echo "$bracket[bracket]";
          echo "</td>";

          echo "<td>";
            echo "$bracket[charge]";
          echo "</td>";

          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/weightcharge.php>";
              echo "<input type=hidden name='weightdel'
                           value=$bracket[bracket]/>";
              echo "<input type=submit name='delete'
                           value='Remove'/>";
            echo "</form>";
          echo "</td>";

        echo "</tr>";
      }
    echo "</table>";
	echo "<br><br>";


echo "<h3> To Add A New Bracket Please Enter Bracket Below: </h3>";
	//buttons for adding new charge and weight
    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/weightcharge.php>";
      echo "<input type=submit name='add'
                   value='Add A New Entry'/>";
      echo "<input type=text name='addnum'
                   placeholder='Weight To Be Added' required/>";
      echo "<input type=text name='addcharge'
                   placeholder='Charge Added For Weight' required/>";
    echo "</form>";
  ?>
</html>
<br><br><br><br><br>
<footer>

Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>

