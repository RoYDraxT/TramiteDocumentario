const rmcheck = document.getElementById("remember"),
                correoinput = document.getElementById("correo"),
                passinput = document.getElementById("password");
            if(localStorage.checkbox && localStorage.checkbox!=""){
                rmcheck.setAttribute("checked", "checked");
                correoinput.value =localStorage.correo;
                passinput.value =localStorage.password;
            }else{
                rmcheck.removeAttribute("checked");
                correoinput.value ="";
                passinput.value ="";
            }

function recuerdame(){
    if(rmcheck.checked && correoinput.value!="" && passinput.value!=""){
        localStorage.correo = correoinput.value;
        localStorage.pass = passinput.value;
        localStorage.checkbox = rmcheck.value;
    }else{
        localStorage.correo = "";
        localStorage.password = "";
        localStorage.checkbox = "";
    }
}

rmcheck.addEventListener("change", recuerdame);
correoinput.addEventListener("input", recuerdame);
passinput.addEventListener("input", recuerdame);