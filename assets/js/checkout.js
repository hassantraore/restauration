document.addEventListener("DOMContentLoaded", function () {
    initCheckout();
});
var delivery = ["deliver", "take_away"];
var payment_methodes = {
    especes: "especes",
    credit_cart: "credit card",
    paypal: "paypal",
};
var checkout = {};

function initCheckout() {
    checkout = {
        livraison: "deliver",
        addresse: "",
        identifiant: { nom: "", tel: "" },
        method_payment: "",
        shipping: 15,
    };
}

function setIdentifiant(name) {
    checkout.identifiant[name] = $("#" + name).val();
}

function order() {
    let error = false;

    switch (checkout.livraison) {
        case "deliver":
            if (!checkout.addresse) {
                error = true;
                $("#addresseError").show();
            }
            break;

        case "take_away":
            if (!checkout.identifiant.nom) {
                error = true;
                $("#nom").addClass("is-invalid");
                $("#nomError").show();
            } else {
                $("#nom").removeClass("is-invalid");
                $("#nomError").hide();
            }
            if (!checkout.identifiant.tel) {
                error = true;
                $("#tel").addClass("is-invalid");
                $("#telError").show();
            } else {
                $("#tel").removeClass("is-invalid");
                $("#telError").hide();
            }
            break;
    }
    if (!checkout.method_payment) {
        error = true;
        $("#paymentError").show();
    }

    if (!error) {
        console.log(checkout.method_payment);
        if (checkout.method_payment != "paypal") {
            window.location.href =
                "/checkout/payment/" + JSON.stringify(checkout);
        }
    }
}

function acceptValue(event) {
    var regex = new RegExp("^[0-9+]+$");
    var key = String.fromCharCode(
        !event.charCode ? event.which : event.charCode
    );
    console.log(regex.test(key));
    if (!regex.test(key)) {
        event.preventDefault();
        return false;
    }
}
function setDelivery(type) {
    checkout.livraison = type;

    let selected;
    let not_selected;
    for (let del of delivery) {
        if (del == type) {
            selected = del;
        } else {
            not_selected = del;
        }
    }

    selectElement(not_selected, "unselect");
    selectElement("icon-" + not_selected, "unselect");
    selectElement(selected, "select");
    selectElement("icon-" + selected, "select");

    $("#coordonnee-" + selected).show();
    $("#coordonnee-" + not_selected).hide();
}

function setAddresse(addresses, id) {
    $("#addresseError").hide();
    for (const addresse_id of addresses) {
        let name = "addresse-" + addresse_id;
        if (addresse_id == id) {
            selectElement(name, "select");
            $("#addresse-radio-" + addresse_id).prop("checked", true);
            checkout.addresse = id;
        } else {
            selectElement(name, "unselect");
            /* $("#addresse-radio-" + addresse_id).removeAttr("checked"); */
        }
    }
}

function setPaymentMethod(payment_methode) {
    $("#paymentError").hide();
    for (const method in payment_methodes) {
        id = "payment-" + method;
        if (method == payment_methode) {
            selectElement(id, "select");
            $("#payment-radio-" + method).prop("checked", true);
            checkout.method_payment = payment_methodes[method];
        } else {
            selectElement(id, "unselect");
        }
    }
}

function selectElement(id, type) {
    if (type == "select") {
        $("#" + id).removeClass("not-selected");
        $("#" + id).addClass("selected");
    } else if (type == "unselect") {
        $("#" + id).removeClass("selected");
        $("#" + id).addClass("not-selected");
    }
}

window.setIdentifiant = setIdentifiant;
window.setDelivery = setDelivery;
window.setPaymentMethod = setPaymentMethod;
window.setAddresse = setAddresse;
window.acceptValue = acceptValue;
window.order = order;
