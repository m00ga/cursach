// import $ from "jquery";
import { modal } from "./modal.js";

function checkLogin() {
    let token = localStorage.getItem("JWTtoken");
    if (token === null) {
        return false;
    }
    $.ajax({
        url: "/auth/verify/" + token,
    })
        .done(function(_) {
            return true;
        })
        .fail(function() {
            localStorage.removeItem("JWTtoken");
            return false;
        });
    return true;
}

$(function() {
    let change = function() {
        $("#login").attr("src", "/media/person.svg");
    };

    let mainPage = true;

    let reBind = () => {
        $("#login").on("click", function() {
            let url;
            let token;
            if (mainPage) {
                url = "/admin/";
                token = localStorage.getItem("JWTtoken");
                mainPage = false;
            } else {
                url = "/?raw=1";
                mainPage = true;
                location.href = "/";
                return;
            }
            $.ajax({
                url: url,
                method: "GET",
                headers: { Authorization: token },
            }).done(function(res) {
                $("#main").html(res);
            });
        });
    };

    let res = checkLogin();
    if (res == false) {
        modal("#login");
    } else {
        reBind();
        change();
    }

    let showError = function(text) {
        $("#loginError").text(text).css("display", "block");
    };

    let passHidden = true;

    $("#passEye").on("click", function() {
        if (passHidden) {
            $(this).attr("src", "/media/eye_crossed.svg");
            $("#passwordparam")[0].type = "text";
            passHidden = false;
        } else {
            $(this).attr("src", "/media/eye_open.svg");
            $("#passwordparam")[0].type = "password";
            passHidden = true;
        }
    });

    $(".loginButton").on("click", function() {
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
        $("#loginError").css("display", "none");

        $.ajax({
            url: `/auth/${this.value}/`,
            data: {
                login: login,
                password: password,
            },
            method: "POST",
        })
            .done(function(res) {
                localStorage.setItem("JWTtoken", res.data);
                change();
                $("#loginModal").trigger("modalClose", []);
                $("#login").unbind("click");
                reBind();
            })
            .fail(function(xhr) {
                let obj = JSON.parse(xhr.responseText);
                showError(obj.data);
            });
    });
});
