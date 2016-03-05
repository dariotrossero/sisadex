
function showModal() {
    string = "<h4><ul>";
    for (var key in exams) {
        var obj = exams[key];
        string = string + "<li>" + obj.nombreMateria + "</br><h5>"+ obj.nombreTipoExamen +"</h5></li></br>";
    }
    string = string + "</ul><h4>";
    $.modal(string);
};

function convertDate (input) {
    var meses = new Array ("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
    var diasSemana = new Array("Domingo","Lunes","Martes","Miércoles","Jueves","Viernes","Sábado");
    var datePart = input.match(/\d+/g);
    
    year = datePart[0],
    month = datePart[1], day = datePart[2];

     f=new Date(year+"-"+month+"-"+day);
    return diasSemana[f.getDay()+1] + ", " + (f.getDate()+1) + " de " + meses[f.getMonth()] + " de " + f.getFullYear();
}
