<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

if(isset($_GET['category'])){
   $category = $_GET['category'];
}else{
   $category = '';
}

include 'components/like_post.php';
include 'components/dislike_post.php';
include 'components/follow_author.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<!-- header section starts  -->
<?php include 'components/user_header.php'; ?>
<!-- header section ends -->


<section class="posts-container">

   <h1 class="heading">post categories</h1>

   <div class="box-container">

      <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE category = ? and status = ?");
         $select_posts->execute([$category, 'active']);
         if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
               
            $post_id = $fetch_posts['id'];

            $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
            $count_post_comments->execute([$post_id]);
            $total_post_comments = $count_post_comments->rowCount(); 

            $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
            $count_post_likes->execute([$post_id]);
            $total_post_likes = $count_post_likes->rowCount();

            $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
            $confirm_likes->execute([$user_id, $post_id]);

            $count_post_dislikes = $conn->prepare("SELECT * FROM `dislikes` WHERE post_id = ?");
            $count_post_dislikes->execute([$post_id]);
            $total_post_dislikes = $count_post_dislikes->rowCount();

            $confirm_dislikes = $conn->prepare("SELECT * FROM `dislikes` WHERE user_id = ? AND post_id = ?");
            $confirm_dislikes->execute([$user_id, $post_id]);

            $admin_id = $fetch_posts['admin_id'];
            $confirm_follows = $conn->prepare("SELECT * FROM `follows` WHERE user_id = ? AND admin_id = ?");
            $confirm_follows->execute([$user_id, $admin_id]);
      ?>
      <form class="box" method="post" style="<?php if($confirm_follows->rowCount() > 0){ echo 'border-color: var(--follow-color); border-width: 5px;'; } ?>">
         <input type="hidden" name="post_id" value="<?= $post_id; ?>">
         <input type="hidden" name="admin_id" value="<?= $fetch_posts['admin_id']; ?>">
         <div class="post-admin">
            <i class="fas fa-user"></i>
            <div>
               <a href="author_posts.php?author=<?= $fetch_posts['name']; ?>"><?= $fetch_posts['name']; ?></a>
               <a><?php if($confirm_follows->rowCount() > 0){ echo '<span class="fas fa-check-circle" style=" padding-right:0px; font-size: 11px; color:var(--btn-color); border:none; background:none;" style="color: green;"> Following</span>'; } ?> </a>
               <div><?= $fetch_posts['date']; ?></div>
            </div>
         </div>
         
         <?php
            if($fetch_posts['image'] != ''){  
         ?>
         <img src="uploaded_img/<?= $fetch_posts['image']; ?>" class="post-image" alt="">
         <?php
         }
         ?>
         <div class="post-title"><?= $fetch_posts['title']; ?></div>
         <div class="post-content content-150"><?= $fetch_posts['content']; ?></div>
         <a href="view_post.php?post_id=<?= $post_id; ?>" class="inline-btn">read more</a>
         <div class="icons">
            <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span></a>
            <a style="display: block;"><button class = "b1" style="margin-right: 10px" type="submit" name="like_post"><i class="fas fa-thumbs-up" style="<?php if($confirm_likes->rowCount() > 0){ echo 'color:var(--red);'; } ?>  "></i><span>(<?= $total_post_likes; ?>)</span></button>
            <button class = "b1" style="margin-right: 10px" type="submit" name="dislike_post"><i class="fas fa-thumbs-down	" style="<?php if($confirm_dislikes->rowCount() > 0){ echo 'color:var(--red);'; } ?>  "></i><span>(<?= $total_post_dislikes; ?>)</span></button>
            <button class = "b1" type="submit" name="follow_author"><i class="far fa-plus-square" style="<?php if($confirm_follows->rowCount() > 0){ echo 'color:var(--orange);'; } ?>  "></i></button></a>
         </div>
      
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">no posts found for this category!</p>';
      }
      ?>
   </div>

</section>










<?php include 'components/footer.php'; ?>







<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>