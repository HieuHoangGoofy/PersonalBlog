<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

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
   <title>Blog</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.0/css/line.css">
   <link rel="stylesheet" href="css/style.css">
   
</head>
<body>



<?php include 'components/user_header.php'; ?>

<section class="home-grid">

   <div class="box-container">

      <div class="box">
         <?php
            $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
            $select_profile->execute([$user_id]);
            if($select_profile->rowCount() > 0){
               $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
               $count_user_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
               $count_user_comments->execute([$user_id]);
               $total_user_comments = $count_user_comments->rowCount();
               $count_user_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ?");
               $count_user_likes->execute([$user_id]);
               $total_user_likes = $count_user_likes->rowCount();
               $count_user_dislikes = $conn->prepare("SELECT * FROM `dislikes` WHERE user_id = ?");
               $count_user_dislikes->execute([$user_id]);
               $total_user_dislikes = $count_user_dislikes->rowCount();
               $count_user_follows = $conn->prepare("SELECT * FROM `follows` WHERE user_id = ?");
               $count_user_follows->execute([$user_id]);
               $total_user_follows = $count_user_follows->rowCount();
         ?>
         <p> Welcome <span><?= $fetch_profile['name']; ?></span></p>
         <p>Total comments : <span><?= $total_user_comments; ?></span></p>
         <p>Posts liked : <span><?= $total_user_likes; ?></span></p>
         <p>Posts disliked : <span><?= $total_user_dislikes; ?></span></p>
         <p>Authors follows : <span><?= $total_user_follows; ?></span></p>
         <a href="update.php" class="btn">Update profile</a>
         <div class="flex-btn">
            <a href="user_likes.php" class="option-btn">Likes</a>
            <a href="user_comments.php" class="option-btn">Comments</a>
         </div>

         <div class="flex-btn">
            <a href="user_dislikes.php" class="option-btn">Dislikes</a>
            <a href="user_follow.php" class="option-btn">Follows</a>
         </div>
         <a href="components/user_logout.php" onclick="return confirm('logout from this website?');" class="btn" style = "background-color:#FF2F22
">logout</a>
         <?php
            }else{
         ?>
            <p class="name">Login as:</p>
            <div class="flex-btn">
               <a href="login.php" class="option-btn">user</a>
               <a href="admin\admin_login.php" class="option-btn">author</a>
            </div> 
         <?php
          }
         ?>
      </div>

      <div class="box">
         <p>Authors</p>
         <div class="flex-box">
         <?php
            $select_authors = $conn->prepare("SELECT DISTINCT name FROM `admin` LIMIT 10");
            $select_authors->execute();
            if($select_authors->rowCount() > 0){
               while($fetch_authors = $select_authors->fetch(PDO::FETCH_ASSOC)){ 
         ?>
            <a href="author_posts.php?author=<?= $fetch_authors['name']; ?>" class="links"><?= $fetch_authors['name']; ?></a>
            <?php
            }
         }else{
            echo '<p class="empty">no posts added yet!</p>';
         }
         ?>  
         <a href="authors.php" class="btn">view all</a>
         </div>
      </div>

      <div class="box">
         <p>Categories</p>
         <div class="flex-box">
            <a href="category.php?category=nature" class="links">Nature</a>
            <a href="category.php?category=education" class="links">Education</a>
            <a href="category.php?category=business" class="links">Business</a>
            <a href="category.php?category=travel" class="links">Travel</a>
            <a href="category.php?category=news" class="links">News</a>
            <a href="category.php?category=gaming" class="links">Gaming</a>
            <a href="category.php?category=sports" class="links">Sports</a>
            <a href="category.php?category=design" class="links">Design</a>
            <a href="category.php?category=fashion" class="links">Fashion</a>
            <a href="category.php?category=persional" class="links">Personal</a>
            <a href="all_category.php" class="btn">view all</a>
         </div>
      </div>

      

      

   </div>

</section>

<section class="posts-container">

   <h1 class="heading">latest posts</h1>

   <div class="box-container">

      <?php
         $select_posts = $conn->prepare("SELECT * FROM `posts` WHERE status = ? LIMIT 6 ");
         $select_posts->execute(['active']);
         

         if($select_posts->rowCount() > 0){
            while($fetch_posts = $select_posts->fetch(PDO::FETCH_ASSOC)){
               
               $post_id = $fetch_posts['id'];

               $count_post_comments = $conn->prepare("SELECT * FROM `comments` WHERE post_id = ?");
               $count_post_comments->execute([$post_id]);
               $total_post_comments = $count_post_comments->rowCount(); 

               $count_post_likes = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ?");
               $count_post_likes->execute([$post_id]);
               $total_post_likes = $count_post_likes->rowCount();

               $count_post_dislikes = $conn->prepare("SELECT * FROM `dislikes` WHERE post_id = ?");
               $count_post_dislikes->execute([$post_id]);
               $total_post_dislikes = $count_post_dislikes->rowCount();

               $confirm_likes = $conn->prepare("SELECT * FROM `likes` WHERE user_id = ? AND post_id = ?");
               $confirm_likes->execute([$user_id, $post_id]);

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
         <a href="category.php?category=<?= $fetch_posts['category']; ?>" class="post-cat"> <i class="fas fa-tag"></i> <span><?= $fetch_posts['category']; ?></span></a>
         <div class="icons">
            <a href="view_post.php?post_id=<?= $post_id; ?>"><i class="fas fa-comment"></i><span>(<?= $total_post_comments; ?>)</span></a>
            <a style="display: block;"><button class = "b1" style="margin-right: 10px" type="submit" name="like_post"><i class="fas fa-thumbs-up" style="<?php if($confirm_likes->rowCount() > 0){ echo 'color:var(--btn-color);'; } ?>  "></i><span>(<?= $total_post_likes; ?>)</span></button>
            <button  class = "b1" style="margin-right: 10px" type="submit" name="dislike_post"><i class="fas fa-thumbs-down" style="<?php if($confirm_dislikes->rowCount() > 0){ echo 'color:var(--red);'; } ?>  "></i><span>(<?= $total_post_dislikes; ?>)</span></button>
            <button class = "b1" type="submit" name="follow_author"><i class="far fa-plus-square" style="<?php if($confirm_follows->rowCount() > 0){ echo 'color:var(--orange);'; } ?>  "></i></button></a>
         </div>
      
      </form>
      <?php
         }
      }else{
         echo '<p class="empty">no posts added yet!</p>';
      }
      ?>
   </div>

   <div class="more-btn" style="text-align: center; margin-top:1rem;">
      <a href="posts.php" class="inline-btn">view all posts</a>
   </div>

</section>



















<?php include 'components/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="js/script.js"></script>

</body>
</html>