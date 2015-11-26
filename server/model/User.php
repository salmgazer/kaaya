<?php session_start();

include "adb.php";
class User extends adb{

  //sign up
  function signUp($fullname, $email, $username, $phone, $photo, $user_password){
    $str_sql = "insert into user (user_id, fullname, email, username, phone, photo, user_password) values
    ('$email', '$fullname', '$email', '$username', '$phone', '$photo', '$user_password')";
    return $this->query($str_sql);
  }

  function signOut(){
    if(session_destroy()){
      return true;
    }else{
      return false;
    }
  }

//get details of a user based on his id
  function getUserDetailsById($user_id, $user_type){
    $this->checkUser();
    $str_sql = "";
    if($user_type == "ordinary"){
      $str_sql = "select * from user where user_id = '$user_id' AND type = '$user_type' limit 0,1";
    }else if($user_type == "artisan"){
      $str_sql = "select * from user inner join artisan on user.user_id = artisan.artisan_id where user.user_id = '$user_id'  AND artisan.artisan_id = '$user_id' limit 0,1";
    }
    $this->query($str_sql);
    $row = $this->fetch();
    if($row == null){
      return false;
    }
    return $row;
  }

//get details of a user based on username and password
  function getUserDetails($username, $password, $user_type){
    $str_sql = "";
    if($user_type == "ordinary"){
      $str_sql = "select * from user where username = '$username' AND user_password = '$password' limit 0,1";
    }else if($user_type == "artisan"){
      $str_sql = "select * from user inner join artisan on user.user_id = artisan.artisan_id where user.username = '$username' AND user.user_password = '$password' limit 0,1";
    }
    $this->query($str_sql);
    $row = $this->fetch();
    if($row == null){
      return false;
    }
    return $row;
  }

//fresh login of user with no session
  function loginUser($username, $password, $user_type){
    $row = $this->getUserDetails($username, $password, $user_type);
    if(!$row){
      return false;
    }
    else{
      $_SESSION['username'] = $row['username'];
      $_SESSION['fullname'] = $row['fullname'];
      $_SESSION['password'] = $row['user_password'];
      $_SESSION['photo'] = $row['photo'];
      $_SESSION['user_type'] = $row['type'];
      $_SESSION['phone'] = $row['phone'];
      $_SESSION['user_id'] = $row['user_id'];
      if($row['type'] == "artisan"){
        if(($row['location_latitude'] != null) && ($row['location_longitude'] != null)){
          $_SESSION['longitude'] = $row['location_longitude'];
          $_SESSION['latitude'] = $row['location_latitude'];
        }
      }
    }
    return $row;
  }

  //login user with session
  function loginUserSession($username, $password, $user_type){
    return loginUser($username, $password, $user_type);
  }

  function checkUser(){
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $user_type = $_SESSION['user_type'];
    $row = $this->getUserDetails($username, $password, $user_type);
    if(!$row){
      //redirect user to login page
      return false;
    }
    //do nothing :: user is genuine
  }

  function becomeArtisan($username, $community){
    $this->checkUser();
    $str_sql = "update user set type = 'artisan' where username = '$username'";
    if (!$this->query($str_sql)) {
      return false;
    }
    //$user_id = $_
    $str_sql = "insert into artisan(artisan_id, community) values ('".$_SESSION['user_id']."', '$community')";
    if(!$this->query($str_sql)){
      //Allow user to
      return false;
    }
    return true;
  }

  function getArtisansByCommunity($community){
    $this->checkUser();
    $str_sql = "select * from user inner join artisan on user.user_id = artisan.artisan_id where artisan.community like '$community'";
    $this->query($str_sql);
    $row = $this->fetch();
    if($row == null){
      return false;
    }
    return $row;
  }

  function getArtisanBySkill($skill){
    $this->checkUser();
    $str_sql = "select * from artisan inner join artisan_has_skill on artisan.artisan_id =
    artisan_has_skill.artisan_id inner join skill on artisan_has_skill.skill_id = skill.skill_id
     inner join user on user.user_id = artisan.artisan_id";
     $this->query($str_sql);
     $row = $this->fetch();
     if($row == null){
       return false;
     }
     return $row;
  }


}

//$user = new User();
//$details = $user->getUserDetails("tester4real", "tester123", "ordinary");
//echo $details['fullname'];
/*$login = $user->loginUser("salifu123", "mole123","artisan");
if($login){
  echo $login['fullname'];
}else{
  echo "not a user, fucker!!!";
}*/
//echo "User id is ".$_SESSION['user_id']."<br>";
//echo $user->signOut();
//echo "User id is ".$_SESSION['user_id'];

//echo $user->becomeArtisan("salifu123", "Nima");
//echo $user->getArtisansByCommunity("Berekuso")['fullname'];
/*$skill = $user->getArtisanBySkill("cooking");
if($skill){
  echo $skill['skill_name'];
}else{
  echo "Looser";
}*/
//signUp($fullname, $email, $username, $phone, $photo, $user_password)
//echo $user->signUp("John Coffey", "joe@gmail.com", "joe123", "0233456789", "user.png", 'joe4real');

?>
