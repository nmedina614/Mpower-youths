$('.gallery-thumbnail').click(function() {
    openOverlay($(this));
});
$('#overlay').click(function() {
    closeOverlay()
});

function openOverlay(sourceImage) {
    // Gather information to populate overlay with.
    let base = $('#overlay').data('base');
    let src = $(sourceImage).data('source');
    let caption = $(sourceImage).data('caption');

    // Replace current overlay data with gathered data then display.
    $('#overlay-image').attr('src', base + '/assets/images/gallery/' + src);
    $('#overlay-caption').text(caption);
    $('#overlay').fadeIn();
}

function closeOverlay() {
    $('#overlay').fadeOut();
}
