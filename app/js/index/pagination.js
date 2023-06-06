// import $ from "jquery";
import {addToCart} from "./cart.js";

function getCookie(name) {
    const value = `; ${document.cookie}`;
    const parts = value.split(`; ${name}=`);
    if (parts.length === 2) return parts.pop().split(";").shift();
}

function getProducts(page, limit, search = null) {
    var ajaxData = {
        manufactor: [],
        type: [],
        size: [],
        offset: (page - 1) * limit,
        limit: limit,
        gender: getCookie("gender") == "boys" ? 1 : 2,
        group: 1,
    };
    if (search !== null) {
        ajaxData["name"] = search;
    }
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

        let fetchItems = async function(search = null) {
            getProducts(page, limit, search).done((res) => {
                count = res.count;
                $(".shop_list").trigger("newdata", [res.items]);
            });
        };

        $(this).on("newdata", function(_, data) {
            page = 1;
            $("#page").text(page);
            if (data !== undefined) {
                fetchItems(data);
            } else {
                fetchItems();
            }
        });

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
            } else if (this.id == "forward" && count >= limit) {
                page++;
            } else {
                return;
            }
            $("#page").text(page);
            fetchItems();
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
                img.src = "media/" + prod.img;

                let name = document.createElement("span");
                name.className = "item_name";
                name.innerText = prod.name;

                let size = document.createElement("div");
                size.className = "item_size";
                let avaliable = prod.avaliable.split(",");
                let ids = prod.id.split(",");
                let prodData = {};
                let id = 0;
                prodData["name"] = prod.name;
                prod.size.split(",").forEach((product, ind) => {
                    let size_button = document.createElement("span");
                    size_button.innerText = product;
                    if (avaliable[ind] == 0) {
                        size_button.className = "disabled";
                    } else {
                        $(size_button).on("click", function() {
                            $(size).children().removeClass("selected");
                            $(this).addClass("selected");
                            prodData["size"] = product;
                            id = ids[ind];
                        });
                    }
                    size.appendChild(size_button);
                });

                let price = document.createElement("span");
                price.className = "item_price";
                price.innerText = "Ціна: " + prod.price + " грн.";

                let button = document.createElement("button");
                button.className = "item_add";
                button.innerText = "Додати в кошик";
                div.append(img, name, size, price, button);
                $(button).on("click", function(event) {
                    event.preventDefault();
                    if (prodData.size === undefined) {
                        return;
                    }
                    addToCart(id, prodData);
                    setTimeout(function() {
                        $(size).children().removeClass("selected");
                    }, 2000);
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
