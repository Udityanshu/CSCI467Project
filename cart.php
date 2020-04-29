<html><head><title>
      View Your Cart
    </title>
  </head>
<body bgcolor='FFA07A'>
<center>

  <?php
  //Attempt to connect to MariaDB Database
  try
  {
    $dsn1 = "mysql:host=courses;dbname=z1853066";
    include("Project8Apswrd.php"); //using main file
    $pdo1 = new PDO($dsn1, $username1, $password1);
  }

  //catch and handle error if there is one
  catch (PDOexception $e1)
  {
    echo "Database connection failure: " . $e1->getMessage();
  }

  //Attempt to connect to server with all data
  try
  {
    $dsn2 = "mysql:host=blitz.cs.niu.edu;dbname=csci467";
    include("Project8Apswrd.php"); //unsing main psswd file
    $pdo2 = new PDO($dsn2, $username2, $password2);
  }

  //Catahc and handle expection if tthere is one
//also display it
  catch (PDOexception $e2)
  {
    echo "Database connection failure: " . $e2->getMessage();
  }

  $cartcontents = array(); //create the array 

  if (array_key_exists('cart', $_REQUEST))
  {
    $cartcontents = unserialize(base64_decode($_REQUEST['cart'])); //unserialzie array
  }

  if (isset($_POST['changequantity'])) //if change quantity is selected on the cart page
  {
    $ind = intval($_POST['index']);
    $quan = intval($_POST['quantity']);
    $cartcontents[$ind]['qntty'] = $quan; //chane the quant
  }

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/catelog.php>"; //button for going back to catalog
    $arraytostring4 = base64_encode(serialize($cartcontents));
    echo "<input type=hidden name='cart'
                 value=$arraytostring4/>";
    echo "<input type=submit name='button5'
                 value='Return To The Catalog'/>"; //going back to catalog page 
  echo "</form>";

  echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/cart.php>";
    echo "<input type=submit name='button8'
                 value='Clear Your Cart'/>"; //cleaing the acrt and resubmit to cart.p]hp
  echo "</form>";

  if (array_key_exists('pnum', $_REQUEST))
  {
    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/details.php>"; // go to see item page where they just were
      echo "<input type=hidden name='pnum'
                   value=$_REQUEST[pnum]/>";
      $arraytostring5 = base64_encode(serialize($cartcontents));
      echo "<input type=hidden name='cart'
                   value=$arraytostring5/>";
      echo "<input type=submit name='button6'
                   value='Return to Previous Page (Additional Product Information Page)'/>"; //button for going back to prodcut information
    echo "</form>";
  }

  if (!empty($cartcontents))
  {
    $plist = array(); //
    $calclist = array(); // 
    $calcentry = array(); //
    foreach($cartcontents as $citem)
    {
      $sql1 = "SELECT * FROM parts WHERE number = $citem[prdctnum]"; //showing the products with ==product num
      $query1 = $pdo2->query($sql1);
      $rows = $query1->fetchAll(PDO::FETCH_ASSOC);
      array_push($plist, $rows[0]);
    }

    $count = 0;
    $itemprices = 0;
    $totalweight = 0;
    $addfees = 0;
    $finalprice = 0;
    foreach($cartcontents as $centry)
    {
      $sql2 = "SELECT quantity FROM inventory WHERE productID = $centry[prdctnum]"; //show the quantity
      $query2 = $pdo1->query($sql2);
      $rows2 = $query2->fetchAll(PDO::FETCH_ASSOC);
      $itemquant = $rows2[0]['quantity'];

      $pentry = $plist[$count];
      $calcentry = array("pid"=>($centry["prdctnum"]),
                   "des"=>($pentry["description"]),
                   "qty"=>($centry["qntty"]),
                   "tpr"=>($centry["qntty"] * $pentry["price"]),
                   "twe"=>($centry["qntty"] * $pentry["weight"]),
                   "mqy"=>($itemquant));
      array_push($calclist, $calcentry);

      //increment the summary variables
      $itemprices += ($centry["qntty"] * $pentry["price"]);
      $totalweight += ($centry["qntty"] * $pentry["weight"]);

      $count++;
    }

    //set the additional fees
    $sqladd = "SELECT * FROM admin ORDER BY bracket ASC";
    $queryadd = $pdo1->query($sqladd);
    $weightarray = $queryadd->fetchAll(PDO::FETCH_ASSOC);

    //set default charge
    if (count($weightarray) == 0)
    {
      $addfees = 0;
    }

    else
    {
      //$weightarray = $queryadd->fetchAll(PDO::FETCH_ASSOC);

      //set the additional fees
      foreach($weightarray as $weightbracket)
      {
        if ($totalweight <= $weightbracket['bracket'])
        {
          $addfees = $weightbracket['charge'];
          break;
        }
      }
    }

    $itemprices = round($itemprices, 2);
    $totalweight = round($totalweight, 2);
    $addfees = round($addfees, 2);
    $finalprice = round(($itemprices + $addfees), 2);

    echo "<h1>Review Your Items</h1>";

    echo "<table border=3, cellspacing=5, cellpadding=5>";
echo '<tbody style="background-color:#FF726f">';


      echo "<tr>"; //headers for table here
        echo "<th>Product ID</th>";
        echo "<th>Product Name</th>";
        echo "<th>Quantity Ordered</th>";
        echo "<th>Added Price ($)</th>";
        echo "<th>Added Weight (lbs)</th>";
        echo "<th>Change Quantity</th>";
        echo "<th>Delete?</th>";
        echo "<th>Go To Item Details</th>";
      echo "</tr>";

      $countcount = 0;
      foreach($calclist as $cinfo)
      {
        echo "<tr>";
        foreach($cinfo as $label=>$data)
        {
          if ($label != "mqy")
          {
            echo "<td>$data</td>"; //the data for the product
          }
        }

          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/cart.php>"; //information on changing settings on
//infomration on changing
//what is currently in the cart
              if (array_key_exists('pnum', $_REQUEST))
              {
                echo "<input type=hidden name='pnum'
                             value=$_REQUEST[pnum]/>";
              }

              $arraytostring9 = base64_encode(serialize($cartcontents));
              echo "<input type=hidden name='cart'
                           value=$arraytostring9/>";
              echo "<input type=hidden name='index'
                           value=$countcount/>";
              echo "<input type=number name='quantity'
                           min=1 max='$cinfo[mqy]'/>";
              echo "<input type=submit name='changequantity' //changin quantity
                           value='Change The Amount'/>";
            echo "</form>"; //end of changes made in the currrent caart
          echo "</td>";

          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/cart.php>";
              if (array_key_exists('pnum', $_REQUEST))
              {
                echo "<input type=hidden name='pnum'
                             value=$_REQUEST[pnum]/>";
              }

              $cartarray = array();
              foreach($cartcontents as $cartitem)
              {
               if ($cartitem['prdctnum'] != $cinfo['pid'])
               {
                 array_push($cartarray, $cartitem);
               }
              }
              $arraytostring8 = base64_encode(serialize($cartarray));
              echo "<input type=hidden name='cart'
                           value=$arraytostring8/>";
              echo "<input type=submit name=$cinfo[pid]
                           value='Delete'/>";
            echo "</form>";
          echo "</td>";
          echo "<td>";
            echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/details.php>";
              $arraytostring11 = base64_encode(serialize($plist[$countcount]));
              echo "<input type=hidden name='pnum'
                           value=$arraytostring11/>";
              $arraytostring10 = base64_encode(serialize($cartcontents));
              echo "<input type=hidden name='cart'
                          value=$arraytostring10/>";
              echo "<input type=submit name='revisit'
                           value='View Item'/>";
            echo "</form>";
          echo "</td>";
        echo "</tr>";
        $countcount++;
      }
    echo "</table>";

    echo "<h2>Total Order Statistics</h2>";
    echo "<table border=3, cellspacing=3, cellpadding=5>";
echo '<tbody style="background-color:#FF726f">';


      echo "<tr>"; //table headers
        echo "<th>Total Price ($) </th>";
        echo "<th>Total Weight (lbs)</th>";
        echo "<th>Additional Fees ($) </th>";
        echo "<th>Final Price ($) </th>";
      echo "</tr>";
      echo "<tr>";
        echo "<td>$itemprices</td>";
        echo "<td>$totalweight</td>";
        echo "<td>$addfees</td>";
        echo "<td>$finalprice</td>";
      echo "</tr>";
    echo "</table>";

    echo "<h2>Ready to Order? Continue to Cart.</h2>";

    echo "<form method=post action=http://students.cs.niu.edu/~z1853066/CSCI467/Main/checkout.php>";
//moving to the checkoutpage and pulling everything over
      $arraytostring12 = base64_encode(serialize($cartcontents));
      echo "<input type=hidden name='cart'
                   value=$arraytostring12/>";
      echo "<input type=hidden name='itemprices'
                   value=$itemprices/>";
      echo "<input type=hidden name='totalweight'
                   value=$totalweight/>";
      echo "<input type=hidden name='addfees'
                   value=$addfees/>";
      echo "<input type=hidden name='finalprice'
                   value=$finalprice/>";
      echo "<input type=submit name='gotocheckout'
                   value='Go To Checkout'/>";
    echo "</form>";
  }

  else
  {
    echo "Your Cart is empty";
  }
  ?>
</html>



<footer>

Thank You For Shopping With Us
<br>
COVID-19 Has NOT Affected Our Shipping Time!
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>
