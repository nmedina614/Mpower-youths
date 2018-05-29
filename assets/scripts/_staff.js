// when edit staff member button is clicked
$(".btn-edit").click(function(e) {

    // change modal title for modifying
    $("#exampleModalLabel").text("Modify Staff Member");

    // get id of staff member to be edited
    var id = $(e.target).data('id');

    // get variables for autofilling edit form
    var staffFName = $("div[data-id='" + id + "']").find(".fname").text();
    var staffLName = $("div[data-id='" + id + "']").find(".lname").text();
    var staffTitle = $("div[data-id='" + id + "']").find(".title").text();
    var staffEmail = $("div[data-id='" + id + "']").find(".email").text();
    var staffPhone = $("div[data-id='" + id + "']").find(".phone").text();
    var staffBio = $("div[data-id='" + id + "']").find(".biography").text();
    var staffImage = $("div[data-id='" + id + "']").find(".image").attr("src");

    fillModal("Modify Staff Member", staffFName, staffLName, staffTitle, staffEmail, staffPhone, staffBio, staffImage, id);

});

// when add staff member button is clicked
$(".btn-add").click(function(e) {

    fillModal("Add Staff Member");

});

function fillModal(modalTitle, staffFName = "", staffLName = "", staffTitle = "", staffEmail = "",
                   staffPhone = "", staffBio = "", staffImage = "", id = -1) {

    $("#exampleModalLabel").text(modalTitle);

    // populate the form with the current staff member's data
    $("#staffFName").val(staffFName);
    $("#staffLName").val(staffLName);
    $("#staffTitle").val(staffTitle);
    $("#staffEmail").val(staffEmail);
    $("#staffPhone").val(staffPhone);
    $("#staffBio").val(staffBio);
    $("#staffImage").val(staffImage);

    // set the ID for the form
    $("#staffid").val(id);
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
            data : {id : id, memberType : 'staff', idColumnName : 'idstaff'},
            dataType : 'json',
            success : function(response) {
                if(response == true) {
                    location.reload();
                    alert("Staff Member removed!");
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

$('.btn-shift-up').click(function(e) {

    var id = $(e.target).data('id');

    $.ajax('ajax-shift-member', {
        method : "POST",
        data : {id : id, memberType : 'staff', idColumnName : 'idstaff', direction : 'up'},
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