console.log("EventEase Loaded Successfully");
const cards=document.querySelectorAll(".card");
cards.forEach(card=>{
card.addEventListener("mouseenter",()=>{
card.style.boxShadow="0px 10px 30px gray";
});
card.addEventListener("mouseleave",()=>{
card.style.boxShadow="";
});
});