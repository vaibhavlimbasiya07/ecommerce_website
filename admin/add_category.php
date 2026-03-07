<?php

include '../components/connect.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

$message = []; // Initialize as an array to avoid warnings

if (isset($_POST['add_category'])) {
   
   $cat_name = $_POST['cat_name'];
   
   if (empty($cat_name)) {
      $message[] = 'Category name is required!';
   } else {
      $select_category = mysqli_query($conn, "SELECT * FROM db.category WHERE cat_name = '$cat_name'");
      
      if (mysqli_num_rows($select_category) > 0) {
         $message[] = 'Category already exists!';
      } else {
         $insert_category = mysqli_query($conn, "INSERT INTO db.category (cat_name) VALUES('$cat_name')");
         
         if ($insert_category) {
            $message[] = 'New category added!';
         }
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categories</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>
   <?php include '../components/admin_header.php'; ?>
   
   
   <section class="add-products">

      <h1 class="heading">Add Category</h1>

      

      <form action="" method="post" enctype="multipart/form-data">
         <div class="flex">
            
            <div class="inputBox">
               <span>Category name</span>
               <input type="text" class="box" maxlength="100" placeholder="Enter Category name" name="cat_name">
            </div>
           
         </div>

         <input type="submit" value="Add Product" class="btn" name="add_category">
      </form>

   </section>

   <script src="../js/admin_script.js"></script>
</body>
</html>
