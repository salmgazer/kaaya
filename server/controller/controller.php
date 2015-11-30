<?php

if(!isset($_REQUEST['cmd'])){
  echo '{"result": 0, "message": "Command unknown"}';
  return;
}

$cmd = $_REQUEST['cmd'];

switch ($cmd) {
  case 1:
    signUp();
    break;

  case 2:
    loginUser();
    break;

  case 3:
    signOut();
    break;

  case 4:
    loginUserSession();
    break;

  case 5:
    getUserDetailsById();
    break;

  case 6:
    getArtisansBySkill();
    break;

  case 7:
    getArtisansByCommunity();
    break;

  case 8:
    becomeArtisan();
    break;

  default:
    echo '{"result": 0, "message": "Command unknown"}';
    return;
    break;
}
include_once "User.php";
$user = new User();
function signUp(){
  $fullname = $_REQUEST['fullname'];
  $email = $_REQUEST['email'];
  $username = $_REQUEST['username'];
  //get picture here
  $photo = $_REQUEST['photo'];
  $phone = $_REQUEST['phone'];
  $user_password = $_REQUEST['password'];

  if($user->signUp($fullname, $email, $username, $phone, $photo, $user_password)){
    //successfully added send email for confirmation
    sendEmail($sender, $receipient, $subject, $message);

    //response alert
    echo '{"result": 1, "message": "Your have successfully signed up"}';
    return;
    //redirect to login page
  }else{
    echo '{"result: 0, "message": "Signup was unsuccessful. Try again."}';
    return;
  }
}

function sendEmail($sender, $receipient, $subject, $message){

}

function loginUser(){
  $username = $_REQUEST['username'];
  $password = $_REQUEST['password'];
  $user_type = $_REQUEST['user_type'];
   if(!$user->loginUser($username, $password, $user_type)){
     //login is unsuccessful
     echo '{"result": 0, "message": "Login is unsuccessful"}';
     return;
   }else{
     echo '{"result": 1, "message": "Login is successful"}';
     return;
   }
}

function checkSession(){
  if(isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['user_type'])){
    $user->loginUserSession();
  }
}

function signOut(){
  if(!$user->signOut()){
    echo '{"result": 0, "Could not sign out"}';
    return;
  }
  echo '{"result": 1, "You have successfully signed out"}';
  return;
}

function getUserDetailsById(){
  $user_id = $_REQUEST['user_id'];
  $row = $user->getUserDetailsById($user_id);
  if(!$orw){
    echo '{"result": 0, "message": "Could not fetch user details, try again"}';
    return;
  }
  echo '{"result": 1, "user": ["'.json_encode($row).'"]}';
  return;
}

function getArtisansBySkill(){
  $skill = $_REQUEST['skill'];
  $row = $user->getArtisansBySkill($skill);
  if(!$orw){
    echo '{"result": 0, "message": "No such users"}';
    return;
  }
  echo '{"result": 1, "artisans": [';
  while ($row) {
    echo json_encode($row);
    $row = $user->fetch();
    if($row){
      echo ",";
    }
  }
  echo "]}";
  return;
}

function getArtisansByCommunity(){}

function becomeArtisan(){}


 ?>
