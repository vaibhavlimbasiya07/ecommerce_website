<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

if (isset($_GET['update'])) {
   $update_id = $_GET['update'];
}

// Update category
if (isset($_POST['update_category'])) {
   $cat_id = $_POST['cat_id'];
   $cat_name = $_POST['cat_name'];

   if (!empty($cat_name)) {
      mysqli_query($conn, "UPDATE db.category SET cat_name = '$cat_name' WHERE cat_id = '$cat_id'");
      $message[] = 'Category updated successfully!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Category</title>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>
<body>

<?php include '../components/admin_header.php'; ?>

<section class="update-product">
   <h1 class="heading">Update Category</h1>

   <?php
   $select_category = mysqli_query($conn, "SELECT * FROM db.category WHERE cat_id = '$update_id'");
   if (mysqli_num_rows($select_category) > 0) {
      while ($row = mysqli_fetch_assoc($select_category)) {
   ?>
         <form action="" method="post">
            <input type="hidden" name="cat_id" value="<?= $row['cat_id']; ?>">
            <span>Update Category Name</span>
            <input type="text" name="cat_name" required class="box" maxlength="100" placeholder="Enter new category name" value="<?= $row['cat_name']; ?>">
          
            <div class="flex-btn">
               <input type="submit" name="update_category" class="btn" value="Update">
               <a href="view_category.php" class="option-btn">Go Back</a>
            </div>
         </form>
   <?php
      }
   } else {
      echo '<p class="empty">No category found!</p>';
   }
   ?>

</section>

<script src="../js/admin_script.js"></script>
</body>
</html>
