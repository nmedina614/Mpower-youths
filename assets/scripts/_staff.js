// create input elements foreach piece
$(".btn-edit").click(function(e) {

    var id = $(e.target).data('id');

    var staffFName = $("div[data-id='" + id + "']").find(".fname").text();
    var staffLName = $("div[data-id='" + id + "']").find(".lname").text();
    var staffTitle = $("div[data-id='" + id + "']").find(".title").text();
    var staffEmail = $("div[data-id='" + id + "']").find(".email").text();
    var staffPhone = $("div[data-id='" + id + "']").find(".phone").text();
    var staffBio = $("div[data-id='" + id + "']").find(".biography").text();

    $("#staffFName").val(staffFName);
    $("#staffLName").val(staffLName);
    $("#staffTitle").val(staffTitle);
    $("#staffEmail").val(staffEmail);
    $("#staffPhone").val(staffPhone);
    $("#staffBio").val(staffBio);

    $("#staffid").val(id);

});