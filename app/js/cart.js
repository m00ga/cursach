function getCart() {
    let cart = localStorage.getItem("cart");
    if (cart == null) {
        return false;
    }
    return JSON.parse(cart);
}

function deleteCart() {
    let cart = getCart();
    if (cart == false) {
        return;
    }
    localStorage.setItem("cart", JSON.stringify({}));
}

function addToCart(id, data) {
    let cart = getCart();
    if (cart == false) {
        cart = {};
    }
    let val = cart[id];
    if (val === undefined) {
        cart[id] = {
            product: data,
            amount: 1,
        };
    } else {
        cart[id].amount = val.amount + 1;
    }
    $("#cartPanel").trigger("update", [cart[id]]);
    localStorage.setItem("cart", JSON.stringify(cart));
}

function getFromCart(id) {
    let cart = getCart();
    if (cart == false) {
        return false;
    }
    let val = cart[id];
    if (val === undefined) {
        return false;
    } else {
        return val;
    }
}

function cartLength() {
    let cart = getCart();
    if (cart === false) {
        return 0;
    } else {
        return Object.keys(cart).length;
    }
}

function removeFromCart(id) {
    let cart = getCart();

    if (cart == false) {
        return false;
    }
    if (cart[id] !== undefined) {
        if (cart[id] > 1) {
            cart[id] = cart[id] - 1;
        } else {
            delete cart[id];
        }
    }
    localStorage.setItem("cart", JSON.stringify(cart));
}

$(function() {
    function createCartRow(data) {
        let product = data.product;
        let div = document.createElement("div");
        div.className = "cartRow";

        let img = document.createElement("img");
        img.className = "cartImg";
        img.src = "media/delete.svg";

        let name = document.createElement("span");
        name.className = "cartName";
        name.innerText = product.name;

        let sizeDiv = document.createElement("div");
        sizeDiv.className = "cartAnnot";
        let size = document.createElement("span");
        size.className = "cartSize";
        size.innerText = product.size;
        let sText = document.createElement("span");
        sText.innerText = "Розмір:";
        sizeDiv.append(sText, size);

        let amountDiv = document.createElement("div");
        amountDiv.className = "cartAnnot";
        let amount = document.createElement("span");
        amount.className = "cartAmount";
        amount.innerText = data.amount;
        let aText = document.createElement("span");
        aText.innerText = "Кількість:";
        amountDiv.append(aText, amount);

        div.append(img, name, sizeDiv, amountDiv);
        $("#cartPanel").append(div);
    }

    $("#cartPanel").on("update", function(_, data) {
        $(".modal-footer button").prop("disabled", false);
        if (data === undefined) {
            let cart = getCart();
            $("#cartPanel").html("");
            if (cart !== false && cartLength() > 0) {
                for (let key in cart) {
                    createCartRow(cart[key]);
                }
            } else {
                $(".modal-footer button").prop("disabled", true);
            }
        } else {
            createCartRow(data);
        }
    });

    let loaded = false;

    $("#cartModal").on("modalOpen", function() {
        if (!loaded) {
            $("#cartPanel").trigger("update", []);
            loaded = true;
        }
    });

    $("#deleteButton").on("click", function() {
        deleteCart();
        $("#cartPanel").trigger("update", []);
    });
});

export { addToCart, getFromCart, removeFromCart };
