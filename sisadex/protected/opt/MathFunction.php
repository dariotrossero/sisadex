<?php
 class MathFunction {


 public static function calculate($i, $factorCarga, $diasPreparacion)
          {
          $value = round(log($i + 2, 2) * $factorCarga / $diasPreparacion * 3);
          if ($value <= $factorCarga)
              return $value;
          else
              return round($factorCarga);
          }


      }