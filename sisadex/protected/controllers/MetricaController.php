<?php

class MetricaController extends Controller
{
    var $fechas = array();


    public function actionCalendar()
    {
        $this->pageTitle = Yii::app()->name ." | Métricas - Calendario.";
        $this->render('calendar');
    }

    public function actionTimeLine()
    {
        $this->pageTitle = Yii::app()->name ." | Métricas - Linea de tiempo.";
        $this->render('timeline');
    }

    public function actionEvolution()
    {
        $this->pageTitle = Yii::app()->name ." | Métricas - Evolución.";
        $this->render('evolution');
    }



    function createDaysArray($year)
    {
        // Start date
        $date = $year . '-03-01';
        
        // End date
        $end_date = $year  . '-12-31';
        while (strtotime($date) <= strtotime($end_date)) {
            $this->fechas[$date] = 0;
            $date = date("Y-m-d", strtotime("+1 day", strtotime($date)));
        }
    }

    /**
     * Obtiene los datos de los examenes obtenidos de los arreglos de materias y planes
     */
    public function actionGetExams()
    {
        $anios = array_values(json_decode(stripslashes($_POST['anios'])));
        $utils = new Utils();
        $currentYear = $_POST['currentYear'];
        $cuat = $_POST['cuat'];
        $this->createDaysArray($currentYear);
        $materias = json_decode(stripslashes($_POST['materias']));
        $planes = json_decode(stripslashes($_POST['planes']));
        //Por cada id de plan se obtienen todas las materias del mismo
        $criteriaPlanes = new CDbCriteria;
        $criteriaPlanes->select = 't.materia_id';
        $criteriaPlanes->join = "INNER JOIN plan ON(t.Plan_id=plan.id)";
        $criteriaPlanes->addInCondition('t.plan_id', $planes);
        $criteriaPlanes->addInCondition('t.anio', $anios);
       // Con este criterio se traen examenes de todo el año y no del cuatrimestre que deberia traer.
       // Por ej. Analisis Matematico 1 esta en 2 planes en distinto cuatrimestre.
       // $materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);


        $sql = 'select t.Materia_id from Materia_has_Plan t JOIN Materia m WHERE t.Materia_id = m.id AND t.Plan_id  IN ('.implode(",", $planes).') AND t.anio IN ('.implode(",", $anios).') and t.cuatrimestre = '.$cuat;

        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $materiasPlan = $command->queryAll(); 

        $matPlan = array();
        foreach ($materiasPlan as $value) {
            array_push($matPlan, $value["Materia_id"]);
        }
        foreach ($matPlan as $value) {
            if (!in_array($value, $materias, true)) {
                array_push($materias, $value);
            }
        }
        //Obtengo los examenes de las materias dadas
        $criteriaExamenes = new CDbCriteria;
        $criteriaExamenes->select = 't.*';
        $criteriaExamenes->join = "INNER JOIN Tipo_Examen as tipoexamen ON(tipoexamen.id=t.tipoexamen_id)";
        $criteriaExamenes->addInCondition('t.materia_id', $materias);
        $criteriaExamenes->order = 't.fechaExamen ASC';
        if ($cuat==1):
            $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-03-01', $currentYear . '-07-31'); 
        else: 
            $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-08-01', $currentYear . '-12-31'); 
        endif;
        $examenes = Examen::model()->findAll($criteriaExamenes);
        //Arreglo donde se guardaran los datos
        $datos = array();
        $datosNormalDate = $this->fechas;
        //Informacion que se devuelve  a la vista:
        //fecha del examen, dias de preparacion y peso de cada dia
        foreach ($examenes as $arr) {
            $fecha = $arr->fechaExamen;
            $dias = $arr->diasPreparacion;
            $complejidad = $arr->tipoexamen->complejidad;
            $utils->CalculateWeight($datos, $datosNormalDate, $fecha, $dias, $complejidad);
        }
        header("Content-type: application/json");
        //Envio la informacion en formato jSON
        //2 arreglos, result1 con los complejidades en cada dia y result2 con info de cada examen (fecha, materia y tipo de examen)
        $details = $this->actionGetExamsDetails($materias, $planes, $anios, $cuat);
        echo CJSON::encode(array(
            'result1' => $datos,
            'result2' => $details,
            'result3' => $datosNormalDate
            ));
    }

    /**
     * Obtiene fecha, materia y tipo de examen para mostrar cuando se clickea en un dia
     */
    public function actionGetExamsDetails($materias, $planes, $anios, $cuat)
    {
        $currentYear = $_POST['currentYear'];
        $criteriaPlanes = new CDbCriteria;
        $criteriaPlanes->select = 't.materia_id';
        $criteriaPlanes->join = "INNER JOIN plan ON(t.Plan_id=plan.id)";
        $criteriaPlanes->addInCondition('t.plan_id', $planes);
        $criteriaPlanes->addInCondition('t.anio', $anios);
        // Con este criterio se traen examenes de todo el año y no del cuatrimestre que deberia traer.
       // Por ej. Analisis Matematico 1 esta en 2 planes en distinto cuatrimestre.
        //$materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);

        $sql = 'select t.Materia_id from Materia_has_Plan t JOIN Materia m WHERE t.Materia_id = m.id AND t.Plan_id  IN ('.implode(",", $planes).') AND t.anio IN ('.implode(",", $anios).') and t.cuatrimestre = '.$cuat;

        $connection = Yii::app()->db;
        $command = $connection->createCommand($sql);
        $materiasPlan = $command->queryAll(); 

        $matPlan = array();
        foreach ($materiasPlan as $value) {
            array_push($matPlan, $value["Materia_id"]);
        }
        foreach ($matPlan as $value) {
            if (!in_array($value, $materias, true)) {
                array_push($materias, $value);
            }
        }
        $criteriaExamenes = new CDbCriteria;
        $criteriaExamenes->join = "INNER JOIN materia  ON(t.materia_id=materia.id)";
        $criteriaExamenes->join = "INNER JOIN Tipo_Examen ON(t.tipoexamen_id=Tipo_Examen.id)";
        $criteriaExamenes->addInCondition('t.materia_id', $materias);
         if ($cuat==1):
            $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-03-01', $currentYear . '-07-31'); 
        else: 
            $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-08-01', $currentYear . '-12-31'); 
        endif;
        $infoExamenes = Examen::model()->findAll($criteriaExamenes);
        $details = array();
        foreach ($infoExamenes as $row) {
            $index = strtotime($row->fechaExamen);
            if (!isset($details[$index])) {
                $details[$index] = array(
                    array(
                        'tipo' => $row->tipoexamen->nombreTipoExamen,
                        'materia' => $row->materia->nombreMateria
                        )
                    );
            } else {
                array_push($details[$index], array(
                    'tipo' => $row->tipoexamen->nombreTipoExamen,
                    'materia' => $row->materia->nombreMateria
                    ));
            }
        }
        return $details;
    }

    public function actionGetExamsTimeline()
    {
        $materias = json_decode(stripslashes($_POST['materias']));
        $planes = json_decode(stripslashes($_POST['planes']));
        $currentYear = $_POST['currentYear'];
        $cuat = $_POST['cuat'];
        header("Content-type: application/json");
        //Envio la informacion en formato jSON
        //2 arreglos, result1 con los complejidades en cada dia y result2 con info de cada examen (fecha, materia y tipo de examen)
        $details = $this->actionGetExamsDetailsTimeline($materias, $planes, $currentYear, $cuat);
        echo CJSON::encode(array(
            'result' => $details
            ));
    }

    public function actionGetExamsDetailsTimeline($materias, $planes,$currentYear, $cuat)
    {
        $anios = array_values(json_decode(stripslashes($_POST['anios'])));
        
        $criteriaPlanes = new CDbCriteria;
        $criteriaPlanes->select = 't.materia_id';
        $criteriaPlanes->join = "INNER JOIN plan ON(t.Plan_id=plan.id)";
        $criteriaPlanes->addInCondition('t.plan_id', $planes);
        $criteriaPlanes->addInCondition('t.anio', $anios);
        // Con este criterio se traen examenes de todo el año y no del cuatrimestre que deberia traer.
       // Por ej. Analisis Matematico 1 esta en 2 planes en distinto cuatrimestre.
        //$materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);


         $sql = 'select t.Materia_id from Materia_has_Plan t JOIN Materia m WHERE t.Materia_id = m.id AND t.Plan_id  IN ('.implode(",", $planes).') AND t.anio IN ('.implode(",", $anios).') and t.cuatrimestre = '.$cuat;

            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $materiasPlan = $command->queryAll(); 

        $matPlan = array();
        foreach ($materiasPlan as $value) {
            array_push($matPlan, $value["Materia_id"]);
        }
        foreach ($matPlan as $value) {
            if (!in_array($value, $materias, true)) {
                array_push($materias, $value);
            }
        }
        $criteriaExamenes = new CDbCriteria;
        $criteriaExamenes->join = "INNER JOIN materia  ON(t.materia_id=materia.id)";
        $criteriaExamenes->join = "INNER JOIN Tipo_Examen ON(t.tipoexamen_id=Tipo_Examen.id)";
        $criteriaExamenes->addBetweenCondition('fechaExamen', $currentYear . '-03-01', $currentYear . '-12-31');
        $criteriaExamenes->addInCondition('t.materia_id', $materias);
        if ($cuat==1):
            $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-03-01', $currentYear . '-07-31'); 
        else: 
            $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-08-01', $currentYear . '-12-31'); 
        endif;
        $infoExamenes = Examen::model()->findAll($criteriaExamenes);
        $details = array();
        foreach ($infoExamenes as $row) {
            $date = DateTime::createFromFormat("Y-m-d", $row->fechaExamen);
            array_push($details, array(
                array(
                    'dia' => $date->format("d"),
                    'mes' => $date->format("m"),
                    'anio' => $date->format("Y"),
                    'content' => $row->materia->nombreMateria,
                    'classname' => $this->getColorClass($row->tipoexamen->complejidad)
                    )
                ));
        }
        return $details;
    }

    public function getColorClass($complejidad)
    {
        return "color".$complejidad;
    }

    public function actionGetExamsEvolution()
    {   $anios = array(1);
        $currentYear = $_POST['currentYear'];
        $this->createDaysArray($currentYear);
        $utils = new Utils();
        $cuat = 1;
        $planes = Plan::model()->findAll(array(
            'order' => 'anioPlan'
            ));
        $resultados = array();
        foreach ($planes as $key) {
            //Por cada id de plan se obtienen todas las materias del mismo
            $criteriaPlanes = new CDbCriteria;
            $criteriaPlanes->select = 't.materia_id';
            $criteriaPlanes->condition = "plan_id == " . $key->id;
            // Con este criterio se traen examenes de todo el año y no del cuatrimestre que deberia traer.
            // Por ej. Analisis Matematico 1 esta en 2 planes en distinto cuatrimestre.
            //$materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);
            

            $sql = 'select t.Materia_id from Materia_has_Plan t JOIN Materia m WHERE t.Materia_id = m.id AND t.Plan_id = '.$key->id.' AND t.anio IN ('.implode(",", $anios).') and t.cuatrimestre = '.$cuat;

            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $materiasPlan = $command->queryAll(); 



            $materias = array();
            $matPlan = array();
            foreach ($materiasPlan as $value) {
                array_push($matPlan, $value["Materia_id"]);
            }
            foreach ($matPlan as $value) {
                if (!in_array($value, $materias, true)) {
                    array_push($materias, $value);
                }
            }
            //Obtengo los examenes de las materias dadas
            $examenes = $this->getExams($materias, $cuat, $currentYear);
            //Arreglo donde se guardaran los datos
            $datos = array();
            $datosNormalDate = $this->fechas;
            //Informacion que se devuelve  a la vista:
            // fecha del examen, dias de preparacion y peso de cada dia
            foreach ($examenes as $arr) {
                $fecha = $arr->fechaExamen;
                $dias = $arr->diasPreparacion;
                $complejidad = $arr->tipoexamen->complejidad;
                $utils->CalculateWeight($datos, $datosNormalDate, $fecha, $dias, $complejidad);
            }
            $resultados[$key->id] = $datosNormalDate;
        }
        header("Content-type: application/json");
        //Envio la informacion en formato jSON
        echo CJSON::encode(array(
            'result' => $resultados
            ));
    }


 public function actionRefreshExamsEvolution()
    {
        $anios = array_values(json_decode(stripslashes($_POST['anios'])));
        $currentYear = $_POST['currentYear'];
        $this->createDaysArray($currentYear);
        $cuat = $_POST['cuat'];
        $utils = new Utils();
        $planes = Plan::model()->findAll(array(
            'order' => 'anioPlan'
            ));
        $resultados = array();
        foreach ($planes as $key) {
            //Por cada id de plan se obtienen todas las materias del mismo
            $criteriaPlanes = new CDbCriteria;
            $criteriaPlanes->select = 't.materia_id';
            $criteriaPlanes->condition = "plan_id == " . $key->id;
            $criteriaPlanes->addInCondition('t.anio', $anios);
            //$materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);


            $sql = 'select t.Materia_id from Materia_has_Plan t JOIN Materia m WHERE t.Materia_id = m.id AND t.Plan_id = '.$key->id.' AND t.anio IN ('.implode(",", $anios).') and t.cuatrimestre = '.$cuat;

            $connection = Yii::app()->db;
            $command = $connection->createCommand($sql);
            $materiasPlan = $command->queryAll(); 

            $materias = array();
            $matPlan = array();
            foreach ($materiasPlan as $value) {
                array_push($matPlan, $value["Materia_id"]);
            }
            foreach ($matPlan as $value) {
                if (!in_array($value, $materias, true)) {
                    array_push($materias, $value);
                }
            }
            //Obtengo los examenes de las materias dadas
            $examenes = $this->getExams($materias, $cuat, $currentYear);
            //Arreglo donde se guardaran los datos
            $datos = array();
            $datosNormalDate = $this->fechas;
            //Informacion que se devuelve  a la vista:
            // fecha del examen, dias de preparacion y peso de cada dia
            foreach ($examenes as $arr) {
                $fecha = $arr->fechaExamen;
                $dias = $arr->diasPreparacion;
                $complejidad = $arr->tipoexamen->complejidad;
                $utils->CalculateWeight($datos, $datosNormalDate, $fecha, $dias, $complejidad);
            }
            $resultados[$key->id] = $datosNormalDate;
        }
        header("Content-type: application/json");
        //Envio la informacion en formato jSON
        echo CJSON::encode(array(
            'result' => $resultados
            ));
    }


    private function getExams($materias, $cuat, $currentYear) {
    		$criteriaExamenes = new CDbCriteria;
            $criteriaExamenes->select = 't.*';
            $criteriaExamenes->join = "INNER JOIN Tipo_Examen as tipoexamen ON(tipoexamen.id=t.tipoexamen_id)";
            $criteriaExamenes->addInCondition('t.materia_id', $materias);
             if ($cuat==1):
                $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-03-01', $currentYear . '-07-31'); 
            else: 
                $criteriaExamenes->addBetweenCondition('t.fechaExamen', $currentYear . '-08-01', $currentYear . '-12-31'); 
            endif;
            $criteriaExamenes->order = 't.fechaExamen ASC';
            return Examen::model()->findAll($criteriaExamenes);
    }

    
}