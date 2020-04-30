
<html><head><title>
      Warehouse Group 1A
    </title>
  </head>

<center>
<body bgcolor='FFA07A'>
<h1> Warehouse View (Employees Only)</h1>
  <?php
    require("connection.php"); //the connection php file for  connecting to servers for workers

    //try adding form to select order id:
    echo '<form action="warehouse.php" method="post">';
      echo 'Order ID: <input type=text name=orderid id=orderid><br>'; //input box for order id number
      echo '<br><input type="submit" name="submit" value="Submit"><br>'; //submit button
    echo '</form>';


    if($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $orderid = $_POST['orderid'];
        //////////////////////////////
        $statement= $pdo1->query('select * from ordereditems where orderID = '.$orderid); // add 'where orderid = 'the order we're looking for'; get order id from form the user fills out
        echo '<table border="5", cellspacing=5, cellpadding=5>';
echo '<tbody style="background-color:#FF726F">'; //colorring
//table settings

        echo '<th>Order ID</th>';
        echo '<th>Product Name</th>';
        echo '<th>Product ID </th>';
        echo '<th>Quantity</th>';

        //displaying the information from the query in the table
        while($row = $statement->fetch(PDO::FETCH_ASSOC))
        {
          $productid = $row['productID'];
          $stmtgetdescription = $pdo2->query('select description from parts where number = '.$productid);
          // 'select quantity from orderedItems where orderID'

          while($description = $stmtgetdescription->fetch(PDO::FETCH_ASSOC))
          {
            echo '<tr><td>';
            echo $row['orderID']; //display orderID
            echo '</td><td> ';
            echo $description['description']; //display description
            echo '</td><td> ';
            echo $row['productID']; //display productID
            echo '</td><td> ';
            echo $row['quantity']; //display quantity
            echo '</td></tr>';
          }

        } 

        echo '</table>';
        echo '</div>';

    }
   
  ?>
</html>

<footer>
<br><br>
Thank You For Shopping With Us
<br>
For more information during COVID-19 Please Contact US!
<br>
CSCI 467 - Group 1A

</footer>










