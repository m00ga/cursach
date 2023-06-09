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
    let isNew = false;
    if (val === undefined) {
        cart[id] = {
            product: data,
            amount: 1,
        };
        isNew = true;
    } else {
        cart[id].amount = val.amount + 1;
    }
    $("#cartPanel").trigger("update", [[id, cart[id], isNew]]);
    localStorage.setItem("cart", JSON.stringify(cart));
}

function updateCart(id, data) {
    let cart = getCart();
    if (cart == false) {
        return false;
    }
    cart[id] = data;
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
    var allPrice = 0;
    function createCartRow(data, id) {
        let product = data.product;
        let div = document.createElement("div");
        div.className = "cartRow";
        div.dataset.id = id;

        let img = document.createElement("img");
        img.className = "cartImg";
        img.src = "/media/delete.svg";

        $(img).on("click", function() {
            removeFromCart(id);
            $("#cartPanel").trigger("update", []);
        });

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
        let ctrlDecrease = document.createElement("span");
        ctrlDecrease.className = "cartControl";
        ctrlDecrease.dataset.value = "decrease";
        ctrlDecrease.innerText = "-";
        let ctrlIncrease = document.createElement("span");
        ctrlIncrease.className = "cartControl";
        ctrlIncrease.dataset.value = "increase";
        ctrlIncrease.innerText = "+";
        allPrice += Number.parseInt(product.price);
        $([ctrlDecrease, ctrlIncrease]).on("click", function() {
            if (this.disabled) {
                return;
            }
            let amount = $(this).parent().children(".cartAmount");
            let price = $(`.cartRow[data-id='${id}'] .cartPrice`);
            let amountText = Number.parseInt(amount.text());
            if (this.dataset.value == "decrease" && amountText > 1) {
                amount.text(amountText - 1);
                amountText--;
                allPrice -= Number.parseInt(product.price);
            } else if (this.dataset.value == "increase") {
                amount.text(amountText + 1);
                amountText++;
                allPrice += Number.parseInt(product.price);
            }
            price.text(amountText * product.price);
            updateCart(id, { amount: amountText, product: product });
        });
        amountDiv.append(aText, ctrlDecrease, amount, ctrlIncrease);

        let priceDiv = document.createElement("div");
        priceDiv.className = "cartAnnot";
        let price = document.createElement("span");
        price.className = "cartPrice";
        price.innerText = Number.parseInt(product.price) * data.amount;
        let priceAnnot = document.createElement("span");
        priceAnnot.innerText = "Ціна: ";
        priceDiv.append(priceAnnot, price);

        div.append(img, name, sizeDiv, amountDiv, priceDiv);
        $("#cartPanel").append(div);
    }

    $("#cartPanel").on("update", function(_, data) {
        $(".modal-footer button").prop("disabled", false);
        if (data === undefined) {
            let cart = getCart();
            $("#cartPanel").html("");
            if (cart !== false && cartLength() > 0) {
                for (let key in cart) {
                    createCartRow(cart[key], key);
                }
            } else {
                $(".modal-footer button").prop("disabled", true);
            }
        } else {
            if (data[2] === true) {
                createCartRow(data[1], data[0]);
            } else {
                $(`.cartRow[data-id='${data[0]}'] .cartAmount`).text(data[1].amount);
            }
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
