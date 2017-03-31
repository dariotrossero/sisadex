var Subjects = new Array();
var plans = new Array();
var infoExams = {};
var data = [];
var chartData = [];
var currentYear = new Date().getFullYear();

function loadData() {
    chart.dataProvider = generateChartData();
    chart.validateData();
}


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


function parseDate(dateString) {
    // split the string get each field
    var dateArray = dateString.split("-");
    // now lets create a new Date instance, using year, month and day as parameters
    // month count starts with 0, so we have to convert the month number
    var date = new Date(Number(dateArray[0]), Number(dateArray[1]) - 1, Number(dateArray[2]));
    return date;
}

function generateChartData() {
    chartData = [];
    for (var k in infoExams) break;
    fechas = infoExams[k];
    for (fecha in fechas) {
        obj = {};
        date = date = parseDate(fecha);
        obj.date = date;
        for (key in infoExams) {
            obj[key] = infoExams[key][fecha];
        }
        chartData.push(obj);
    }
    return chartData;
}

function getInfoFromServer() {
    var jsonStringSubjects = JSON.stringify(Subjects);
    var jsonStringPlans = JSON.stringify(plans);
    $.ajax({
        type: "POST",
        url: 'GetExamsEvolution',
        data: {
            materias: jsonStringSubjects,
            planes: jsonStringPlans,
            currentYear:currentYear
        },
        cache: false,
        success: function (respuesta) {
            infoExams = respuesta.result;
            if (!(infoExams instanceof Array)) {
                loadData();
                chart.zoom(0, 304);
            }
        }
    });
}
function refreshInfoFromServer() {
    console.log("Getting information from server....");
    var jsonStringSubjects = JSON.stringify(Subjects);
    var jsonStringPlans = JSON.stringify(plans);
    var jsonStringYears = JSON.stringify(years);
    $.ajax({
        type: "POST",
        url: 'RefreshExamsEvolution',
        data: {
            materias: jsonStringSubjects,
            planes: jsonStringPlans,
            anios: jsonStringYears,
            currentYear:currentYear,
            cuat:cuat
        },
        cache: false,
        success: function (respuesta) {
            infoExams = respuesta.result;
            if (!(infoExams instanceof Array)) {
                loadData();
                chart.zoom(0, 304);
            }
        }
    });
}

$(document).ready(function () {
    getInfoFromServer();
});


function go2FirstCuat() {
    cuat = 1;
    refreshInfoFromServer();
    //chart.zoom(0, 140);
}

function go2SecondCuat() {
    cuat = 2;
    refreshInfoFromServer();
    //chart.zoom(150, 304);
}

function clickYear(year) {
    if (!filter_button_clicked) {
        filter_button_clicked = true;
        years = [];
    }
    if ($('#anio_' + year.toString()).hasClass('active')) {
        index = years.indexOf(year);
        if (index > -1) 
            years.splice(index, 1);
    }
    else {
        years.push(year);
    }
    refreshData();
}

function removeYear(year) {
    $('#anio_' + year.toString()).removeClass('active');
    index = years.indexOf(year);
    if (index > -1) years.splice(index, 1);
}

function addYear(year) {
    $('#anio_' + year.toString()).addClass('active');
    index = years.indexOf(year);
    if (index == -1) years.push(year);
}



function refreshData() {
    refreshInfoFromServer();
}

$( "#yearList" ).change(function() {
  currentYear = $('#yearList').find(":selected").text();
    refreshData();
  });

