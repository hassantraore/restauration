document.addEventListener("DOMContentLoaded", function () {
    let items = $(".js-body").data("cartItems");
    let totalArticle = $(".js-body").data("totalArticle");
    let totalPrice = $(".js-body").data("totalPrice");
    setQuantity(items, totalArticle, totalPrice);

    // or with jQuery
    //var isAuthenticated = $('.js-user-rating').data('isAuthenticated');
});
function addProductToCart(id, value, price, type = "plat", sauce = null) {
    if (type == "plat" && !sauce) {
        let liste = document.getElementById(id + "_sauce_" + value);
        if (liste) {
            sauce = liste.options[liste.selectedIndex].text;
        } else {
            sauce = null;
        }
    }
    let json = {
        id: id,
        value: value,
        price: price,
        type: type,
        sauce: sauce,
    };
    $.ajax({
        url: "/cart/add/" + JSON.stringify(json),
        method: "POST",
    }).then((response) => {
        console.log(response.message[1]);
        addFlash(response.message[0], response.message[1]);
        setQuantity(response.items, response.totalArticle, response.totalPrice);
    });
}

function removeProductFromCart(id, value, type = "plat", all = false) {
    let json = {
        id: id,
        value: value,
        type: type,
        all: all,
    };
    $.ajax({
        url: "/cart/sub/" + JSON.stringify(json),
        method: "POST",
    }).then((response) => {
        let items = response.items;
        if (Object.keys(items[type]).length !== 0) {
            if (!items[type][id]) {
                id_cart_body = "cart_body_" + type + "_" + id;
                if ($("." + id_cart_body)) {
                    $("." + id_cart_body).hide();
                }
            } else {
                if (!items[type][id][value]) {
                    id_cart_body = "cart_body_" + type + "_" + id + "_" + value;
                    if ($("." + id_cart_body)) {
                        $("." + id_cart_body).hide();
                    }
                }
            }
        } else {
            id_cart_body = "cart_body_" + type;
            if ($("." + id_cart_body)) {
                $("." + id_cart_body).hide();
            }
        }
        addFlash(response.message[0], response.message[1]);
        setQuantity(items, response.totalArticle, response.totalPrice);
    });
}

function cleanCart(type = "") {
    if (type) {
        id = ".cart_" + type;
    } else {
        id = ".cart";
    }
    document.querySelectorAll(id).forEach((element) => {
        element.innerText = 0;
    });
}

function setQuantity(items, totalArticle, totalPrice) {
    if (typeof items === "string") {
        items = JSON.parse(items);
    }
    let quantity = 0;
    let price = 0;
    if (items && Object.keys(items).length !== 0) {
        for (const type in items) {
            if (Object.keys(items[type]).length !== 0) {
                for (const id in items[type]) {
                    switch (type) {
                        case "plat":
                            for (const value in items[type][id]) {
                                if (items[type][id] && items[type][id][value]) {
                                    quantity =
                                        items[type][id][value]["quantity"];
                                    price =
                                        quantity *
                                        items[type][id][value]["price"];
                                }
                                if (
                                    document.getElementById(
                                        id +
                                            "_" +
                                            type +
                                            "_" +
                                            value +
                                            "_quantity"
                                    )
                                ) {
                                    document.getElementById(
                                        id +
                                            "_" +
                                            type +
                                            "_" +
                                            value +
                                            "_quantity"
                                    ).innerText = quantity;
                                }

                                if (
                                    document.getElementById(
                                        id + "_" + type + "_" + value + "_price"
                                    )
                                ) {
                                    document.getElementById(
                                        id + "_" + type + "_" + value + "_price"
                                    ).innerText = price + " Dhs";
                                }
                            }
                            break;
                        default:
                            if (
                                items[type] &&
                                items[type][id] &&
                                items[type][id]["quantity"]
                            ) {
                                quantity = items[type][id]["quantity"];
                                price = quantity * items[type][id]["price"];
                            }
                            document
                                .querySelectorAll(
                                    "#" + type + "_" + id + "_quantity"
                                )
                                .forEach((element) => {
                                    element.innerText = quantity;
                                });
                            document
                                .querySelectorAll(
                                    "#" + type + "_" + id + "_price"
                                )
                                .forEach((element) => {
                                    element.innerText = price + " Dhs";
                                });
                            break;
                    }
                }
            } else {
                cleanCart(type);
            }
        }
    } else {
        cleanCart();
    }
    replaceCartElements(totalArticle, totalPrice);
}

function replaceCartElements(totalArticle, totalPrice) {
    if (totalArticle) {
        if (document.getElementById("cart")) {
            document.getElementById("cart").innerText = totalArticle;
        }

        if (document.getElementById("cart_total_items")) {
            document.getElementById("cart_total_items").innerText =
                totalArticle + " items";
        }
        if (document.getElementById("cart_total_items_price")) {
            document.getElementById("cart_total_items_price").innerText =
                totalPrice + " Dhs";
        }
    } else {
        if (document.getElementById("cart")) {
            document.getElementById("cart").innerText = "";
        }

        if (document.getElementById("cart_total_items")) {
            document.getElementById("cart_total_items").innerText = "0 items";
        }
        if (document.getElementById("cart_total_items_price")) {
            document.getElementById("cart_total_items_price").innerText =
                "0 Dhs";
        }
        if ($(".cart_empty")) {
            $(".cart_empty").show();
        }
        if ($(".checkout_btn")) {
            $(".checkout_btn").hide();
        }
    }
}

window.cleanCart = cleanCart;
window.addProductToCart = addProductToCart;
window.removeProductFromCart = removeProductFromCart;
window.setQuantity = setQuantity;
