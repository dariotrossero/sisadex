var years = [1,2,3,4,5];
var cuats = [1, 2, 1, 2, 1, 2, 1, 2, 1, 2];
var filter_button_clicked = false;

/**
 * Cuando comienza la peticion ajax muestro la animacion de carga
 */
$(document).ajaxStart(function () {
    $(".circle").show();
    $(".circle1").show();
});

/**
 * Cuando termina la peticion ajax oculto la animacion de carga
 */
$(document).ajaxComplete(function () {
    $(".circle").hide();
    $(".circle1").hide();
});


function getYear(n) {
    switch (n) {
        case 1:
            return 1;
            break;
        case 2:
            return 1;
            break;
        case 3:
            return 2;
            break;
        case 4:
            return 2;
            break;
        case 5:
            return 3;
            break;
        case 6:
            return 3;
            break;
        case 7:
            return 4;
            break;
        case 8:
            return 4;
            break;
        case 9:
            return 5;
            break;
        case 10:
            return 5;
            break;
    }
}


function getComplementButton(n) {
    switch (n) {
        case 1:
            return 2;
            break;
        case 2:
            return 1;
            break;
        case 3:
            return 4;
            break;
        case 4:
            return 3;
            break;
        case 5:
            return 6;
            break;
        case 6:
            return 5;
            break;
        case 7:
            return 8;
            break;
        case 8:
            return 7;
            break;
        case 9:
            return 10;
            break;
        case 10:
            return 9;
            break;

    }
}