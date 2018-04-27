var imageInput = document.getElementById('image');
if(imageInput) {
    imageInput.onchange = readFormImage;
}

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

function readFormImage() {
    if (this.files && this.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            $('#image-preview')
                .attr('src', e.target.result)
        };

        reader.readAsDataURL(this.files[0]);
    }
}
