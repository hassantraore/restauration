$(".custom-file-input").on("change", function (e) {
    var inputFile = e.currentTarget;
    $(inputFile)
        .parent()
        .find(".custom-file-label")
        .html(inputFile.files[0].name);
});
const addFormToCollection = (e) => {
    const collectionHolder = document.querySelector(
        "." + e.currentTarget.dataset.collectionHolderClass
    );

    const item = document.createElement("li");

    item.innerHTML = collectionHolder.dataset.prototype.replace(
        /__name__/g,
        collectionHolder.dataset.index
    );

    collectionHolder.appendChild(item);

    collectionHolder.dataset.index++;

    //$("select").niceSelect();
    // add a delete link to the new form
    addTagFormDeleteLink(item);
};
document
    .querySelectorAll(".add_item_link")
    .forEach((btn) => btn.addEventListener("click", addFormToCollection));

/* delete */
const tags = document.querySelectorAll('[name="tags"]');

const addTagFormDeleteLink = (tagFormLi) => {
    const removeFormButton = document.createElement("button");
    removeFormButton.setAttribute(
        "class",
        "btn btn-outline-danger btn-sm mb-3"
    );
    removeFormButton.classList;
    removeFormButton.innerText = "Delete";

    tagFormLi.append(removeFormButton);

    removeFormButton.addEventListener("click", (e) => {
        e.preventDefault();
        // remove the li for the tag form
        tagFormLi.remove();
    });
};
tags.forEach((tag) => {
    addTagFormDeleteLink(tag);
});

function addFlash(type, message) {
    console.log(type);
    document.getElementById("flash-alert").style.display = "flex";
    $("#flash-alert").addClass("bg-gradient-" + type);
    document.getElementById("flash-message").innerHTML = message;

    setTimeout(function () {
        $("#flash-alert").hide();
    }, 7000);
}

window.addFlash = addFlash;
