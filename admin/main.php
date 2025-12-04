<!DOCTYPE html>
<html>
<?php 
session_start()
 ?>
 <head>
 <meta name="viewport" content="width=device-width, initial-scale=1.0">
 <style>
   img{
     width:8%;
   }
  </style>
<script>
function validation1(){
if(document.getElementById("uname").value == ""){
  alert("Please Enter UserID");
  return 0;
}

if(document.getElementById("pwd").value == ""){
  alert("Please Enter Password");
  return 0;
}
}
</script>
<style>
.text{
font-family:times, serif;
color:#0b7bbd;
font-size:16px;
font-style: #333333;
width:300px;
background-color:white;
}

.logo{
  text-align: center;
  margin: 0% 0% 8% 0%;
}
</style>
</head>
<!-- <body style="width:99%;background-repeat: no-repeat;" > -->
  

<body style="background-image:url('pic/rm314-adj-11.jpg');width:99%;background-repeat: no-repeat;">

  
<!-- Form Mixin-->
<!-- Input Mixin-->
<!-- Button Mixin-->
<!-- Pen Title-->
 
<!-- Form Module-->
<br><br>
<div class="module form-module" style="width:100%;margin-top:12%;">
  <div class="toggle"><i class=""></i>


  <?php
   // if (isset($_POST['submit'])) 
   // {
    ///$uname=$_POST["uname"];
   // $pwd=$_POST["pwd"];
    //header("location:login.php?uname=$uname&pwd=$pwd");
    //}
 ?>
  </div>
  <div class="form">
    <div class="logo"> <img src="../images/EZWITNESS.png" style="width: 75%;"></div>
    <h2>Login to your account</h2>
    <form action="login.php" method="POST" name="abc">
      <input type="text" name="uname" id="uname" placeholder="Username" required/>
      <input type="password" id="pwd" name="pwd" placeholder="Password" required/>
      <input type="submit" name="submit" value="Login" onclick="validation1()" class="btn btn-primary btn-md"><br /><br />  

    
    </form>
  </div>
 
   
</div>  
<!-- <div align="left" style="height:20%;width:23%;background-color:white;">
<br>
<div class="text">Address: Sami Arcade Bejai Kapikad.</div>
<div class="text">Phone:9632829551</div>
!--<div class="text">Website:lunamart.net</div>
<div class="text">Email:lunamart@gmail.com</div>-->
</div> 
  <script>document.getElementById('uname').focus();</script>
  
  
<?php //include"footer.php"; ?>
</body>
</html>
<style>
  body {
  background: #e9e9e9;
  color: #666666;
  font-family: 'RobotoDraft', 'Roboto', sans-serif;
  font-size: 14px;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}

/* Pen Title */
.pen-title {
  padding: 50px 0;
  text-align: center;
  letter-spacing: 2px;
}
.pen-title h1 {
  margin: 0 0 20px;
  font-size: 48px;
  font-weight: 300;
}
.pen-title span {
  font-size: 12px;
}
.pen-title span .fa {
  color: #33b5e5;
}
.pen-title span a {
  color: #33b5e5;
  font-weight: 600;
  text-decoration: none;
}

/* Form Module */
.form-module {
    position: relative;
    background: #ffffff;
    max-width: 320px;
    width: 100%;
    border-top: 5px solid white;
    box-shadow: 0 0 3px rgb(0 0 0 / 10%);
    background-image: url(main.php);
    margin: 0 auto;
}
.form-module .toggle {
    cursor: pointer;
    position: absolute;
    top: -0;
    right: -0;
    background: white;
    width: 30px;
    height: 30px;
    margin: -5px 0 0;
    color: #ffffff;
    font-size: 12px;
    line-height: 30px;
    text-align: center;
}
.form-module .toggle .tooltip {
  position: absolute;
  top: 5px;
  right: -65px;
  display: block;
  background: rgba(0, 0, 0, 0.6);
  width: auto;
  padding: 5px;
  font-size: 10px;
  line-height: 1;
  text-transform: uppercase;
}
.form-module .toggle .tooltip:before {
  content: '';
  position: absolute;
  top: 5px;
  left: -5px;
  display: block;
  border-top: 5px solid transparent;
  border-bottom: 5px solid transparent;
  border-right: 5px solid rgba(0, 0, 0, 0.6);
}
.form-module .form {
  display: none;
  padding: 20px 40px 2px 40px;
}
.form-module .form:nth-child(2) {
  display: block;
}
.form-module h2 {
  margin: 0 0 20px;
  color: #33b5e5;
  font-size: 18px;
  font-weight: 400;
  line-height: 1;
}
.form-module input {
  outline: none;
  display: block;
  width: 100%;
  border: 1px solid #d9d9d9;
  margin: 0 0 20px;
  padding: 10px 15px;
  box-sizing: border-box;
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
}
.form-module input:focus {
  border: 1px solid #33b5e5;
  color: #333333;
}
.form-module button {
  cursor: pointer;
  background: #33b5e5;
  width: 100%;
  border: 0;
  padding: 10px 15px;
  color: #ffffff;
  -webkit-transition: 0.3s ease;
  transition: 0.3s ease;
}
.form-module button:hover {
  background: #178ab4;
}
.form-module .cta {
  background: #f2f2f2;
  width: 100%;
  padding: 15px 40px;
  box-sizing: border-box;
  color: #666666;
  font-size: 12px;
  text-align: center;
}
.form-module .cta a {
  color: #333333;
  text-decoration: none;
}

</style>