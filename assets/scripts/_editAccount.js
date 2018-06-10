$("#username").on('input', function () {
   validateNotEmpty($(this));
});

function validateNotEmpty(element){
    var isValid = !(element.val().length === 0) && !(element.val().includes(' '));
    if (isValid){
        element.addClass("is-valid");
        element.removeClass("is-invalid");
    }else{
        element.addClass("is-invalid");
        element.removeClass("is-valid");
    }
}