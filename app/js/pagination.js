// import $ from "jquery";

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
}

function getProducts() {
    var ajaxData = {
        manufactor: [],
        type: [],
        size: [],
        gender: getCookie("gender") == "boys" ? 1 : 2,
        group: 1,
    };
    $(".shop_params div ul li input").each(function() {
        if (this.checked === true) {
            ajaxData[this.className].push(this.value);
        }
    });
    return $.ajax({
        method: "GET",
        data: ajaxData,
        url: "/api/product/",
    });
}

(function($) {
    $.fn.pagination = function() {
        let page = 1;
        let limit = 9;
        let count = 0;
        let products = {};

        let fetchItems = async function() {
            getProducts().done((res) => {
                products = res.items;
                count = res.count;
                sendItems();
            });
        };

        let sendItems = function() {
            let data = Object.fromEntries(
                Object.entries(products).slice((page - 1) * limit, page * limit)
            );
            $(".shop_list").trigger("newdata", [data]);
        };

        this.append("<a href='#' class='pagination' id='back'><</a>");
        this.append("<span id='page' class='pagination'>" + page + "</span>");
        this.append("<a href='#' class='pagination' id='forward'>></a>");

        fetchItems();

        $("#filter").on("click", function(event) {
            event.preventDefault();
            page = 1;
            $("#page").text(page);
            fetchItems();
        });

        $(".pagination").on("click", function() {
            if (this.id == "back" && page > 1) {
                page--;
            } else if (this.id == "forward" && count >= limit * page) {
                page++;
            }else{
                return;
            }
            $("#page").text(page);
            sendItems();
            // fetchItems();
        });
    };

    $.fn.shopMenu = function() {
        this.on("newdata", function(_, data) {
            this.innerHTML = "";
            for (let elem in data) {
                let prod = data[elem];
                let div = document.createElement("div");
                div.className = "shop_item";

                let img = document.createElement("img");
                img.className = "item_img";
                img.src = "media/cart.svg";

                let name = document.createElement("span");
                name.className = "item_name";
                name.innerText = elem;

                let size = document.createElement("div");
                size.className = "item_size";
                prod.forEach((product) => {
                    let size_button = document.createElement("span");
                    size_button.innerText = product.size;
                    if (product.avaliable == 0) {
                        size_button.className = "disabled";
                    } else {
                        $(size_button).on("click", function() {
                            $(size).children().removeClass('selected')
                            $(this).addClass("selected");
                            div.dataset.id = product.id;
                        });
                    }
                    size.appendChild(size_button);
                });

                let price = document.createElement("span");
                price.className = "item_price";
                price.innerText = prod[0].price;

                let button = document.createElement("button");
                button.className = "item_add";
                button.innerText = "Додати в кошик";
                div.append(img, name, size, price, button);
                $(button).on("click", function(event) {
                    event.preventDefault();
                    $.ajax({
                        url: "/api/cart/" + this.parentNode.dataset.id,
                        method: "GET",
                    })
                        .done(function(res) {
                            $.ajax({
                                url: "/api/cart/" + res.items.id,
                                method: "PUT",
                                data: {
                                    amount: res.items.amount + 1,
                                },
                            });
                        })
                        .fail(() => {
                            if(typeof div.dataset.id === 'undefined'){
                                return;
                            }
                            let id = div.dataset.id;
                            $.ajax({
                                url: "/api/cart/" + id,
                                method: "POST",
                                data: {
                                    id: id,
                                    prod_id: id, 
                                    amount: 1,
                                },
                            });
                        });
                });
                this.appendChild(div);
            }
        });
    };
})(jQuery);

$(function() {
    $(".shop_list").shopMenu();
    $(".pages").pagination();
});
