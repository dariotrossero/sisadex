timeline = new links.Timeline(document.getElementById('target'));
var options = {
    'width': '890px',
    'axisOnTop': true,
    'locale': 'es',
    'height': '500px',
    'editable': false,   // enable dragging and editing events
    'style': 'box',
    'showCurrentTime': false,
    'showNavigation': true,
    'min': new Date(new Date().getFullYear()-5, 1, 1),
    'max': new Date(new Date().getFullYear(), 12, 31),
    'zoomMin': 864000000,
    'start': new Date(new Date().getFullYear(), 1, 1),
    'end': new Date(new Date().getFullYear(), 12, 31)
};
var fadeTime = 400;
var timeline;
var data = [];
var subjects = new Array();
var infoExams = [];
var plans = new Array();
var currentYear = new Date().getFullYear();
var zoomed = false;
function setData() {
    data = [];
    for (var i = 0; i < infoExams.length; i++) {
        data.push({
            'start': new Date(infoExams[i][0].anio, infoExams[i][0].mes - 1,
                infoExams[i][0].dia),
            'content': infoExams[i][0].content,
            'className': infoExams[i][0].classname
        });
    }
    drawVisualization();
}

// Called when the Visualization API is loaded.
function drawVisualization() {
    // Instantiate our timeline object.
    // attach an event listener using the links events handler
    // Draw our timeline with the created data and options
    timeline.draw(data, options);
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



function reset() {
    data = [];
    infoExams = [];
    subjects = [];
    plans = [];
    timeline.deleteAllItems();
    $('#target-area').find('span').fadeOut(fadeTime, function () {
        this.remove();
    });
}

function dragElement(materia, event, number) {
    contenedor = materia.id + "|" + number;
    event.dataTransfer.setData("text", contenedor);
}

function dropElement(target, event) {
    event.preventDefault();
    contenedor = event.dataTransfer.getData("text");
    arreglo = contenedor.split("|");
    var materia = arreglo[0];
    var source = arreglo[1];
    var a = document.createElement('a');
    a.id = materia;
    a.setAttribute("onclick", 'removeElementFromCalendar(' + a.id + ');');
    a.setAttribute("class", "boxclose");
    if (source == 0)
        a.setAttribute("name", "plan");
    else
        a.setAttribute("name", "materia");
    a.innerHTML = '<img src="' + baseUrl + '/images/close-icon.gif"/>';
    elemento = document.getElementById(materia).cloneNode(true);
    elemento.appendChild(a);
    if (target.id == "target" && $('#target-area #' + materia + '').length == 0) {
        $('#target-area').append(elemento);
        if (source == 1)
            subjects.push(materia);
        else
            plans.push(materia);
    }
    getInfoFromServer();
}

function getInfoFromServer() {
    console.log("Getting information from server....");
    var jsonStringsubjects = JSON.stringify(subjects);
    var jsonStringPlans = JSON.stringify(plans);
    var jsonStringYears = JSON.stringify(years);
    $.ajax({
        type: "POST",
        url: 'GetExamsTimeline',
        data: {
            materias: jsonStringsubjects,
            planes: jsonStringPlans,
            anios: jsonStringYears,
            cuat: cuat,
            currentYear:currentYear
        },
        cache: false,
        success: function (respuesta) {
            infoExams = respuesta.result;
            setData();
            if (zoomed) 
               zoom();
            go2year(currentYear);
        }
    });
}

/**
 * Necesario para que el drap and drop se comporte como drag and copy y no mueva el elemento.
 */
function allowDrop(event) {
    event.preventDefault();
}

function removeElementFromCalendar(id) {
    id = id.toString().trim();
    elemento = $('#target-area #' + id);
    if (elemento.attr("class") == "materia") {
        subjects.splice(_.indexOf(subjects, id), 1);
    }
    else
        plans.splice(_.indexOf(plans, id), 1);
    getInfoFromServer();
    elemento.fadeOut(fadeTime, function () {
        elemento.remove();
    });
}

$(document).ready(function () {
    $(".circle").hide();
    $(".circle1").hide();
    timeLineProperties = {
        width: $("#target").width(),
        height: $("#target").height()
    };
});

function restore() {
    timeline.options.width = timeLineProperties.width + 'px';
    $("#materias-metricas").show(250);
    $("#planes-metricas").show(250);
    $("#middle").animate({
        'min-width': "890px",
        'min-height': "645px"
    }, 250);
    $("#target").animate({
        width: "890px",
        height: "500px"
    }, 250);
    timeline.setSize(timeLineProperties.width + 'px', '500px');
    zoomed=false;
}

function zoom() {
    timeline.setSize("1200px", "500px");
    $("#materias-metricas").hide(250);
    $("#planes-metricas").hide(250);
    timeline.options.width = '1200px';
    $("#middle").animate({
        'min-width': "1200px",
        'min-height': "700px"
    }, 250);
    $("#target").animate({
        width: "1200px",
        height: "700px"
    }, 250);
    zoomed=true;
}

function go2FirstCuat() {
    cuat = 1;
    getInfoFromServer();
    timeline.setVisibleChartRange(new Date(currentYear, 2, 1), new Date(currentYear, 7, 1));
}

function go2SecondCuat() {
    cuat = 2;
    getInfoFromServer();
    timeline.setVisibleChartRange(new Date(currentYear, 7, 1), new Date(currentYear + 1, 1, 1));
}

function go2year(year) {
    timeline.setVisibleChartRange(new Date(year, 2, 1), new Date(year , 12, 1));
}

function clickYear(year) {
    if (!filter_button_clicked) {
        filter_button_clicked = true;
        years = [];
        }

    if ($('#anio_' + year.toString()).hasClass('active')) {
        index = years.indexOf(year);
        if (index > -1) years.splice(index, 1);
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
    getInfoFromServer();
}

$( "#yearList" ).change(function() {
  currentYear = $('#yearList').find(":selected").text();

getInfoFromServer();
  });
