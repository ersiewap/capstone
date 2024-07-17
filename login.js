// $(".toggle-password").click(function() {

//     $(this).toggleClass("fa-eye fa-eye-slash");
//     var input = $($(this).attr("toggle"));
//     if (input.attr("type") == "password") {
//         input.attr("type", "text");
//     } else {
//         input.attr("type", "password");
//     }
//     });
    
//   For Registration

    document.querySelector("#show-login").addEventListener("click",function(){
    document.querySelector(".popup").classList.add("active");
});
    document.querySelector(".popup .close-btn").addEventListener("click",function(){
    document.querySelector(".popup").classList.remove("active");
});

// function auth(){
//     var owneremail = document.getElementById("owneremail").value;
//     var ownerpass = document.getElementById("ownerpass").value;
//     if(owneremail =="owneremail" && ownerpass == "ownerpass"){
//         window.location.assign("Homepage.php");  
//         alert("Login Successfully");
//     }
//     else{
//         alert("Invalid Information");
//         return;
//     }

// }