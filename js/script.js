var link = "server/controller/controller.php?cmd=";
var username = "";
var photo = "user.png";
var fullname = "";
var phone = "";
var email = "";
var user_type = "";
var community = "";
var myskills = [];

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

//delay longer for next action
function doLongdelay(){
 setTimeout(function(){return true;},60000);
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

//event to signout user
$(function(){
  $("#signout").click(function(e){
    e.preventDefault();
    signOut();
  });
});

//event to create job
$(function(){
  $("#createjob-form").submit(function(e){
    e.preventDefault();
    createJob();
  });
});

//event to editing frofile
$(function(){
  $("#profile-form").submit(function(e){
    e.preventDefault();
    updateProfile();
  });
});

//event to add skill
$(function(){
  $("#add-skill-form").submit(function(e){
    e.preventDefault();
    addSkill();
  });
});



function signUp(p1){
  var signupstatus = document.getElementById('signupstatus');
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
  var objResult = sendRequest(strUrl);
  if(objResult.result == 0){
    signupstatus.innerHTML = "Signup unsuccessful successful!";
    signupstatus.style.color = "red";
    return;
  }
  signupstatus.innerHTML = "Signup was successful";
  signupstatus.style.color = "green";
  doLongdelay();
  window.location.href = "index.html";
}

//function to login a user
function login(username, password, user_type){
  var loginstat = document.getElementById('loginstatus');
  var strUrl = link+"2&username="+username+"&password="+password+"&user_type="+user_type;
  objResult = sendRequest(strUrl);

  if(objResult.result == 0){
    loginstat.innerHTML = "Login was unsuccessful, check details and user type.";
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
  user_type = mydetails['type'];
  if(user_type == "artisan")
    community = mydetails['community'];
//pimp with details
  document.getElementById('myfullname').innerHTML = fullname;
//  document.getElementById('dp-area').innerHTML = '<img src="images/"'+photo+' class="demo-avatar centered">';
  //alert(objResult.user[0]['fullname']);
}

function signOut(){
  var strUrl = link+"10";
  var objResult = sendRequest(strUrl);
  doAdelay();
  if(objResult.result == 1){
         window.location.href = "index.html";
     return;
     }
    alert(objResult.message);
}

function createJob(){
  var summary = $("#summary").val();
  var starting_price = $("#starting_price").val();
  var description = $("#description").val();
  var community = $("#community").val();
  var required_skill = $("#skill_required").val();
  var report = document.getElementById('jobadd-report');

  //alert(summary); alert(starting_price); alert(description); alert(community);

  if(summary.length < 10){
    report.innerHTML = "Summary must be between 20 and 30 characters";
    report.style.color = "red";
    return;
  }
  if(required_skill.length < 3){
    report.innerHTML = "Enter the main skill required";
    report.style.color = "red";
    return;
  }
  if (starting_price <= 0) {
    report.innerHTML = "Starting price can't be empty";
    report.style.color = "red";
    return;
  }
  if(community.length < 2){
    report.innerHTML = "Write full name of community";
    report.style.color = "red";
    return;
  }
  var strUrl = link+"11&summary="+summary+"&starting_price="+starting_price+"&description="+description+"&community="+community+"&skill_required="+required_skill;
  var objResult = sendRequest(strUrl);
  if(objResult.result == 0){
    report.innerHTML = "Sorry, could not add job.<br> Check internet and try again";
    report.style.color = "red";
    return;
  }
  report.innerHTML = "Your new job has been added";
  report.style.color = "green";

}

//inserts user details into profile form
function fillProfileForm(){
  document.getElementById('name_area').innerHTML = fullname;
  document.getElementById('username_area').innerHTML = username;
  if(user_type == "artisan"){
    document.getElementById('community-area').innerHTML = "<div class='mdl-textfield mdl-js-textfield mdl-textfield--floating-label textfield-demo'><input class='mdl-textfield__input' type='text' id='community' value='Nima'/><label class='mdl-textfield__label' for='community'><i class='fa fa-users'></i> community</label></div>";
  }
  else{
    document.getElementById('artisanBtn').innerHTML = '<button class="becomeArtisanBtn mdl-button mdl-js-button mdl-js-ripple-effect whiten mdl-button--raised deep-blue-text centered" onclick="becomeArtisan()">Become An Artisan</button>';
  }

  document.getElementById('phone').value = phone;
  document.getElementById('email').value = email;
  if(user_type == "artisan"){
    document.getElementById('community').value = community;
  }
  dressSkillsArea();
}

function dressSkillsArea(){
  if(user_type == "ordinary"){
    document.getElementById("jobs-area-nav").innerHTML = "Jobs Assigned";
    document.getElementById("skills-area-nav").remove();
    document.getElementById("lannisters-panel").remove();
  }
  if(user_type == "artisan")
    getArtisanSkills();
  getProfileJobs();
}

function getProfileJobs(){

}

function getArtisanSkills(){
  var strUrl = link+"13";
  var objResult = sendRequest(strUrl);

  if(objResult.result == 0){
    alert(" no skills");
    return;
  }
  var skills = objResult.skills;
  var skillsTable = document.getElementById('skillsTable');
  for(var i = 0; i < skills.length; i++){
    var skill_name = skills[i]['skill_name'];
    myskills[i] = skill_name;
    row=skillsTable.insertRow(1);
    cell=row.insertCell(0);
    cell.className = 'mdl-data-table__cell--non-numeric';
    cell.innerHTML = "<td>"+skill_name+"</td>";
  }
}

function addSkill(){
  var newskill = $("#newskill").val().toLowerCase();
  if(newskill.length == 0){
    return alert("skill can't be empty");
  }
  if(checkIfSkillExists(newskill, myskills)){
    return alert(newskill+" exists");
  }
  //alert(newskill+" does not exist");
  var strUrl = link+"15&skill_name="+newskill;
  var objResult = sendRequest(strUrl);
  if(objResult.result == 0){
    alert(objResult.message);
    return;
  }
  //successfully added skill to database - add to table in
  myskills[myskills.length] = newskill;
  var skillsTable = document.getElementById('skillsTable');
  row=skillsTable.insertRow(1);
  cell=row.insertCell(0);
  cell.className = 'mdl-data-table__cell--non-numeric';
  cell.innerHTML = "<td>"+newskill+"</td>";
}

function checkIfSkillExists(skill, skills){
  var exists = false;
  for(var i = 0; i < skills.length; i++){
    if(skill.toUpperCase() == skills[i].toUpperCase()){
      exists = true;
      return exists;
    }
  }
  return exists;
}

function updateProfile(){
  var strUrl = "";
  var form_report = document.getElementById('myprofileFormReport');
  var newcommunity = "";
  var newphone = $("#phone").val();
  var newemail = $("#email").val();
  if(user_type == "artisan"){
    newcommunity = $("#community").val();
  }
  if(newcommunity == community && newphone == phone && newemail == email){
    form_report.innerHTML = "Profile details are still the same";
    form_report.style.color = "red";
    return;
  }
    strUrl = link+"12&newphone="+newphone+"&newemail="+newemail+"&newcommunity="+newcommunity;
    var objResult = sendRequest(strUrl);
    if(objResult.result == 0){
      form_report.innerHTML = "Update was unsuccessful.";
      form_report.style.color = "red";
      return;
    }
    form_report.innerHTML = "Profile update was successful";
    form_report.style.color = "green";
}

function becomeArtisan(){
  var form_report = document.getElementById('myprofileFormReport');
  var strUrl = link+"8";
  var objResult = sendRequest(strUrl);
  form_report.innerHTML = objResult.message;
  if(objResult.result == 0){
    form_report.style.color = "red";
    return;
  }
  document.getElementById('artisanBtn').innerHTML = "";
  form_report.style.color = "green";
  doLongdelay();
  window.location.href = "profile.html";
}

function getNewJobs(){
  var strUrl = link+"16";
  var objResult = sendRequest(strUrl);
  if(objResult.result == 0){
    return alert(" no jobs for you");
  }
  //populate jobs area here
  var jobs = objResult.jobs;
  var freshjobs = "";
  var current_jobs = document.getElementById("current-jobs-area");
  for(var i = 0; i < jobs.length; i++){
    singleTask = '<div class="demo-updates mdl-shadow--2dp mdl-cell mdl-color--white mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--6-col-desktop "><div class="mdl-grid"><div class="mdl-cell mdl-cell--12-col"><p>'+jobs[i]['summary']+'</p></div></div><div class="mdl-grid mdl-card__supporting-text mdl-color-text--orange-600" style="margin-top: -25px;"><div class="mdl-cell mdl-cell--12-col"><b class="jobtag deep-blue-text mdl-cell mdl-cell--6-col">'+jobs[i]['skill_required']+'</b><b class=" mdl-cell mdl-cell--6-col price">₵'+jobs[i]['starting_price']+'</b></div></div><div class="mdl-grid mdl-card__actions mdl-card--border mdl-color-text--grey-600"><div class="mdl-grid mdl-cell--12-col"><div class="mdl-cell mdl-cell--8-col"><p style="text-align: left;">Posted: '+jobs[i]['skill_required']+'</p></div><div class="mdl-cell mdl-cell--4-col"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect applybtn" onclick="applyForJob('+jobs['job_id']+')"><b>Apply</b></button></div></div></div></div>';
    freshjobs += singleTask;
  }
  current_jobs.innerHTML = freshjobs;
}

function getOpenAssignedJobs(){

}

function getAllJobs(){
  var strUrl = link+"17";
  var objResult = sendRequest(strUrl);

  var jobs = objResult.jobs;
  var freshjobs = "";
  var all_jobs = document.getElementById("all-jobs-area");

  for(var i = 0; i < jobs.length; i++){
    singleTask = '<div class="demo-updates mdl-shadow--2dp mdl-cell mdl-color--white mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--6-col-desktop "><div class="mdl-grid"><div class="mdl-cell mdl-cell--12-col"><p>'+jobs[i]['summary']+'</p></div></div><div class="mdl-grid mdl-card__supporting-text mdl-color-text--orange-600" style="margin-top: -25px;"><div class="mdl-cell mdl-cell--12-col"><b class="jobtag deep-blue-text mdl-cell mdl-cell--6-col">'+jobs[i]['skill_required']+'</b><b class=" mdl-cell mdl-cell--6-col price">₵'+jobs[i]['starting_price']+'</b></div></div><div class="mdl-grid mdl-card__actions mdl-card--border mdl-color-text--grey-600"><div class="mdl-grid mdl-cell--12-col"><div class="mdl-cell mdl-cell--8-col"><p style="text-align: left;">Posted: '+jobs[i]['skill_required']+'</p></div><div class="mdl-cell mdl-cell--4-col"><button class="mdl-button mdl-js-button mdl-button--raised mdl-js-ripple-effect applybtn" onclick="applyForJob('+jobs['job_id']+')"><b>Apply</b></button></div></div></div></div>';
    freshjobs += singleTask;
  }
  all_jobs.innerHTML = freshjobs;
}
