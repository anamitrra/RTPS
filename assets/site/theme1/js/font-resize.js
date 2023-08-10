/* 
Max Font increase = DEFAULT_FONT_SIZE + 3
Max Font decrease = DEFAULT_FONT_SIZE - 3
*/

$(document).ready(function () {

    const FONT_SIZE_KEY = 'rtps-font-size';
    const DEFAULT_FONT_SIZE = 15;

    const FONT_FAMILY_KEY = 'rtps-font-family';
    const DEFAULT_FONT_FAMILY = 'Nunito';

    // Set default font
    const pageFontSize = localStorage.getItem(FONT_SIZE_KEY) ? parseInt(localStorage.getItem(FONT_SIZE_KEY)) : DEFAULT_FONT_SIZE;
    const pageFontFamily = localStorage.getItem(FONT_FAMILY_KEY) ? localStorage.getItem(FONT_FAMILY_KEY) : DEFAULT_FONT_FAMILY;

    $('html').css('font-size', `${pageFontSize}px`);  // font size
    document.querySelector(':root').style.setProperty('--main-font', pageFontFamily);   // font family


    // Font size change
    $('[data-font]').on('click', function (e) {
        e.preventDefault();

        let fontSize = DEFAULT_FONT_SIZE;

        switch ($(this).attr('data-font')) {
            case '+':

                fontSize = parseInt($('html').css('font-size')) + 1;

                if (fontSize > DEFAULT_FONT_SIZE + 3) {
                    return;
                }

                $('html').css('font-size', `${fontSize}px`);

                break;

            case '0':
                $('html').css('font-size', `${DEFAULT_FONT_SIZE}px`);

                break;

            case '-':

                fontSize = parseInt($('html').css('font-size')) - 1;

                if (fontSize < DEFAULT_FONT_SIZE - 3) {
                    return;
                }

                $('html').css('font-size', `${fontSize}px`);

                break;
        }

        // Save the font size in localStorage
        localStorage.setItem(FONT_SIZE_KEY, `${fontSize}`);

    });

    // Font family Change
    $('[data-fontname]').on('click', function (e) {
        e.preventDefault();
        const fontName = $(this).attr('data-fontname');
        const root = document.querySelector(':root');
        root.style.setProperty('--main-font', fontName);

        // Save the font family in localStorage
        localStorage.setItem(FONT_FAMILY_KEY, fontName);

    });

});
