const loginForm=document.getElementById("loginForm");
if(loginForm){
loginForm.addEventListener("submit",function(e){
e.preventDefault();
alert("Login Successful");
window.location="index.html";
});
}
const registerForm=document.getElementById("registerForm");
if(registerForm){
registerForm.addEventListener("submit",function(e){
e.preventDefault();
let pass=document.getElementById("regpassword").value;
let confirm=document.getElementById("confirm").value;
if(pass!==confirm){
alert("Passwords do not match");
return;
}
alert("Registration Successful");
window.location="login.html";
});
}