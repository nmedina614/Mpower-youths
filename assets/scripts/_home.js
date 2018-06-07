// when edit event button is clicked
$(".btn-edit").click(function(e) {

    var id = $(e.target).data('id');
    var dataIdSelector = $("div[data-id='" + id +"']")

    var eventTitle = dataIdSelector.find("h5").text();
    var eventDate = dataIdSelector.find(".date").text().split("/");
    var eventDateFormatted = eventDate[2] + "-" + eventDate[0] + "-" + eventDate[1];
    var eventDesc = dataIdSelector.find(".desc").text();

    fillModal("Modify Event", eventTitle, eventDateFormatted, eventDesc, id);

});

// when add event button is clicked
$(".btn-add").click(function(e) {

    fillModal("Add Event");

});

function fillModal(modalTitle, eventTitle = "", eventDate = "mm/dd/yyyy", eventDesc = "", id = -1) {

    $("#exampleModalLabel").text(modalTitle);

    $("#eventTitle").val(eventTitle);
    $("#eventDate").val(eventDate);
    $("#eventDesc").val(eventDesc);

    $("#eventid").val(id);
    $("#route").val(window.location.href);

}

// when delete event button is clicked
$('.btn-delete').click(function(e) {

    var id = $(e.target).data('id');
    var eventTitle = $("div[data-id='" + id +"']").find("h5").text();
    let confirmed = confirm("Are you sure you want to delete the event titled, '" + eventTitle + "'?");

    if(confirmed) {

        $.ajax('ajax-delete-event', {
            method : "POST",
            data : {id : id},
            dataType : 'json',
            success : function(response) {
                if(response == true) {
                    location.reload();
                    alert("Event removed!")
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