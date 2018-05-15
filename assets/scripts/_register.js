$(document).ready(function () {

    // Prevent from entering anything but numbers in phone field.
    $('#phone').keydown(function (e) {
        // Allow certain keys.
        var KeyID = event.keyCode;
        switch(KeyID)
        {
            case 8:
                return;
            case 37:
                return;
            case 38:
                return;
            case 39:
                return;
            case 40:
                return;
            case 46:
                return;
            default:
                break;
        }

        var k = String.fromCharCode(e.which);

        if (k.match(/[^0-9]/g))
            e.preventDefault();

    });

    $("input[type='password']").keypress(function() {
        setInterval(function() { validatePasswords() }, 1000);
    });
});

function validatePasswords() {
    let pass1 = $('#password1').val();
    let pass2 = $('#password2').val();

    if(validPasswords(pass1, pass2)) {
        $("input[type='password']").addClass('border-success');
        $("input[type='password']").removeClass('border-danger');
    } else {
        $("input[type='password']").addClass('border-danger');
        $("input[type='password']").removeClass('border-success');
    }
}

function validPasswords(pass1, pass2) {
    const MIN_LENGTH = 6;
    if(pass1 !== pass2) {
        return false;
    } else if(pass1.length < MIN_LENGTH || pass2.length < MIN_LENGTH) {
        return false
    }

    return true;
}