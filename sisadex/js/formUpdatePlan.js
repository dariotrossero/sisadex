
var arr = [];
function procesarMaterias () {
//itero sobre todos los cuatrimestres

for (var i = 1; i <11; i++) {

              materiasDelCuat=document.getElementById('c'+i).getElementsByClassName("materia");

              //noinspection JSUndeclaredVariable
    a=getMateriasCuat(materiasDelCuat); //obtengo arreglo de codigos de las materias del cuat
             arr.push(a);


            }


var field = document.getElementById('formularioMaterias');
field.value =JSON.stringify(arr);


}

function getMateriaCode (html) {
         var numberPattern = /^\d+/g;

         return html.match(numberPattern);
}

function getMateriasCuat (nodeList) {
        var output=[];
        for (var i = 0; i < nodeList.length; i++) {

          output[i]=getMateriaCode(nodeList[i].innerHTML);

        };
         return output;
}

