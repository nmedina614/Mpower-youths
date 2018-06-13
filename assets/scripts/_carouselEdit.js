// when edit staff member button is clicked
$(".btn-edit").click(function(e) {

    // change modal title for modifying
    $("#exampleModalLabel").text("Modify Carousel Item");

    // get id of staff member to be edited
    var id = $(e.target).data('id');

    // get variables for autofilling edit form
    var header = $("div[data-id='" + id + "']").find(".header").text();
    var paragraph = $("div[data-id='" + id + "']").find(".paragraph").text();
    var buttonLink = $("div[data-id='" + id + "']").find(".button").attr("href");
    var buttonText = $("div[data-id='" + id + "']").find(".button").text();
    var imageURL = $("div[data-id='" + id + "']").find(".image").attr("src");
    var pageOrder = $(e.target).data('order');

    fillModal("Modify Staff Member", header, paragraph, buttonLink, buttonText, imageURL, id, pageOrder);

});

// when add staff member button is clicked
$(".btn-add").click(function(e) {

    fillModal("Add Carousel Item");

});

function fillModal(modalTitle, header = "", paragraph = "", buttonLink = "",
                   buttonText = "", imageURL = "", id = -1, pageOrder = 0) {

    $("#exampleModalLabel").text(modalTitle);

    // populate the form with the current staff member's data
    $("#header").val(header);
    $("#paragraph").val(paragraph);
    $("#buttonLink").val(buttonLink);
    $("#buttonText").val(buttonText);
    $("#imageURL").val(imageURL);
    $("#pageOrder").val(pageOrder);

    // set the ID for the form
    $("#idcarousel").val(id);
}

// when delete staff member button is clicked
$('.btn-delete').click(function(e) {

    var id = $(e.target).data('id');
    let confirmed = confirm("Are you sure you want to remove this item from the carousel?");

    if(confirmed) {

        $.ajax('ajax-delete-member', {
            method : "POST",
            data : {id : id, memberType : 'carousel', idColumnName : 'idcarousel', imageFolderName : 'carousel'},
            dataType : 'json',
            success : function(response) {
                if(response == true) {
                    location.reload();
                    alert("Carousel item removed!");
                } else {
                    alert(response);
                }
            },
            error : function() {
                console.log("Failed to connect!");
            }
        });
    }
});

// when shift member up button is clicked
$('.btn-shift-up').click(function(e) {

    var id = $(e.target).data('id');

    $.ajax('ajax-shift-member', {
        method : "POST",
        data : {id : id, memberType : 'carousel', idColumnName : 'idcarousel', direction : 'up'},
        dataType : 'json',
        success : function(response) {
            if (response == true) {
                location.reload();
            } else {
                alert(response);
            }
        },
        error : function() {
            console.log("Failed to connect!");
        }
    });
});

// when shift member down button is clicked
$('.btn-shift-down').click(function(e) {

    var id = $(e.target).data('id');

    $.ajax('ajax-shift-member', {
        method : "POST",
        data : {id : id, memberType : 'carousel', idColumnName : 'idcarousel', direction : 'down'},
        dataType : 'json',
        success : function(response) {
            if (response == true) {
                location.reload();
            } else {
                alert(response);
            }
        },
        error : function() {
            console.log("Failed to connect!");
        }
    });
});