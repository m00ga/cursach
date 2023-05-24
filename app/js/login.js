// import $ from "jquery";

$(function() {
    let showError = function(text) {
        $("#loginError").text(text).css("display", "block");
    };

    let passHidden = true;

    $("#passEye").on('click', function(){
        if(passHidden){
            $("#passwordparam")[0].type = 'text';
            passHidden = false;
        }else{
            $("#passwordparam")[0].type = 'password';
            passHidden = true;
        }
    })

    $("#loginButton").on("click", function() {
        let login = $("#loginparam").val();
        let password = $("#passwordparam").val();

        if (login == "") {
            showError("Please enter login");
            return;
        }

        if (password == "") {
            showError("Please enter password");
            return;
        }

        let res = password.match(/\S+/g);
        if (res.length > 1) {
            showError("Password contains special chars");
            return;
        }
        if(password.length < 6) {
            showError("Password must contain at least 6 chars");
            return;
        }
        if(new RegExp(/(?:\d+)/g).test(password) != true){
            showError("Password must contain at least 1 number");
            return;
        }
        $('#loginError').css('display', 'none');
    });
});
