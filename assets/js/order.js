function changeStatus(id) {
    let ele = document.getElementsByName(id + "-admin-order-status");

    for (i = 0; i < ele.length; i++) {
        if (ele[i].checked) value = ele[i].value;
    }
    $.ajax({
        url: "/account/order/status/" + id + "/" + value,
        method: "POST",
    }).then((response) => {
        if (value == "En cours") {
            document.getElementById(id + "-admin-order-status").innerHTML =
                "En cours";
        } else {
            document.getElementById(id + "-admin-order-status").innerHTML =
                "Livrée";
        }
        //addFlash(response.message[0], response.message[1]);
    });
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
        if (value) {
            document.getElementById(id + "-admin-order-payment").innerHTML =
                "Payé";
            $("#" + id + "-admin-order-payment").removeClass("badge-danger");
            $("#" + id + "-admin-order-payment").addClass("badge-success");
        } else {
            document.getElementById(id + "-admin-order-payment").innerHTML =
                "Non payé";
            $("#" + id + "-admin-order-payment").removeClass("badge-success");
            $("#" + id + "-admin-order-payment").addClass("badge-danger");
        }

        //addFlash(response.message[0], response.message[1]);
    });
}

window.changeStatus = changeStatus;
window.changePayment = changePayment;
