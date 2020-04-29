<html><head><title> Group 1A Catalog Page
</title></head>
<center>
<body bgcolor='FFA07A'>

  <?php
  $cartcontents = array(); //initialize the variable as an array() 

  //maintain the cart array
  if (array_key_exists('cart', $_REQUEST)) //if cart
  {
    $cartcontents = unserialize(base64_decode($_REQUEST['cart'])); //unseralize the array to be uses
  }

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/catelog.php>"; //method post to catelog.php page
    $arraytostring3 = base64_encode(serialize($cartcontents)); //seriazlize the contenets again
    echo "<input type=hidden name='cart',
                 value=$arraytostring3/>"; //array to string to be used
    echo "<input type=hidden name='list' //listt hidden 
                 value='filler'/>"; 
    echo "<input type=submit name='button1' //button for viewing the pruduct catalog
                 value='View Product Catalog'/>";
    echo "<input type=submit name='button2' //button for closing the table down
                 value='Close Product Catelog'/>";
  echo "</form>"; //end of the form for openeing and closing the catalog 

  //create a cart button
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/cart.php>"; //cart button to be submitted to cart.php
    $arraytostring6 = base64_encode(serialize($cartcontents)); //serialze the contents back to array
    echo "<input type=hidden name='cart'
                 value=$arraytostring6/>"; //array to string
    echo "<input type=submit name='button7' //button for viewing your cart tha ttakes you to cat.php
                 value='Your Cart'/>";
  echo "</form>"; //end of cart.php entry form


// Connecting to MariaDB if it fails then catch error and display it
  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";
    include("Project8Apswrd.php"); //using data from here
    $pdo1 = new PDO($dsn1, $username1, $password1);
  }

//Error handler
  catch(PDOexception $e1)
  {
    echo "Database connection failed: " . $e1->getMessage();
  }

//Connect to the DB with all data provided in project details
  try
  {
    $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
    include("Project8Apswrd.php"); //again using doc for variables
    $pdo2 = new PDO($dsn2, $username2, $password2);
  }

//Handle an error and display it if there is one
  catch(PDOexception $e2)
  {
    echo "Database connection failed: " . $e2->getMessage();
  }

//end of the beginning buttons and the connection to DB's
  ?>

  <?php

//Closing the catalog button with resubmission to self
  if (isset($_POST['button2']))
  {
    unset($_REQUEST['list']);
  }

  if(array_key_exists('list', $_REQUEST))
  {
    //test a query
    echo "<h1>Group 1A Product Catalog Page</h1>";
	echo "<h3> COVID-19 Has NOT Affected Our Shipping Times!</h3>";
	echo "<h3> For more information during COVID-19 Please Contact US!</h3>";
    $sql1 = "SELECT * FROM parts"; //Retreieve all parts in the DB and display to user
    $query1 = $pdo2->query($sql1); //actualyl request to the server

    //get the row to print
    $rows = $query1->fetchAll(PDO::FETCH_ASSOC); //fetch all and store row in rows to be echo'd

    //use a table to print the results
    echo "<table width='40%'border=3, cellspacing=15,cellpadding=1>"; //table settings
	echo '<tbody style="background-color:#FF726F">'; //colorring
      echo "<tr>"; //sttart of row
        echo "<th>Product #</th>";
        echo "<th>Product Name</th>";
        echo "<th>More information</th>";
      echo "</tr>"; //end of headers
      foreach($rows as $value) //displaying the DB data
      {
        echo "<tr>"; //new row for the loop
          echo "<td>";
            echo "$value[number]"; //the product number here
          echo "</td>"; 

          echo "<td>";
            echo "$value[description]"; //product name here
          echo "</td>";
        //button to see more detials, more informatino on the product
          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/details.php>"; //button for taking to details.php page
              $arraytostring = base64_encode(serialize($value));
              echo "<input type=hidden name=pnum
                           value=$arraytostring/>";
              $arraytostring2 = base64_encode(serialize($cartcontents));
              echo "<input type=hidden name='cart'
                           value=$arraytostring2/>";
              echo "<input type=submit name='$value[number]' // the number to request more inbfromation on
                           value='See More Information'/>";
            echo "</form>"; //end of form
          echo "</td>"; //end data
        echo "</tr>"; //end row
      } //loop here to keep doing for all data in DB
    echo "</table>"; //end of table
  }
  ?>
</html>


<footer>

Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>
