var imageInput = document.getElementById('image');
if(imageInput) {
    imageInput.onchange = readFormImage;
}

$('.btn-del-image').click(function() {
    deleteGalleryImage(this);
});
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

function deleteGalleryImage(target) {
    let confirmed = confirm("Are you sure you want to delete this image?");
    if(confirmed) {
        let imageSrc = $(target).parent().find('.gallery-thumbnail').attr('src');
        let targetImage = imageSrc.split("/").pop();

        $.ajax('ajax-delete-image', {
            method : "POST",
            data : {image : targetImage},
            dataType : 'json',
            success : function(response) {
                if(response == true) {
                    location.reload();
                } else {
                    alert(response);
                }
            },
            error : function() {
                console.log("Failed to connect!");
            }
        });
    }

}


