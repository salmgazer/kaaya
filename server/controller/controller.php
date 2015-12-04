<?php session_start();

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
    checkSession();
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
  case 9:
    getUserDetailsBySession();
    break;
  case 10:
    signOut();
    break;
  case 11:
    createJob();
    break;
  case 12:
    updateProfile();
    break;
  case 13:
    getArtisanSkills();
    break;
  case 14:
    getProfileJobs();
    break;
  case 15:
    addSkill();
    break;
  case 16:
    getNewJobs();
    break;
  case 17;
    getAllJobs();
    break;

  default:
    echo '{"result": 0, "message": "Command unknown"}';
    return;
    break;
}

function signUp(){
  include_once "../model/User.php";
  $user = new User();
  $fullname = $_REQUEST['fullname'];
  $email = $_REQUEST['email'];
  $username = $_REQUEST['username'];
  $phone = $_REQUEST['phone'];
  $user_password = $_REQUEST['password'];

  if($user->signUp($fullname, $email, $username, $phone, $user_password)){
    //successfully added send email for confirmation
    //sendEmail($sender, $receipient, $subject, $message);

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
  include_once "../model/User.php";
  $user = new User();
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
  include_once "../model/User.php";
  $user = new User();
  if(isset($_SESSION['username']) && isset($_SESSION['password']) && isset($_SESSION['user_type'])){
    $user->loginUserSession();
  }
}

function signOut(){
  if(session_destroy()){
        echo '{"result":1,"message": "Logged out successfully"}';
        return;
    }
    echo '{"result":0,"message": "Could not log you out, try again."}';
    return;
}

function getUserDetailsById(){
  include_once "../model/User.php";
  $user = new User();
  $user_id = $_REQUEST['user_id'];
  $row = $user->getUserDetailsById($user_id);
  if(!$orw){
    echo '{"result": 0, "message": "Could not fetch user details, try again"}';
    return;
  }
  echo '{"result": 1, "user": ["'.json_encode($row).'"]}';
  return;
}

function getUserDetailsBySession(){
  include_once "../model/User.php";
  $user = new User();
  $row = $user->getUserDetailsBySession();
  if(!$row){
    echo '{"result": 0, "message": "Could not fetch user details, try again"}';
    return;
  }
  echo '{"result": 1, "user": [';
    echo json_encode($row);
    echo ']}';
  return;
  }


function getArtisansBySkill(){
  include_once "../model/User.php";
  $user = new User();
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

function getArtisansByCommunity(){
  include_once "../model/User.php";
  $user = new User();
}

function becomeArtisan(){
  include_once "../model/User.php";
  $user = new User();
  if(!$user->becomeArtisan()){
    echo '{"result": 0, "message": "unsuccessful, try again. Check internet"}';
    return;
  }
  echo '{"result": 1, "message": "You are now an artisan!"}';
  return;
}

function createJob(){
  include_once "../model/User.php";
  $user = new User();
  $starting_price = $_REQUEST['starting_price'];
  $summary = $_REQUEST['summary'];
  $description = $_REQUEST['description'];
  $community = $_REQUEST['community'];
  $skill_required = $_REQUEST['skill_required'];
  if(!$user->createJob($starting_price, $summary, $description, $community, $skill_required)){
    echo '{"result": 0, "message": "Could not add your job, try again"}';
    return;
  }
  echo '{"result": 1, "message": "Your job has been added"}';
  return;
}

function updateProfile(){
  include_once "../model/User.php";
  $user = new User();

  $newcommunity = $_REQUEST['newcommunity'];
  $newphone = $_REQUEST['newphone'];
  $newemail = $_REQUEST['newemail'];

  if(!$user->updateProfile($newcommunity, $newphone, $newemail)){
    echo '{"result": 0, "message": "Update was unsuccessful"}';
    return;
  }
  echo '{"result": 1, "message": "Update was successful"}';
  return;
}

function getArtisanSkills(){
  include_once "../model/User.php";
  $user = new User();

  $skills = $user->getArtisanSkills();
  if(!$skills){
    echo '{"result": 0, "message": "No skills"}';
    return;
  }
  echo '{"result": 1, "skills": [';
  while($skills){
    echo json_encode($skills);
    $skills = $user->fetch();
    if($skills){
      echo ",";
    }
  }
  echo "]}";
}

function addSkill(){
  include_once "../model/User.php";
  $user = new User();

  $skill_name = $_REQUEST['skill_name'];
  if(!$user->addSkill($skill_name)){
    echo '{"result: 0", "message": "Could not add new skill. try again"}';
    return;
  }
  echo '{"result": 1, "message": "New skill has been added"}';
  return;
}

function getNewJobs(){
  include_once "../model/User.php";
  $user = new User();

  $jobs = $user->getNewJobs();
  if(!$jobs){
    echo '{"result": 0, "message": "No jobs for you, try viewing through all jobs"}';
    return;
  }
  echo '{"result": 1, "jobs": [';
  while($jobs){
    echo json_encode($jobs);
    $jobs = $user->fetch();
    if($jobs){
      echo ",";
    }
  }
  echo "]}";
}

function getAllJobs(){
  include_once "../model/User.php";
  $user = new User();

  $jobs = $user->getAllJobs();
  if(!$jobs){
    echo '{"result": 0, "message": "No jobs for you, try viewing through all jobs"}';
    return;
  }
  echo '{"result": 1, "jobs": [';
  while($jobs){
    echo json_encode($jobs);
    $jobs = $user->fetch();
    if($jobs){
      echo ",";
    }
  }
  echo "]}";
}



 ?>
