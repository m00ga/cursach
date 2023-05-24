// import $ from 'jquery';

(function($){
    $.fn.modal = function(){
        let id = "#" + this[0].id + "Modal";
        $(this).on('click', function(){
            $(id).css('display', "block");
        })
        $(id + " .modal-close").on('click', function() {
            $(id).css('display', 'none');
        })
        $(id).on('click', function(event){
            if(event.target.id == id.substring(1)){
                $(id).css("display", 'none');
            }
        })
    }
})(jQuery);

$(function() {
    $("#search").modal()
    $("#login").modal()
})

export default $.fn.modal
