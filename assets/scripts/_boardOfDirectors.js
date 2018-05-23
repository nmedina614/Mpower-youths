// when edit BOD member button is clicked
$(".btn-edit").click(function(e) {

    // change modal title for modifying
    $("#exampleModalLabel").text("Modify Director");

    // get id of BOD member to be edited
    var id = $(e.target).data('id');

    // get variables for autofilling edit form
    var BODFName = $("div[data-id='" + id + "']").find(".fname").text();
    var BODLName = $("div[data-id='" + id + "']").find(".lname").text();
    var BODTitle = $("div[data-id='" + id + "']").find(".title").text();
    var BODEmail = $("div[data-id='" + id + "']").find(".email").text();
    var BODPhone = $("div[data-id='" + id + "']").find(".phone").text();
    var BODBio = $("div[data-id='" + id + "']").find(".biography").text();
    var BODImage = $("div[data-id='" + id + "']").find(".image").attr("src");

    fillModal("Modify BOD Member", BODFName, BODLName, BODTitle, BODEmail, BODPhone, BODBio, BODImage, id);

});

// when add BOD member button is clicked
$(".btn-add").click(function(e) {

    fillModal("Add Director");

});

function fillModal(modalTitle, BODFName = "", BODLName = "", BODTitle = "", BODEmail = "",
                   BODPhone = "", BODBio = "", BODImage = "", id = -1) {

    $("#exampleModalLabel").text(modalTitle);

    // populate the form with the current BOD member's data
    $("#BODFName").val(BODFName);
    $("#BODLName").val(BODLName);
    $("#BODTitle").val(BODTitle);
    $("#BODEmail").val(BODEmail);
    $("#BODPhone").val(BODPhone);
    $("#BODBio").val(BODBio);
    $("#BODImage").val(BODImage);

    // set the ID for the form
    $("#idbod").val(id);
}