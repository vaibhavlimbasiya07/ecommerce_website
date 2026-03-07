<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
   exit();
}

// Fetch user details from the database

$number = '';
$email = '';

$query = mysqli_query($conn, "SELECT email, phone_no  FROM db.users WHERE id = '$user_id'");
if ($query && mysqli_num_rows($query) > 0) {
   $result = mysqli_fetch_assoc($query);
   $number = $result['phone_no'];
   $email = $result['email'];
}

if (isset($_POST['order'])) {
   if (empty($_POST['flat']) || empty($_POST['street']) || empty($_POST['city']) || empty($_POST['pin_code'])) {
      $warning_msg[] = 'All address fields are required!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <?php include 'components/user_header.php'; ?>

   <section class="checkout-orders">
      <form action="pay.php" method="POST">
         <h3>Your Orders</h3>
         <div class="display-orders">
            <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = mysqli_query($conn, "SELECT * FROM db.cart WHERE user_id = '$user_id'");
            if (mysqli_num_rows($select_cart) > 0) {
               while ($row = mysqli_fetch_assoc($select_cart)) {
                  $cart_items[] = $row['name'] . ' (' . $row['price'] . ' x ' . $row['quantity'] . ')';
                  $grand_total += ($row['price'] * $row['quantity']);
            ?>
                  <p> <?= $row['name']; ?> <span>(<?= '₹' . $row['price'] . '/- x ' . $row['quantity']; ?>)</span> </p>
            <?php
               }
               $total_products = implode(', ', $cart_items);
            } else {
               echo '<p class="empty">Your cart is empty!</p>';
            }
            ?>
            <input type="hidden" name="total_products" value="<?= $total_products; ?>">
            <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
            <div class="grand-total">Grand Total: <span>₹<?= $grand_total; ?>/-</span></div>
         </div>
         <h3>Place Your Order</h3>
         <div class="flex">
            <div class="inputBox">
               <span>Your Name :</span>
               <input type="text" name="name" class="box" placeholder="Enter your name"  maxlength="20" required>
            </div>
            <div class="inputBox">
               <span>Mobile Number :</span>
               <input type="text" name="number" class="box" value="<?= htmlspecialchars($number); ?>" readonly>
            </div>
            <div class="inputBox">
               <span>Your Email :</span>
               <input type="email" name="email" class="box" value="<?= htmlspecialchars($email); ?>" readonly>
            </div>
            <div class="inputBox">
               <span>Address Line 01 :</span>
               <input type="text" name="flat" placeholder="e.g. Flat Number" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Address Line 02 :</span>
               <input type="text" name="street" placeholder="e.g. Street Name" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Area :</span>
               <input type="text" name="city" placeholder="e.g. Nikol" class="box" maxlength="50" required>
            </div>
            <div class="inputBox">
               <span>Pin Code :</span>
               <input type="number" name="pin_code" placeholder="e.g. 123456" min="0" max="999999" onkeypress="if(this.value.length == 6) return false;" class="box" required>
            </div>
         </div>
         <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Place Order">
      </form>
   </section>

   <?php include 'components/footer.php'; ?>
   <script src="js/script.js"></script>
</body>
</html>
