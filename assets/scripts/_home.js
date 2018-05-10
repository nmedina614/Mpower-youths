function googleTranslateElementInit() {
    new google.translate.TranslateElement({pageLanguage: 'en', includedLanguages: 'en,es', layout: google.translate.TranslateElement.InlineLayout.SIMPLE}, 'google_translate_element');
}

// create input elements foreach piece
$("input[id*='edit']" ).each(function () {
    $(this).click(function () {
        // find the current buttons id

        // find the row for element in dom

        // add input element around each child

    });
});