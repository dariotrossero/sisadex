Date.prototype.customFormat = function (formatString) {
    var YYYY, YY, MMMM, MMM, MM, M, DDDD, DDD, DD, D, hhh, hh, h, mm, m, ss, s, ampm, AMPM, dMod, th;
    var dateObject = this;
    YY = ((YYYY = dateObject.getFullYear()) + "").slice(-2);
    MM = (M = dateObject.getMonth() + 1) < 10 ? ('0' + M) : M;
    MMM = (MMMM = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"][M - 1]).substring(0, 3);
    DD = (D = dateObject.getDate()) < 10 ? ('0' + D) : D;
    DDD = (DDDD = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"][dateObject.getDay()]).substring(0, 3);
    th = (D >= 10 && D <= 20) ? 'th' : ((dMod = D % 10) == 1) ? 'st' : (dMod == 2) ? 'nd' : (dMod == 3) ? 'rd' : 'th';
    formatString = formatString.replace("#YYYY#", YYYY).replace("#YY#", YY).replace("#MMMM#", MMMM).replace("#MMM#", MMM).replace("#MM#", MM).replace("#M#", M).replace("#DDDD#", DDDD).replace("#DDD#", DDD).replace("#DD#", DD).replace("#D#", D).replace("#th#", th);
    h = (hhh = dateObject.getHours());
    if (h == 0) h = 24;
    if (h > 12) h -= 12;
    hh = h < 10 ? ('0' + h) : h;
    AMPM = (ampm = hhh < 12 ? 'am' : 'pm').toUpperCase();
    mm = (m = dateObject.getMinutes()) < 10 ? ('0' + m) : m;
    ss = (s = dateObject.getSeconds()) < 10 ? ('0' + s) : s;
    return formatString.replace("#hhh#", hhh).replace("#hh#", hh).replace("#h#", h).replace("#mm#", mm).replace("#m#", m).replace("#ss#", ss).replace("#s#", s).replace("#ampm#", ampm).replace("#AMPM#", AMPM);
}
var fadeTime = 400;
var startingMonth;
var currentYear = new Date().getFullYear();
var currentMonth = new Date().getMonth() + 1;
if (1 <= currentMonth && currentMonth <= 7)
    startingMonth = 2;
else
    startingMonth = 7;
var data2display = {};
var infoExams = {};
var subjects = new Array();
var plans = new Array();
var cal = new CalHeatMap();
cal.init({
    itemSelector: "#target",
    domain: "month",
    subDomain: "day",
    cellSize: 28,
    legendVerticalPosition: "top",
    legendOrientation: "horizontal",
    legendHorizontalPosition: "left",
    legendCellSize: 20,
    weekStartOnMonday: false,
    verticalOrientation: false,
    domainTextFormat: "%d",
    subDomainTextFormat: "%d",
    cellRadius: 4,
    domainMargin: 2,
    itemName: ["puntos de carga académica", "puntos de carga academica"],
    legendColors: {
        min: "white",
        max: "red",
        empty: "white"
    },
    format: {
        date: function (date) {
            moment.lang("es");
            return moment(date).format("MMMM YYYY").toUpperCase();
        },
        legend: null,
    },
    animationDuration: 500,
    range: 5, legend: [0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
    displayLegend: true,
    itemNamespace: "domainDynamicDimension",
    start: new Date(currentYear, startingMonth, 1),
    minDate: new Date(currentYear, 2),
    maxDate: new Date(currentYear, 12),
    onClick: function (date, nb) {
        var fecha = date.getTime() / 1000;
        var string = "<h2>" + date.customFormat("#DD# de #MMMM# de #YYYY#") + "</h2><br/>";
        for (x in infoExams[fecha]) {
            string = string + "<b>" + infoExams[fecha][x]['materia'] + "</b><br/>" + "<img src='" + baseUrl + "/images/down_right_arrow_small.png'/> " + infoExams[fecha][x]['tipo'] + "<br/><hr/>";
        }
        $.modal(string);
    },
    domainLabelFormat: function (date) {
        moment.lang("es");
        return moment(date).format("MMMM YYYY").toUpperCase();
    },
});

function go2FirstCuat() {
    cal.update(data2display);
    cal.options.data = data2display;
    cal.jumpTo(new Date(currentYear, 2), true);
};

function go2SecondCuat() {
    cal.update(data2display);
    cal.options.data = data2display;
    cal.jumpTo(new Date(currentYear, 7), true);
};

function go2Today() {
    cal.update(data2display);
    cal.options.data = data2display;
    cal.jumpTo(new Date(currentYear, currentMonth));
};

function reset() {
    data2display = new Array();
    subjects = new Array();
    plans = new Array();
    infoExams = new Array();
    cal.update(data2display);
    $('#target').find('span').fadeOut(fadeTime, function () {
        this.remove();
    });
};

function go2prev() {
    cal.update(data2display);
    cal.options.data = data2display;
    cal.previous();
};

function go2next() {
    cal.update(data2display);
    cal.options.data = data2display;
    cal.next();
};

function dragElement(materia, event, number) {
    contenedor = materia.id + "|" + number;
    event.dataTransfer.setData("text", contenedor);
}

function removeElementFromCalendar(id) {
    id = id.toString().trim();
    elemento = $('#target #' + id);
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
    if (target.id == "target" && $('#target #' + materia + '').length == 0) {
        target.appendChild(elemento);
        div_target = target.id;
        if (source == 1)
            subjects.push(materia);
        else
            plans.push(materia);
    }
    getInfoFromServer();
}

function getInfoFromServer() {
    var jsonStringsubjects = JSON.stringify(subjects);
    var jsonStringPlans = JSON.stringify(plans);
    var jsonStringYears = JSON.stringify(years);
    var jsonStringCuats = JSON.stringify(cuats);
    $.ajax({
        type: "POST",
        url: 'GetExams',
        data: {
            materias: jsonStringsubjects,
            planes: jsonStringPlans,
            anios: jsonStringYears,
            cuatrimestres: jsonStringCuats,
         },
        cache: false,
        success: function (respuesta) {
            infoExams = respuesta.result2;
            data2display = respuesta.result1;
            cal.update(data2display);
        }
    });
}

/**
 * Necesario para que el drap and drop se comporte como drag and copy y no mueva el elemento.
 */
function allowDrop(event) {
    event.preventDefault();
}

$(document).ready(function () {
    $(".circle").hide();
    $(".circle1").hide();
});

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


//esto es nuevo

var years = [];
var cuats = [];

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

function clickYear(year) {
    firstCuat = (year * 2).toString();
    secondCuat = ((year * 2) - 1).toString();
    if ($('#anio_' + year.toString()).hasClass('active')) {
        index = years.indexOf(year);
        if (index > -1) years.splice(index, 1);
        removeCuat(year * 2, true);
        removeCuat((year * 2) - 1, true);
    }
    else {
        years.push(year);
        addCuat(1);
        addCuat(2);
        $('#btn_' + firstCuat).addClass('active');
        $('#btn_' + secondCuat).addClass('active');
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
function removeCuat(cuat, fromYear) {
    if (fromYear) $('#btn_' + cuat.toString()).removeClass('active');
    if (cuat % 2 == 0)
        index = cuats.indexOf(2);
    else
        index = cuats.indexOf(1);
    cuats.splice(index, 1);
}
function addCuat(cuat) {
    if (cuat % 2 == 0) cuats.push(2);
    else  cuats.push(1);
}

function clickCuat(button) {
    btn = '#btn_' + button.toString();
    btn_complement = '#btn_' + getComplementButton(button).toString();
    year = getYear(button);
    if ($(btn).hasClass('active') && (!$(btn_complement).hasClass('active'))
    ) {
        anio = getYear(button);
        if ($('#anio_' + anio.toString()).hasClass('active'))
            removeYear(anio);
    }
    if (!$(btn).hasClass('active')) {
        addCuat(button);
        addYear(year);
    }
    if ($(btn).hasClass('active'))
        removeCuat(button);
    refreshData();
}
function refreshData() {
    getInfoFromServer();
}