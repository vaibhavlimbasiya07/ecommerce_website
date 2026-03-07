<?php
include '../components/connect.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:login.php');
}

$message = [];

// Show message from session (if exists)
if (isset($_SESSION['message'])) {
   $message[] = $_SESSION['message'];
   unset($_SESSION['message']); // Clear message after displaying
}

// Delete category
if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM db.category WHERE cat_id = '$delete_id'");
   $_SESSION['message'] = 'Category Deleted!';  // Store message in session
   header('location:view_category.php');
   exit(); // Stop script execution after redirect
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Categories</title>

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="../css/admin_style.css">
</head>

<body>

<?php include '../components/admin_header.php'; ?>

<section class="display-product-table">
   <?php
   // Display messages
   if (!empty($message)) {
     
   }
   ?>
   
   <table>
      <thead>
         <th style="width: 200px;">Category ID</th>
         <th>Category Name</th>
         <th style="width: 200px;">Action</th>
      </thead>
      <tbody>
      <?php
         $select_categories = mysqli_query($conn, "SELECT * FROM db.category ORDER BY cat_id DESC");
         if (mysqli_num_rows($select_categories) > 0) {
            while ($row = mysqli_fetch_assoc($select_categories)) {
         ?>
               <tr>
                  <td><?= $row['cat_id']; ?></td>
                  <td><?= $row['cat_name']; ?></td>
                  <td>
                     <a href="view_category.php?delete=<?= $row['cat_id']; ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this?');">
                        <i class="fas fa-trash"></i> Delete
                     </a>
                     <a href="update_category.php?update=<?= $row['cat_id']; ?>" class="option-btn">
                        <i class="fas fa-edit"></i> Update
                     </a>
                  </td>       
               </tr>
         <?php
            }
         } else {
            echo "<tr><td colspan='3' class='empty'>No categories found</td></tr>";
         }
         ?>
      </tbody>
   </table>
</section>

<script src="../js/admin_script.js"></script>

</body>
</html>
