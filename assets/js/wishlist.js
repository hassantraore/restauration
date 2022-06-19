document.addEventListener("DOMContentLoaded", function () {
    let items = $(".js-body").data("wishItems");
    let totalWish = $(".js-body").data("totalWish");
    replaceWishListElement(items, totalWish);
    // or with jQuery
    //var isAuthenticated = $('.js-user-rating').data('isAuthenticated');
});
function addProductToWishList(id, type) {
    let json = {
        id: id,
        type: type,
    };
    $.ajax({
        url: "/wishlist/add/" + JSON.stringify(json),
        method: "POST",
    }).then((response) => {
        console.log(response);
        addFlash(response.message[0], response.message[1]);
        replaceWishListElement(response.items, response.totalWish);
        /* setQuantity(response.items, response.totalArticle, response.totalPrice); */
    });
}

function subProductFromWishList(id, type) {
    let json = {
        id: id,
    };
    $.ajax({
        url: "/wishlist/sub/" + JSON.stringify(json),
        method: "POST",
    }).then((response) => {
        console.log(response);
        setTotalWish(response.totalWish);

        $("#" + id + "-sub-wish-" + type).hide();
        $("#" + id + "-add-wish-" + type).show();
        $("#" + id + "-wish-product-" + type).hide();
        /* setQuantity(response.items, response.totalArticle, response.totalPrice); */
    });
}

function moveProductToCart(id, value, price, type = "plat", sauce = null) {
    if (!value && !price) {
        let ele_value = document.getElementsByName(id + "-wish-product-size");

        for (i = 0; i < ele_value.length; i++) {
            if (ele_value[i].checked) {
                tmp = ele_value[i].value.split("-");
                value = tmp[0];
                price = tmp[1];
            }
        }
    }
    if (type == "plat" && !sauce) {
        let ele_sauce = document.getElementsByName(id + "-wish-product-sauce");

        for (i = 0; i < ele_sauce.length; i++) {
            if (ele_sauce[i].checked) sauce = ele_sauce[i].value;
        }
    }

    addProductToCart(id, value, price, type, sauce);
    subProductFromWishList(id, type);
}
function replaceWishListElement(items, totalWish) {
    setTotalWish(totalWish);
    if (typeof items === "string") {
        items = JSON.parse(items);
    }
    for (const id in items) {
        type = items[id];
        $("#" + id + "-add-wish-" + type).hide();
        $("#" + id + "-sub-wish-" + type).show();
    }
}

function setTotalWish(totalWish) {
    if (totalWish) {
        if (document.getElementById("wish")) {
            document.getElementById("wish").innerText = totalWish;
        }

        $("#wishlist-empty").hide();
        $("#table-wishlist").show();
    } else {
        if (document.getElementById("wish")) {
            document.getElementById("wish").innerText = "";
        }

        $("#wishlist-empty").show();
        $("#table-wishlist").hide();
    }
}

window.addProductToWishList = addProductToWishList;
window.subProductFromWishList = subProductFromWishList;
window.moveProductToCart = moveProductToCart;
