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
        $('#loginError').css('display', 'none');

        $.ajax({
            url: "/auth/login/",
            data: {
                login: login,
                password: password
            },
            method: "POST"
        }).done(function(res) {
            localStorage.setItem('JWTtoken', res.data);
            $('#loginModal').trigger("modalClose", []);
        }).fail(function(xhr){
            let obj = JSON.parse(xhr.responseText);
            showError(obj.data);
        })
    });
});
