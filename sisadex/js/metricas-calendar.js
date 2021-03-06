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
var subDomain = "x_day";
cal.init({
    itemSelector: "#target",
    domain: "month",
    subDomain: subDomain,
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
        legend: null
    },
    animationDuration: 1,
    range: 5, legend: [0, 2, 4, 6, 8, 10, 12, 14, 16, 18, 20],
    displayLegend: true,
    itemNamespace: "domainDynamicDimension",
    start: new Date(currentYear, startingMonth, 1),
    minDate: new Date(currentYear-3, 2),
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
    }
});

function go2FirstCuat() {
    cuat = 1;
    getInfoFromServer();
    cal.update(data2display);
    cal.options.data = data2display;
    cal.jumpTo(new Date(currentYear, 2), true);

};

function go2SecondCuat() {
    cuat = 2;
    getInfoFromServer();
    cal.update(data2display);
    cal.options.data = data2display;
    cal.jumpTo(new Date(currentYear, 7), true);
};

function go2Year(year) {
    cal.jumpTo(new Date(year, 2 ), true);
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
    container = materia.id + "|" + number;
    event.dataTransfer.setData("text", container);
}

function removeElementFromCalendar(id) {
    id = id.toString().trim();
    element = $('#target #' + id);
    if (element.attr("class") == "materia") {
        subjects.splice(_.indexOf(subjects, id), 1);
    }
    else
        plans.splice(_.indexOf(plans, id), 1);
    getInfoFromServer();
    element.fadeOut(fadeTime, function () {
        element.remove();
    });
}

function dropElement(target, event) {
    event.preventDefault();
    container = event.dataTransfer.getData("text");
    arreglo = container.split("|");
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
    element = document.getElementById(materia).cloneNode(true);
    element.appendChild(a);
    if (target.id == "target" && $('#target #' + materia + '').length == 0) {
        target.appendChild(element);
        div_target = target.id;
        if (source == 1)
            subjects.push(materia);
        else
            plans.push(materia);
    }
    getInfoFromServer();
}

function getInfoFromServer() {
    console.log("Getting information from server....");
    showSpinner();
    var jsonStringsubjects = JSON.stringify(subjects);
    var jsonStringPlans = JSON.stringify(plans);
    var jsonStringYears = JSON.stringify(years);
    $.ajax({
        type: "POST",
        url: 'GetExams',
        data: {
            materias: jsonStringsubjects,
            planes: jsonStringPlans,
            anios: jsonStringYears,
            cuat: cuat,
            currentYear:currentYear
         },
        cache: false,
        success: function (respuesta) {
            hideSpinner();
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
    if (subjects.length > 0 || plans.length > 0) {
        getInfoFromServer(); 
}
}

function showSpinner() {
    $(".circle").show();
    $(".circle1").show();
}

function hideSpinner() {
   $(".circle").hide();
   $(".circle1").hide();
}

$( "#yearList" ).change(function() {
    currentYear = $('#yearList').find(":selected").text();
    cuat = 1;
    refreshData();
    go2Year(currentYear);
  });



