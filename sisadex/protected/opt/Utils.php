<?php

/**
 * This class contains useful and utils methods used in different controllers
 */
class Utils {

 

 public $colores = ['#FF8000','#D7DF01','#01DF01','#DF0101','#088A85','#424242','#000000','#FE2E9A','#FE2E9A'];
 public $indice;

 function __construct() {
      $this->indice=0;
   }


   public function getRandomColor() { 
    $possibilities = array(1, 2, 3, 4, 5, 6, 7, 8, 9, "A", "B", "C", "D", "E", "F" );
    shuffle($possibilities);
    $color = "#";
    
    while (true) {


      for($i=1;$i<=6;$i++){
        $color .= $possibilities[rand(0,14)];
      }
      if (!in_array($color,$this->colores)) return $color;

    }
  } 



public function supportedBrowser()
{
          $b = new EWebBrowser();
         if ($b->browser =="Internet Explorer" && intval($b->version) < 9) return false;
         else return true;
}



public function getColor() {

  if ($this->indice < count($this->colores)  ) {

    $color= $this->colores[$this->indice];
    $this->indice++;
    return $color;
    
  }
  else 
    return $this->getRandomColor();

}


//Deprecated
 function getPercentage($val1, $percentage)
          {
          $new_width = ($percentage * $val1) / 100;
          $res       = floor($new_width);
          return intval($res);
          }



      /*
      Dada una fecha de examen, dias de preparacion y el peso del tipo de examen 
      Desde el dia inicial de preparacion (fechaExamen-diasPreparacion) hasta el dia del examen
      calcula el valor de peso de cada dia de acuerdo a la funcion matematica para calcular los pesos
      */
      function  CalculateWeight(&$arreglo,&$arregloNormalDate, $fechaExamen, $diasPreparacion, $factorCarga)
          {
          $inicio = strtotime('-' . $diasPreparacion . ' day', strtotime($fechaExamen));
          $inicio = date('Y-m-d', $inicio);
          $i      = 1;
          while (strtotime($inicio) < strtotime($fechaExamen))
              {
              $miliseconds = strtotime($inicio);
              //$miliseconds=strtotime($inicio)*1; Esto funciona en windows!!!
              $valor       = MathFunction::calculate($i, $factorCarga, $diasPreparacion);
              $i++;
              $arregloNormalDate[$inicio] = isset($arregloNormalDate[$inicio]) ? $arregloNormalDate[$inicio] + $valor : $valor;
              $arreglo[$miliseconds] = isset($arreglo[$miliseconds]) ? $arreglo[$miliseconds] + $valor : $valor;
              $inicio                = date("Y-m-d", strtotime("+1 day", strtotime($inicio)));
              }
          $miliseconds           = strtotime($inicio);
          //$miliseconds=strtotime($inicio)*1; Esto funciona en windows!!!
          $arreglo[$miliseconds] = isset($arreglo[$miliseconds]) ? $arreglo[$miliseconds] + $valor : $valor;
          $arregloNormalDate[$inicio] = isset($arregloNormalDate[$inicio]) ? $arregloNormalDate[$inicio] + $valor : $valor;
          }



 //Cambia el formato de la fecha de dd-mm-yyyy a yyyy-mm-dd
      public static function dateToYMD($date)
          {
          return date('Y-m-d', strtotime($date));
          }
      //Cambia el formato de la fecha de yyyy-mm-dd a dd-mm-yyyy 
      public static function dateToDMY($date)
          {
          return date('d-m-Y', strtotime($date));
          }


           public static function dateToDMYLong($date,$withDays)
          {

$arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
   'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
 
   $arrayDias = array( 'Domingo', 'Lunes', 'Martes',
       'Miercoles', 'Jueves', 'Viernes', 'Sabado');
     
    if ($withDays)
      return $arrayDias[date('w',strtotime($date))].", ".date('d',strtotime($date))." de ".$arrayMeses[date('m',strtotime($date))-1]." de ".date('Y',strtotime($date));
          else return date('d',strtotime($date))." de ".$arrayMeses[date('m',strtotime($date))-1]." de ".date('Y',strtotime($date));
          }






}