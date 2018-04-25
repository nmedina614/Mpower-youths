$('.gallery-thumbnail').click(function() {
    openOverlay()
});
$('#overlay').click(function() {
    closeOverlay()
});

function openOverlay() {
    $('#overlay').fadeIn();
}

function closeOverlay() {
    $('#overlay').fadeOut();
}
