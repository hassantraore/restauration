function changeStatus(id) {
    let ele = document.getElementsByName(id + "-admin-order-status");

    for (i = 0; i < ele.length; i++) {
        if (ele[i].checked) value = ele[i].value;
    }
    $.ajax({
        url: "/account/order/status/" + id + "/" + value,
        method: "POST",
    }).then((response) => {});
}
function changePayment(id) {
    let ele = document.getElementsByName(id + "-admin-order-paiement");

    for (i = 0; i < ele.length; i++) {
        if (ele[i].checked) {
            value = ele[i].value == 0 ? false : true;
        }
    }

    $.ajax({
        url: "/account/order/payment/" + id + "/" + value,
        method: "POST",
    }).then((response) => {
        /* setQuantity(response.items, response.totalArticle, response.totalPrice); */
    });
}

window.changeStatus = changeStatus;
window.changePayment = changePayment;
