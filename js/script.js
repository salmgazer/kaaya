var link = "server/controller/controller.php?cmd=";
var username = "";
var photo = "user.png";
var fullname = "";
var phone = "";
var email = "";

//sends request to Ajax page
function sendRequest(u){
    // Send request to server
    //u a url as a string
    //async is type of request
    var obj=$.ajax({url:u,async:false});
    //Convert the JSON string to object
    var result=$.parseJSON(obj.responseText);
    return result;	//return object

}

function loginHtml(){
  window.location.href = "index.html";
}

function signupHTML(){
  window.location.href = "signup.html";
}
//compares password and confirmation password are the same during signup
function comparePasswords(){
  var firstpass = $("#password").val();
  var confirmationpass = $("#password2").val();
  var result = "";
  var component = document.getElementById('passequal');
  if(firstpass == confirmationpass){
    component.style.color = "green";
    result = "passwords match!";
  }else {
    component.style.color = "red";
    result = "passwords don't match...";
  }
  component.innerHTML = result;
}

//delay next action
function doAdelay(){
 setTimeout(function(){return true;},30000);
}

//submit event for signup form
$(function(){
  $("#signup-form").submit(function(e){
    e.preventDefault();
    var p1 = $("#password").val();
    var p2 = $("#password2").val();
    if(p1 != p2){
      alert("Passwords do not match");
      return;
    }
    signUp(p1);
  });
});

//submit event for login form
$(function(){
  $("#login-form").submit(function(e){
    e.preventDefault();
    var user_type = "ordinary";
    var username = $("#username").val();
    var password = $("#password").val();
    if(document.getElementById("user_type_check").checked)
      user_type = "artisan";
    login(username, password, user_type);
  });
});


function signUp(p1){
  if(p1.length < 8){
    alert("password must be at least 8 characters long");
  }
  var fullname  = $("#fullname").val();
  var email = $("#email").val();
  var username = $("#username").val();
  var phone = $("#phone").val();
  var password = p1;

  if(fullname.length < 5){
    alert("write full name");
    return;
  }
  var emailtest = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/i.test(email);
  if(!emailtest){
    alert("Not a correct email");
    return;
  }

  var strUrl = link+"1&fullname="+fullname+"&email="+email+"&username="+username+"&phone="+phone+"&password="+password;
  alert(strUrl);
  var objResult = sendRequest(strUrl);
  if(objResult.result == 0){
    alert("Signup was unsuccessful");
    return;
  }
  alert("Signup was successful");
  window.location.href = "index.html";
}

//function to login a user
function login(username, password, user_type){
  var loginstat = document.getElementById('loginstatus');
  var strUrl = link+"2&username="+username+"&password="+password+"&user_type="+user_type;
  objResult = sendRequest(strUrl);

  if(objResult.result == 0){
    loginstat.innerHTML = "Login was unsuccessful";
    loginstat.style.color = "red";
  }
  else{
    loginstat.innerHTML = "Login was successful";
    loginstat.style.color = "green";
    doAdelay();
    return window.location.href = "home.html";
  }
}

function getUserDetailsBySession(){
  var strUrl = link+"9";
  var objResult = sendRequest(strUrl);
  if(objResult.result == 0){
    alert("You need to sign in again. Your session is lost");
    window.location.href = "./index.html";
    return;
  }
  //store user details in variables
  var mydetails = objResult.user[0];
  username = mydetails['username'];
  email = mydetails['email'];
  fullname = mydetails['fullname'];
  phone = mydetails['phone'];
  photo = mydetails['photo'];
//pimp with details
  document.getElementById('myfullname').innerHTML = fullname;
  document.getElementById('dp-area').innerHTML = '<img src="images/"'+photo+' class="demo-avatar centered">';
  //alert(objResult.user[0]['fullname']);
}
