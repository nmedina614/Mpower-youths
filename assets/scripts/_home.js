function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}

// when edit event button is clicked
$(".btn-edit").click(function(e) {

    var id = $(e.target).data('id');
    var eventTitle = $("div[data-id='" + id +"']").find("h5").text();
    var eventDate = $("div[data-id='" + id +"']").find(".date").text().split("/");
    var eventDateFormatted = eventDate[2] + "-" + eventDate[0] + "-" + eventDate[1];
    var eventDesc = $("div[data-id='" + id +"']").find(".desc").text();

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

}