
    document.getElementById("exampleInputPassword1")
    .addEventListener("keyup", function(event) {
    event.preventDefault();
    if (event.keyCode === 13) {
        document.getElementById("myBtn").click();
    }
   });
function validate(){




    var password= document.getElementById("exampleInputPassword1").value;
    
    if(password =="kheticlicks"){

    window.location.href = "https://khetifood.com/track.html";

    }
    return false;
}