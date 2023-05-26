// import $ from 'jquery';

(function($){
    $.fn.modal = function(){
        let id = "#" + this[0].id + "Modal";
        $(this).on('click', function(){
            $(id).css('display', "block");
            $(id).trigger("modalOpen", []);
        })
        $(id + " .modal-close").on('click', function() {
            $(id).css('display', 'none');
        })
        $(id).on('click', function(event){
            if(event.target.id == id.substring(1)){
                $(id).css("display", 'none');
            }
        })
        $(id).on('modalClose', function(){
            $(this).css('display', 'none');
        })
    }
})(jQuery);

$(function() {
    $("#search").modal()
    $("#login").modal()
    $("#cart").modal()
})

export default $.fn.modal
