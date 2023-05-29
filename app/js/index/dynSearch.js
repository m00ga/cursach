// import $ from "jquery";

var transitionEvent = "transitionend";

(function($) {
    $.fn.dynSearch = function() {
        let flag = false;
        let timerHandle = 0;
        let timer = () => {
            return setTimeout(() => {
                if (this[0].value != "") {
                    $(".pages").trigger("newdata", [this[0].value + "%"]);
                } else {
                    $(".pages").trigger("newdata", []);
                }
            }, 1000);
        };
        $(this).on("input", function() {
            if(flag == true){
                clearTimeout(timerHandle);
                flag = false;
            }
            timerHandle = timer();
            flag = true;
        });
    };
})(jQuery);

$(function() {
    let showed = false;
    $("#search").on("click", function() {
        if (!showed) {
            $("#searchContainer").css("display", "block");
            $("#searchContainer").addClass("unhide");
            showed = true;
        } else {
            showed = false;
            $("#searchContainer").removeClass("unhide");
        }
    });
    $("#searchContainer").on("transitionend", function() {
        if (!showed) {
            $(this).css("display", "none");
        }
    });
    $("#prodSearch").dynSearch();
});
