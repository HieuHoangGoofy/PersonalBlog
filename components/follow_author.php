<?php

if(isset($_POST['follow_author'])){

   if($user_id != ''){
      
      
      $admin_id = $_POST['admin_id'];
      $admin_id = filter_var($admin_id, FILTER_SANITIZE_STRING);
      
      $select_author_follow = $conn->prepare("SELECT * FROM `follows` WHERE admin_id = ? AND user_id = ?");
      $select_author_follow->execute([$admin_id, $user_id]);

      if($select_author_follow->rowCount() > 0){
         $remove_like = $conn->prepare("DELETE FROM `follows` WHERE admin_id = ?");
         $remove_like->execute([$admin_id]);
         $message[] = 'removed from follows';
      }else{
         $add_like = $conn->prepare("INSERT INTO `follows`(user_id, admin_id) VALUES(?,?)");
         $add_like->execute([$user_id, $admin_id]);
         $message[] = 'added to follows';
      }
      
   }else{
         $message[] = 'please login first!';
   }

}

?>