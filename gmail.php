<?php
include("includes/config.php");
//include("includes/session.php")
if(isset($_POST["submit"])){
  $email= $_POST['mail'];
  $token=md5(rand());

  $check_email="SELECT EmailId,FirstName from tblemployees where  EmailId='$email' limit 1";
  $check_email_run=mysqli_query($conn,$check_email);
  if(mysqli_num_rows($check_email_run)>0)
  {
     $row=mysqli_fetch_array($check_email_run);
     $get_name=$row["FirstName"];
     $get_email=$row["EmailId"];

     $update_token="UPDATE tblemployees set token='$token' where EmailId='$get_email' limit 1";
     $update_token_run=mysqli_query($conn,$update_token);

     if($update_token_run){
       $to_email =$_POST["mail"];
       $subject = "reset password";
       $body = "click here on the link to reset your password 
                'http://localhost/vjit_leave/password_reset.php?token=$token&email=$email'
                ";
       //$admin="SELECT username from admin";
       $headers = "From: snaveengoud7799@gmail.com";
       if(mail($to_email, $subject, $body, $headers)){
        //  if(mail($to_email, $subject, $body, $headers)){
          //echo"<script><alert><" mail sent to " .$to_mail..></script>";
          //header("location: index1.php");
         echo "<script>alert('we emailed to you the password reset link')</script>";
      // $_SESSION['status']="we emailed to you the password reset link";
       header("location:index1.php");
       exit(0);
      }
     }else{
       $_SESSION['status']="something went wrong";
       header("location:mail1.html?token=$token&email=$email");
       exit(0);
     }
  }else{
      $_SESSION['status']="no email found";
      header("location:mail1.html?token=$token&email=$email");
      exit(0);
  }

}

//include("includes/config.php");
if(isset($_post['update'])){
    $email=mysqli_real_escape_string($conn,$_POST['email1']);
    $new_password=mysqli_real_escape_string($conn,$_POST['new']);
    $confirm_password=mysqli_real_escape_string($conn,$_POST['confirm']);
   $token1=mysqli_real_escape_string($conn,$_POST['password_token']);

   if(!empty($token1)){
        if(!empty($email)&&!empty($new_password)&&!empty($confirm_password)){
          $check_token="SELECT token from tblemployees where  token=$token1 limit 1";
          $check_token_run=mysqli_query($conn,$check_token);
     
               if(mysqli_num_rows($check_token_run)>0){
                 if($new_password==$confirm_password){
                        $update_password="UPDATE tblemployees SET Password=$new_password where token='$token1' limit 1";
                        $update_passsword_run=mysqli_query($conn,$update_password);
                    if(mysqli_num_rows($update_passsword_run)>0){
                      $new_token=md5(rand());
                      $update_to_new_token="UPDATE tblemployees set token=$new_token where token='$token1' limit 1";
                      $update_to_new_token_run=mysqli_query($conn,$update_to_new_token);
                      if($update_to_new_token_run){
                         //$_SESSION['status']=
                          echo"<script>alert('password updated succcessfully')</script>";
                      header("location:index1.php");
                      exit(0);}
                      
                    }else{
                      echo"<script>alert('password did notupdated succcessfully')</script>";
                                  //$_SESSION['status']="did not update the password";
                                       header("location:index1.php?token=$token&email=$email");
                                          exit(0);
                             }
                 }else{
          
                             //  $_SESSION['status']=";;;;;;;;;;;;;;;;;;; n ;;;/
                               echo"<script>alert('password and cofirm password does not match')";
                                  header("location:password_reset.php?token=$token&email=$email");
                                           exit(0);
                         }
               }

              }else{
          
                             $_SESSION['status']="all fields are mandetoryy";
                            header("location:password_reset.php?token=$token&email=$email");
                        exit(0);
                       }
            }else{
                           $_SESSION['status']="no token available";
                    header("location:password_reset.php?token=$token&email=$email");
                    exit(0);}
       

  }
?>