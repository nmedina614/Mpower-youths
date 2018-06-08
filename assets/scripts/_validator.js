// Bsharp
// Validates input for various form elements off regex and bootstrap validation classes
// Optional to make form not submit without correct input - use class 'validation-halt' on submit button

// classes =
// validation-halt -> used to stop button from working that submits form until input is correct
// validation-optional -> used to allow a field to be empty w/out needing to change regex
// all classes in regex JSON key's

// leave off regex pre/post /'s
const regex = {
    "name-validate": ["^([^0-9]{1,60})$", "Please enter a name with between 1-60 characters"],
    "account-validate": ["^(\\w|\\d){1,60}$", "Please enter an account 1-60 letters or numbers"],
    "school-validate": ["^([^0-9]{1,60})$", "Please enter a school 1 to 60 characters"],
    "grade-validate": ["^([0-9]{1,2})$", "Please enter a grade with 1-2 numbers"],
    "instrument-validate": ["^([^0-9]{1,60})$", "Please enter an instrument 1 to 60 characters"],
    "email-validate": ["^(([^<>()\\[\\]\\\\.,;:\\s@\"]+(\\.[^<>()\\[\\]\\\\.,;:\\s@\"]+)*)|(\".+\"))@((\\[[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\.[0-9]{1,3}\\])|(([a-zA-Z\\-0-9]+\\.)+[a-zA-Z]{2,}))$", "Please enter a name with dashes and letters"],
    "phone-validate": ["^\\d{10}$", "Please enter a phone with 10 numbers - no symbols"],
    "address-validate": ["^[\\w]{1,60}$", "Please enter an address 1 to 60 characters"],
    "city-validate": ["^([^0-9]{1,60})$", "Please enter a city 1 to 60 characters"],
    "zip-validate": ["^\\d\\d\\d\\d\\d$", "Please enter a zip code with 5 numbers"],
    "allergies-validate": ["^([^0-9]{1,60})$", "Please enter an allergy list 1 to 60 characters"],
    "serial-validate": ["^([0-9]{1,20})$", "Please enter a serial number with numbers only"],
    "make-validate": ["^([^0-9]{1,30})$", "Please enter a make 1 to 30 characters"],
    "model-validate": ["^([^0-9]{1,30})$", "Please enter a model 1 to 30 characters"]
};

let stopSendClass = "validation-halt";
let optionClass = "validation-optional";

$(document).ready(function() {
    // bind regex evaluation on each element
    bindInputs();

    // double check before submitting form that the data is valid!
    $("." + stopSendClass).on("click", function(event) {
        // manually run input validation
        forceValidationRun();

        let invalidInputs = $(".is-invalid").length;
        let invalidFeedbacks = $(".invalid-feedback").length;

        if (invalidFeedbacks + invalidInputs !== 0){
            alert("Input is incorrect!");
            event.stopPropagation();
        }
    });
});

// forces the validation to run before the user presses the send button for every element
function forceValidationRun(){
    // check for each key in element's classes
    for (let key in regex) {
        if (regex.hasOwnProperty(key)) {
            $("." + key).each(function (index) {
                let input = $(this);
                let feedback = input.siblings("div").first();
                evaluateRegexForElement(input, feedback, regex[key]);
            })
        }
    }
}

// binds all input elements to update regex on each typed letter
function bindInputs() {
    // check for each key in element's classes
    for (let key in regex) {
        if (regex.hasOwnProperty(key)) {
            $("." + key).each(function (index) {
                let input = $(this);
                // create response div for custom feedback
                let feedback = $( "<div></div>" );
                feedback.insertAfter(input);
                // on input change check the regex
                $(this).on('input', function () {
                    evaluateRegexForElement(input, feedback, regex[key]);
                });
            })
        }
    }
}

// loops over pairs of elements and checks the regex along with assigning a warning message
function evaluateRegexForElement(input, feedback, regexJson){
    let inputString = input.val();
    let regexp = new RegExp(regexJson[0]);
    let regexWarning = regexJson[1];
    let doesMatch = regexp.test(inputString);

    // make it so optional tags are no evaluated when empty
    let hasOption = input.hasClass(optionClass);

    if(hasOption && inputString.length === 0){
        return;
    }

    if (doesMatch){
        feedback.addClass('valid-feedback');
        input.addClass('is-valid');
        feedback.removeClass('invalid-feedback');
        input.removeClass('is-invalid');
        feedback.text("Looks good!");
    }else {
        feedback.addClass('invalid-feedback');
        input.addClass('is-invalid');
        feedback.removeClass('valid-feedback');
        input.removeClass('is-valid');
        feedback.text(regexWarning);
    }
}