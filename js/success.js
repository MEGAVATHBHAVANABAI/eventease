let booking="EVT"+Math.floor(Math.random()*1000000);
document
.getElementById("bookingId")
.innerHTML=booking;
setTimeout(function(){
let msg=new SpeechSynthesisUtterance(
"Congratulations. Your booking has been confirmed successfully."
);
speechSynthesis.speak(msg);
},800);