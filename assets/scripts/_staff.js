console.log(1);
// create input elements foreach piece
$(".btn-edit").click(function(e) {

    var id = $(e.target).data('id');
    //var eventTitle = $("div[data-id='" + id +"']").find("h5").text();
    //var eventDate = $("div[data-id='" + id +"']").find(".date").text().split("/");
    //var eventDateFormatted = eventDate[2] + "-" + eventDate[0] + "-" + eventDate[1];
    //var eventDesc = $("div[data-id='" + id +"']").find(".desc").text();

    var staffFName = $("div[data-id='" + id + "']").find(".fname").text();
    var staffLName = 0;
    var staffTitle = 0;
    var staffEmail = 0;
    var staffPhone = 0;

    $("#staffFname").val(staffFname);
    //$("#eventDate").val(eventDateFormatted);
    //$("#eventDesc").val(eventDesc);

    //$("#eventid").val(id);

    //console.log($("#eventid").val());
    // find the current buttons id

    // find the row for element in dom

    // add input element around each child

});