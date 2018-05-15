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

});

// when add staff member button is clicked
$(".btn-add").click(function(e) {

    // change modal title for adding
    $("#exampleModalLabel").text("Add Staff Member");

    // value indicating the member needs to be added
    $("#staffid").val(-1);

});