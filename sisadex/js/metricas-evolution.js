var Subjects =  new Array();
var plans =  new Array();
var infoExams={};
var data=[];
var fadeTime=400;
var years = [];
var cuats = [];
 var chartData = [];

function loadData () {
 
  chart.dataProvider = generateChartData();
  
  chart.validateData();
}

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

        for(key in infoExams) {
          k=key;    
        }

        fechas=infoExams[k];

          for (fecha in fechas) {
             obj={};
             date=date=parseDate(fecha);
              obj.date=date;
              
             for (key in infoExams) {
                
                obj[key]=infoExams[key][fecha];
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
  data: {materias:jsonStringSubjects, planes:jsonStringPlans}, 
  cache: false,

  success: function(respuesta){

   infoExams=respuesta.result;
   if (!(infoExams instanceof Array)) {
    loadData();
   chart.zoom(0,304);
 }

   
 }
});   
}
function refreshInfoFromServer() {

 var jsonStringSubjects = JSON.stringify(Subjects);
 var jsonStringPlans = JSON.stringify(plans);
 var jsonStringYears = JSON.stringify(years);
 var jsonStringCuats = JSON.stringify(cuats);

 $.ajax({
  type: "POST",
  url: 'RefreshExamsEvolution',
  data: {materias:jsonStringSubjects, planes:jsonStringPlans, anios:jsonStringYears, cuatrimestres:jsonStringCuats}, 
  cache: false,

  success: function(respuesta){

   infoExams=respuesta.result;
    if (!(infoExams instanceof Array)) {
   loadData();
   chart.zoom(0,304);
}
   
 }
});   
}


$( document ).ready(function() {
  getInfoFromServer();
});
        
 
function getComplementButton(n) {
  switch(n)
{
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


/*
Cuando comienza la peticion ajax muestro la animacion de carga
*/


function go2FirstCuat() {
chart.zoom(0,140);
}

function go2SecondCuat() {
chart.zoom(150,304);
}

function clickYear(year) {
    firstCuat = (year*2).toString();
    secondCuat = ((year*2)-1).toString();
    
    if ($('#anio_'+year.toString()).hasClass('active'))
    {
      index = years.indexOf(year);
      if (index > -1) years.splice(index, 1);
  
      removeCuat(year*2,true);
      removeCuat((year*2)-1,true);
    }
    else 
    {
    years.push(year);
    addCuat(1);
    addCuat(2);
    
    $('#btn_'+firstCuat).addClass('active');
    $('#btn_'+secondCuat).addClass('active');
    }
    refreshData();
}

function removeYear(year) {
   $('#anio_'+year.toString()).removeClass('active');
   index = years.indexOf(year);
      if (index > -1) years.splice(index, 1);
}

function addYear(year) {
   $('#anio_'+year.toString()).addClass('active');
   index = years.indexOf(year);
      if (index == -1) years.push(year);
}
function removeCuat(cuat, fromYear) {
  
  
  if (fromYear) $('#btn_'+cuat.toString()).removeClass('active');
   if (cuat%2==0) 
       index = cuats.indexOf(2);
   else 
      index = cuats.indexOf(1);
        
   cuats.splice(index, 1); 
   
}
function addCuat(cuat) {
  
  if (cuat%2==0) cuats.push(2);
      else  cuats.push(1);
}

function clickCuat(button) {

  btn = '#btn_'+button.toString();
  btn_complement = '#btn_'+getComplementButton(button).toString();
  year=getYear(button);
  
    if ($(btn).hasClass('active')  && (!$(btn_complement).hasClass('active')) 
     ) {

      anio= getYear(button);
       if ($('#anio_'+anio.toString()).hasClass('active'))
      removeYear(anio);
    
    } 
    
      if (!$(btn).hasClass('active')){
      addCuat(button);

      addYear(year);
      }
   
    if ($(btn).hasClass('active'))
      removeCuat(button);
  refreshData();
}

function getYear(n) {

   switch(n)
{
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
function refreshData() {
 
 refreshInfoFromServer();
 
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

