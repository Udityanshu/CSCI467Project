<!-- Catalog.php -->

<html><head><title> Group 1A Catalog Page
</title></head>
<center>
<body bgcolor='FFA07A'>

  <?php
  $cartitems = array(); 	//initialize the variable as an array() 

  
  if (array_key_exists('cart', $_REQUEST))	//maintain the cart array
  {
    $cartitems = unserialize(base64_decode($_REQUEST['cart'])); //unseralize the array 
  }

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/catalogpage.php>"; //method post to catelog.php page
    $a2s3 = base64_encode(serialize($cartitems)); //seriazlize the contenets again
    echo "<input type=hidden name='cart',
                 value=$a2s3/>"; //array to string to be used
    echo "<input type=hidden name='list' //listt hidden 
                 value='filler'/>"; 
    echo "<input type=submit name='button1' //button for viewing the pruduct catalog
                 value='View Product Catalog'/>";
    echo "<input type=submit name='button2' //button for closing the table down
                 value='Close Product Catelog'/>";
  echo "</form>"; //end of the form for openeing and closing the catalog 

  //create a cart button
  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/cartpage.php>"; //cart button to be submitted to cart.php
    $a2s6 = base64_encode(serialize($cartitems)); //serialze the contents back to array
    echo "<input type=hidden name='cart'
                 value=$a2s6/>"; //array to string
    echo "<input type=submit name='button7' //button for viewing your cart tha ttakes you to cat.php
                 value='Your Cart'/>";
  echo "</form>"; //end of cart.php entry form

  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";	//connecting to the database
    include("pswrds.php");	 //using password file
    $pdo1 = new PDO($dsn1, $username1, $password1);	//stating username and password
  }

//Error handler
  catch(PDOexception $exception1)
  {
    echo "Database connection failed: " . $exception1->getMessage();	//throw an error
  }

  try
  {
    $connection2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";	//connecting to the second database
    include("pswrds.php"); 			//using password file
    $pdo2 = new PDO($connection2, $username2, $password2);		//stating username and password
  }

//Handle an error and display it if there is one
  catch(PDOexception $exception2)
  {
    echo "Database connection failed: " . $exception2->getMessage();	//throw an error
  }

  ?>

  <?php

//Closing the catalog button with resubmission to self
  if (isset($_POST['button2']))
  {
    unset($_REQUEST['list']);
  }

  if(array_key_exists('list', $_REQUEST))
  {
    echo "<h1>Group 1A Product Catalog Page</h1>";	//header
	echo "<h3> COVID-19 Has NOT Affected Our Shipping Times!</h3>";
	echo "<h3> For more information during COVID-19 Please Contact US!</h3>";
   	
	  //test a query
	$sql1 = "SELECT * FROM parts"; 	//Retreieve all parts in the DB and display to user
    $query1 = $pdo2->query($sql1); 	//actualyl request to the server

    //get the row to print
    $rows = $query1->fetchAll(PDO::FETCH_ASSOC); 	//fetch all and store row in rows to be echo

    //use a table to print the results
    echo "<table width='40%'border=3, cellspacing=15,cellpadding=1>"; 	//table settings
	echo '<tbody style="background-color:#FF726F">'; 	//coloring
      echo "<tr>"; 			
        echo "<th>Product #</th>";
        echo "<th>Product Name</th>";
        echo "<th>More information</th>";
      echo "</tr>"; 				
      foreach($rows as $value) 		//displaying the database contents
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
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/U/moredetails.php>"; //button for taking to details.php page
              $a2s = base64_encode(serialize($value));
              echo "<input type=hidden name=pnum
                           value=$a2s/>";
              $a2s2 = base64_encode(serialize($cartitems));
              echo "<input type=hidden name='cart'
                           value=$a2s2/>";
              echo "<input type=submit name='$value[number]' // the number to request more inbfromation on
                           value='See More Information'/>";
            echo "</form>"; 	
          echo "</td>"; 	
        echo "</tr>"; 	
      }
    echo "</table>";
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


