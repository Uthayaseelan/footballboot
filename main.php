<?php
function connect(){
 $host = "localhost";
    $user = "footballboot";
    $pass = "pa$$";
    $db = "footballboot";
    
    $conn = mysqli_connect($host, $user, $pass, $db);
    
    return $conn;
}

function subscribe($email){
 $conn = connect();
    $insert = "INSERT INTO fb_newsletter VALUES('$email')";
    mysqli_query($conn, $insert);
    mysqli_close($conn);
    header("Location: index.html");
}

function register($email, $fname, $sname, $pcode, $pass) {
    $conn = connect();
    $insert = "INSERT INTO fb_customer VALUES('$email', '$fname', '$sname', '$pcode', '$pass')";
    mysqli_query($conn, $insert);
    mysqli_close($conn);
    header ("Location: index.html");
}

function login($email, $pass){
    $conn = connect();
    $select = "SELECT * FROM fb_customer WHERE email='$email' AND pass='$pass'";
    $result = mysqli_query($conn, $select);
    if(mysqli_num_rows($result)===1) {
     session_start();
        $_SESSION["user"]=$email;
        header("Location: index.html");
    }else {
     $msg = "Your username or password is incorrent. Please try again.";
        echo "<script type = 'text/javascript'>
        alert('$msg');
        window.location = 'register.html';
        </script>";
    }
}

function display_products(){
    $conn = connect();
    $select = "SELECT * FROM fb_product";
    $results = mysqli_query($conn, $select);
    echo "<table border='3px'><tr>
    <th>Product Name</th>
    <th>Product Description</th>
    <th>Images</th>
    <th>Price</th>
    <th>Order</th>
    </tr></table";
    while ($row = mysqli_fetch_array($results) ) {
     echo "<tr>
     <td>$row[name]</td>
     <td>$row[description]</td>
     <td><img src= '$row[imagepath]' width='140' height='100' /></td>
     <td>$row[price]</td>
     <td><form action = 'basket.php' method = 'post'>
     <input type = 'submit' value = 'Add to basket' name = '$row[pid]' />
     </form></td>
     </tr>";
    }
    echo "</table>";
}

function add_to_basket($pid){
    session_start();
    if (isset($_SESSION['basket'])) {
        if ( isset($_SESSION['basket'][$pid]) ) {
         $_SESSION['basket'][$pid]++;  
        }else {
         $_SESSION['basket'][$pid] =1;   
        }
    }else {
        $_SESSION['basket'] = array ($pid => 1);
    }
    header ("Location: basket.html");
}

function display_basket() {
 if(!isset($_SESSION['basket']) ) {
  echo "<h2> Your basket is empty. Go to the produt page to order items. </h2>";
      return;
 }
    
    echo "<table border='4px'><tr>
    <th>Product Name</th>
    <th>Quantity</th>
    <th>Price</th>
    <th>Subtotal</th>
    </tr>";
    $conn = connect();
    $total = 0;
    foreach ($_SESSION['basket'] as $key=>$value) {
     $select = "SELECT name, price FROM fb_product WHERE pid=$key";
        $result = mysqli_query($conn, $select);
        $row = mysqli_fetch_array($result);
        echo "<tr>
        <td>$row[name]</td>
        <td>$value</td>
        <td>$row[price]</td>
        <td>". number_format($value*$row['price'], 2, '.', '')."</td>
        </tr>";
        $total = $total + $value*$row['price'];
    }
    echo"</table>";
    mysqli_close($conn);
    echo "<table><tr>
    <th>Total</th>
    <th>Order</th>
    </tr>
    <tr>
    <td>".number_format($total, 2, '.', '') ."</td>
    <td><form action= 'order.php' method = 'post'><input type = 'submit' value= 'order' /></form></td>
    </tr>
    </table>";
}

function order(){
 session_start();
    if(!isset($_SESSION['user'])) {
        $msg = "You must be logged in to order items.";
        echo "<script type = 'text/javascript'>
        alert('$msg');
        window.location = 'register.html';
        </script>";
    }
    $conn = connect();
    $insert = "INSERT INTO fb_order VALUES(NULL, '$_SESSION[user]')";
    mysqli_query($conn, $insert);
    $oid = mysqli_insert_id($conn);
    
    foreach ($_SESSION['basket'] as $key=>$value) {
     $insert = "INSERT INTO fb_orderitems VALUES($oid, $key, $value)";
        mysqli_query($conn, $insert);
    }
    unset($_SESSION['basket']);
    mysqli_close($conn);
    $msg = "Thank you for ordering with us. You order has been received.";
        echo "<script type = 'text/javascript'>
        alert('$msg');
        window.location = 'index.html';
        </script>";
}

?>