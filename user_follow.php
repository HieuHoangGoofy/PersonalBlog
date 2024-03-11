<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/follow_author.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Follow Author</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->


<section class="authors">

   <h1 class="heading">Follow authors</h1>

   <div class="box-container">

   <?php
      $select_authorsfollow = $conn->prepare("SELECT * FROM `follows` WHERE user_id = ?");
      $select_authorsfollow->execute([$user_id]);
      if($select_authorsfollow->rowCount() > 0){
      while($fetch_authorsfollow = $select_authorsfollow->fetch(PDO::FETCH_ASSOC)){
      $select_authors = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
      $select_authors->execute([$fetch_authorsfollow['admin_id']]);
      //CTN
      if($select_authors->rowCount() > 0){
         while($fetch_authors = $select_authors->fetch(PDO::FETCH_ASSOC)){ 

            $count_admin_posts = $conn->prepare("SELECT * FROM `posts` WHERE admin_id = ?");
            $count_admin_posts->execute([$fetch_authors['id']]);
            $total_admin_posts = $count_admin_posts->rowCount();

            $count_admin_likes = $conn->prepare("SELECT * FROM `likes` WHERE admin_id = ?");
            $count_admin_likes->execute([$fetch_authors['id']]);
            $total_admin_likes = $count_admin_likes->rowCount();

            $count_admin_comments = $conn->prepare("SELECT * FROM `comments` WHERE admin_id = ?");
            $count_admin_comments->execute([$fetch_authors['id']]);
            $total_admin_comments = $count_admin_comments->rowCount();

            /*$admin_id = $fetch_authors['id'];
            $confirm_follows = $conn->prepare("SELECT * FROM `follows` WHERE user_id = ? AND admin_id = ?");
            $confirm_follows->execute([$user_id, $admin_id]);*/

   ?>
   <form class="box" method="post">
   <input type="hidden" name="admin_id" value="<?= $fetch_authors['id']; ?>">
      <p>author : <span><?= $fetch_authors['name']; ?></span></p>
      <p>total posts : <span><?= $total_admin_posts; ?></span></p>
      <p>posts likes : <span><?= $total_admin_likes; ?></span></p>
      <p>posts comments : <span><?= $total_admin_comments; ?></span></p>
      <a href="author_posts.php?author=<?= $fetch_authors['name']; ?>" class="btn">view posts</a>
      <button class = "btn" type="submit" name="follow_author">Unfollow</button>
   </form>
   <?php
            }
         }
      }

   }else{
      echo '<p class="empty">no authors found</p>';
   }
   ?>

   </div>
      
</section>











<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>