
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
    var datePart = input.match(/\d+/g),
        year = datePart[0].substring(2),
        month = datePart[1], day = datePart[2];
    return day+'/'+month+'/'+year;
}
