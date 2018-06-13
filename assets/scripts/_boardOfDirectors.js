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
    var pageOrder = $(e.target).data('order');

    fillModal("Modify BOD Member", BODFName, BODLName, BODTitle, BODEmail, BODPhone, BODBio, BODImage, id, pageOrder);

});

// when add BOD member button is clicked
$(".btn-add").click(function(e) {

    fillModal("Add Director");

});

function fillModal(modalTitle, BODFName = "", BODLName = "", BODTitle = "", BODEmail = "",
                   BODPhone = "", BODBio = "", BODImage = "", id = -1, pageOrder = 0) {

    $("#exampleModalLabel").text(modalTitle);

    // populate the form with the current BOD member's data
    $("#BODFName").val(BODFName);
    $("#BODLName").val(BODLName);
    $("#BODTitle").val(BODTitle);
    $("#BODEmail").val(BODEmail);
    $("#BODPhone").val(BODPhone);
    $("#BODBio").val(BODBio);
    $("#BODImage").val(BODImage);
    $("#pageOrder").val(pageOrder);

    // set the ID for the form
    $("#idbod").val(id);
}

// when delete staff member button is clicked
$('.btn-delete').click(function(e) {

    var id = $(e.target).data('id');
    var fullName = $("div[data-id='" + id + "']").find(".fname").text() +
                " " + $("div[data-id='" + id + "']").find(".lname").text();
    let confirmed = confirm("Are you sure you want to remove " + fullName + " from the list?");

    if(confirmed) {

        $.ajax('ajax-delete-member', {
            method : "POST",
            data : {id : id, memberType : 'board_of_directors', idColumnName : 'idbod', imageFolderName : 'staffportraits'},
            dataType : 'json',
            success : function(response) {
                if(response == true) {
                    location.reload();
                    alert("Director removed!")
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
        data : {id : id, memberType : 'board_of_directors', idColumnName : 'idbod', direction : 'up'},
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
        data : {id : id, memberType : 'board_of_directors', idColumnName : 'idbod', direction : 'down'},
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