const service=document.getElementById("service");
const category=document.getElementById("category");
const price=document.getElementById("price");
service.onchange=function(){
category.innerHTML="";
for(let key in data[this.value]){
let option=document.createElement("option");
option.value=key;
option.text=key;
category.appendChild(option);
}
price.value=data[this.value][category.value];
};
category.onchange=function(){
price.value=data[service.value][this.value];
};