<?php

if(isset($_POST['like_post'])){

   if($user_id != ''){
      
      $post_id = $_POST['post_id'];
      $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
      $admin_id = $_POST['admin_id'];
      $admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);
      
      $select_post_like = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ? AND user_id = ?");
      $select_post_like->execute([$post_id, $user_id]);

      $select_post_dislike = $conn->prepare("SELECT * FROM `dislikes` WHERE post_id = ? AND user_id = ?");
      $select_post_dislike->execute([$post_id, $user_id]);

      if($select_post_like->rowCount() > 0){
         $remove_like = $conn->prepare("DELETE FROM `likes` WHERE post_id = ?");
         $remove_like->execute([$post_id]);
         $message[] = 'removed from likes';
      }else{
         if($select_post_dislike->rowCount() > 0){
            $remove_like = $conn->prepare("DELETE FROM `dislikes` WHERE post_id = ?");
            $remove_like->execute([$post_id]);
            $message[] = 'removed from dislikes';
         }
         $add_like = $conn->prepare("INSERT INTO `likes`(user_id, post_id, admin_id) VALUES(?,?,?)");
         $add_like->execute([$user_id, $post_id, $admin_id]);
         $message[] = 'added to likes';
      }
      
   }else{
         $message[] = 'please login first!';
   }

}

?>