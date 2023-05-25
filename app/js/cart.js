function replacer(key, value) {
    if (value instanceof Map) {
        return {
            dataType: "Map",
            value: Array.from(value.entries()), // or with spread: value: [...value]
        };
    } else {
        return value;
    }
}

function reviver(key, value) {
    if (typeof value === "object" && value !== null) {
        if (value.dataType === "Map") {
            return new Map(value.value);
        }
    }
    return value;
}

function getCart() {
    let cart = localStorage.getItem("cart");
    if (cart == null) {
        return false;
    }
    // cart = new Map(Object.entries(JSON.parse(cart)));
    return JSON.parse(cart);
}

function addToCart(id) {
    let cart = getCart();
    if (cart == false) {
        cart = {};
    }
    let val = cart[id];
    if (val === undefined) {
        cart[id] = 1;
    } else {
        cart[id] = val + 1;
    }
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

export { addToCart, getFromCart, removeFromCart };
