let checkBox = document.getElementById("checkBox");
let resetLogin = document.getElementById("annuler"); 


function check() {
  let type =
    password.type == "password"
      ? (password.type = "text")
      : (password.type = "password");
  return type;
}
checkBox.addEventListener("click", check);

function resetInputs() {
document.getElementById("reset").reset(); 
}
resetLogin.addEventListener('click', resetInputs); 