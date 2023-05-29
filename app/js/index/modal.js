// import $ from 'jquery';

(function($) {
    $.fn.modal = function() {
        let id = "#" + this[0].id + "Modal";
        $(this).on("click", function() {
            $(id).addClass("show");
            $(id).trigger("modalOpen", []);
        });
        $(id + " .modal-close").on("click", function() {
            $(id).removeClass("show");
        });
        $(id).on("click", function(event) {
            if (event.target.id == id.substring(1)) {
                $(id).removeClass("show");
            }
        });
        $(id).on("modalClose", function() {
            $(id).removeClass("show");
        });
    };
})(jQuery);

export function modal(elem){
    $(elem).modal()
}

$(function() {
    $("#cart").modal();
});
