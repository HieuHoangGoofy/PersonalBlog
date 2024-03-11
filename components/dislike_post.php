<?php

if(isset($_POST['dislike_post'])){

   if($user_id != ''){
      
      $post_id = $_POST['post_id'];
      $post_id = filter_var($post_id, FILTER_SANITIZE_STRING);
      $admin_id = $_POST['admin_id'];
      $admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);
      
      $select_post_like = $conn->prepare("SELECT * FROM `likes` WHERE post_id = ? AND user_id = ?");
      $select_post_like->execute([$post_id, $user_id]);

      $select_post_dislike = $conn->prepare("SELECT * FROM `dislikes` WHERE post_id = ? AND user_id = ?");
      $select_post_dislike->execute([$post_id, $user_id]);

      if($select_post_dislike->rowCount() > 0){
         $remove_dislike = $conn->prepare("DELETE FROM `dislikes` WHERE post_id = ?");
         $remove_dislike->execute([$post_id]);
         $message[] = 'removed from dislikes';
      }else{
        if($select_post_like->rowCount() > 0){
            $remove_like = $conn->prepare("DELETE FROM `likes` WHERE post_id = ?");
            $remove_like->execute([$post_id]);
            $message[] = 'removed from likes';
         }
         $add_dislike = $conn->prepare("INSERT INTO `dislikes`(user_id, post_id, admin_id) VALUES(?,?,?)");
         $add_dislike->execute([$user_id, $post_id, $admin_id]);
         $message[] = 'added to dislikes';
      }
      
   }else{
         $message[] = 'please login first!';
   }

}

?>