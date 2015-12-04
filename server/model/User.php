<?php

include "adb.php";
class User extends adb{

  //sign up
  function signUp($fullname, $email, $username, $phone, $user_password){
    $str_sql = "insert into user (user_id, fullname, email, username, phone, user_password) values
('$email', '$fullname', '$email', '$username', '$phone', '$user_password')";
    return $this->query($str_sql);
  }

  function signOut(){
    session_destroy();
    return true;
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

//user currently logged in gets his details
function getUserDetailsBySession(){
  if(isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['user_type'])){
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    $user_type = $_SESSION['user_type'];
    return $this->getUserDetails($username, $password, $user_type);
  }
  return false;
}

//get details of a user based on username and password
  function getUserDetails($username, $password, $user_type){
    $str_sql = "";
    if($user_type == "ordinary"){
      $str_sql = "select * from user where username = '$username' AND user_password = '$password' AND type = '$user_type' limit 0,1";
    }else if($user_type == "artisan"){
      $str_sql = "select * from user inner join artisan on user.user_id = artisan.artisan_id where user.username = '$username' AND user.user_password = '$password' AND user.type = '$user_type' limit 0,1";
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
        if($row['community'] != null){
          $_SESSION['community'] = $row['community'];
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
    if(isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['user_type'])){
      $username = $_SESSION['username'];
      $password = $_SESSION['password'];
      $user_type = $_SESSION['user_type'];
      $row = $this->getUserDetails($username, $password, $user_type);
      if(!$row){
        //redirect user to login page
        //header("location ./");
        return false;
      }
    }else{
      return false;
    }
  }

  function becomeArtisan(){
    $this->checkUser();
    $user_id = $_SESSION['user_id'];
    $str_sql = "update user set type = 'artisan' where user_id = '$user_id'";
    if (!$this->query($str_sql)) {
      return false;
    }
    //$user_id = $_
    $str_sql = "insert into artisan(artisan_id) values ('$user_id')";
    //if(!$this->query($str_sql)){
    return $this->query($str_sql);
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

  function getArtisansBySkill($skill){
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

  function createJob($starting_price, $summary, $description, $community){
    $this->checkUser();
    if(isset($_SESSION['user_id'])){
      $assigner_id = $_SESSION['user_id'];
      $str_sql = "insert into job(assigner_id, starting_price, summary, description, community) values ('$assigner_id',
      '$starting_price', '$summary', '$description', '$community')";
      return $this->query($str_sql);
  }
    return false;
  }

  function updateProfile($newcommunity, $newphone, $newemail){
    $this->checkUser();
    $user_id = $_SESSION['user_id'];
    $str_sql = "";
    $user_type = $_SESSION['user_type'];

      $str_sql = "update user set phone = '$newphone', email = '$newemail' where user_id='$user_id'";
      $row = $this->query($str_sql);
      if($user_type == 'ordinary'){
        return $row;
      }
      $community = $_SESSION['community'];
      if($user_type == 'artisan' && $newcommunity != $_SESSION['community']){
        $str_sql = "update artisan set community = '$newcommunity' where artisan_id = '$user_id'";
        return $this->query($str_sql);
      }
    }

    function getArtisanSkills(){
      $this->checkUser();
      $artisan_id = $_SESSION['user_id'];
      $str_sql = "select skill.skill_name from skill inner join artisan_has_skill on skill.skill_id = artisan_has_skill.skill_id where artisan_id='$artisan_id'";
      $this->query($str_sql);
      $row = $this->fetch();
      if($row == null){
        return false;
      }
      return $row;
    }

    function addSkill($skill_name){
      $this->checkUser();
      $artisan_id = $_SESSION['user_id'];
      $str_sql = "select skill_id from skill where skill_name = '$skill_name' limit 0,1";
      $this->query($str_sql);
      $theskill = $this->fetch();
      //if skill does not exist in database already
      if($theskill == null){
        $str_sql = "insert into skill(skill_name) values ('$skill_name')";
        if($this->query($str_sql)){
          $str_sql = "select skill_id from skill where skill_name = '$skill_name'";
          $this->query($str_sql);
          $skill_id = $this->fetch();
          if($skill_id == null){
            return false;
          }
          $myskill_id = $skill_id['skill_id'];
          $str_sql = "insert into artisan_has_skill (artisan_id, skill_id) values ('$artisan_id', $myskill_id)";
          return $this->query($str_sql);
        }
      }
      //if skill exists already
      $myskill_id = $theskill['skill_id'];
      $str_sql = "insert into artisan_has_skill (artisan_id, skill_id) values ('$artisan_id', $myskill_id)";
      return $this->query($str_sql);
    }

}

/*$user = new User();
$skills = $user->getArtisanSkills();
while($skills){
  echo ($skills['skill_name']);
  $skills = $user->fetch();
}*/
//echo $_SESSION['fullname'];
//echo $user->createJob(210, "I need my car washed", "I live in a muddy area, so car easily gets dirty. You are gonna wipe out all the mud");

//$details = $user->getUserDetails("tester4real", "tester123", "ordinary");
//echo $details['fullname'];
/*$login = $user->loginUser("salifu123", "mole123","artisan");
if($login){
  echo $login['fullname'];
}else{
  echo "not a user, fucker!!!";
}*/
//echo "User fullname is ".$_SESSION['fullname']."<br>";
//echo $user->getUserDetailsBySession()['fullname'];
//echo $user->getUserDetails($_SESSION['username'], $_SESSION['password'], $_SESSION['user_type']);
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
