// import $ from 'jquery';

(function($) {
    $.fn.dynSearch = function(body) {
        $(this).on("input", function() {
            $(body).html("");
            if (this.value != "") {
                $.ajax({
                    url: "/api/product/",
                    method: "GET",
                    data: {
                        name: this.value + "%",
                    },
                }).done(function(res) {
                    let ul = document.createElement("ul");
                    res.items.forEach((item) => {
                        let elem = document.createElement("li");
                        let name = document.createElement("span");
                        name.innerText = item.name;
                        elem.appendChild(name);
                        ul.appendChild(elem);
                    });
                    $(body).append(ul);
                });
            }
        });
    };
})(jQuery);

$(function() {
    $("#prodSearch").dynSearch("#prodBody");
});
