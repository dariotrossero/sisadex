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
        $this->createDaysArray($currentYear);
        $materias = json_decode(stripslashes($_POST['materias']));
        $planes = json_decode(stripslashes($_POST['planes']));
        //Por cada id de plan se obtienen todas las materias del mismo
        $criteriaPlanes = new CDbCriteria;
        $criteriaPlanes->select = 't.materia_id';
        $criteriaPlanes->join = "INNER JOIN plan ON(t.Plan_id=plan.id)";
        $criteriaPlanes->addInCondition('t.plan_id', $planes);
        $criteriaPlanes->addInCondition('t.anio', $anios);
        $materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);
        $matPlan = array();
        foreach ($materiasPlan as $value) {
            array_push($matPlan, $value->Materia_id);
        }
        foreach ($matPlan as $value) {
            if (!in_array($value, $materias, true)) {
                array_push($materias, $value);
            }
        }
        //Obtengo los examenes de las materias dadas
        $criteriaMaterias = new CDbCriteria;
        $criteriaMaterias->select = 't.*';
        $criteriaMaterias->join = "INNER JOIN Tipo_Examen as tipoexamen ON(tipoexamen.id=t.tipoexamen_id)";
        $criteriaMaterias->addInCondition('t.materia_id', $materias);
        $criteriaMaterias->order = 't.fechaExamen ASC';
        $criteriaMaterias->addBetweenCondition('t.fechaExamen', $currentYear . '-03-01', $currentYear . '-12-31');
        $examenes = Examen::model()->findAll($criteriaMaterias);
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
        $details = $this->actionGetExamsDetails($materias, $planes, $anios);
        echo CJSON::encode(array(
            'result1' => $datos,
            'result2' => $details,
            'result3' => $datosNormalDate
            ));
    }

    /**
     * Obtiene fecha, materia y tipo de examen para mostrar cuando se clickea en un dia
     */
    public function actionGetExamsDetails($materias, $planes, $anios)
    {
        $criteriaPlanes = new CDbCriteria;
        $criteriaPlanes->select = 't.materia_id';
        $criteriaPlanes->join = "INNER JOIN plan ON(t.Plan_id=plan.id)";
        $criteriaPlanes->addInCondition('t.plan_id', $planes);
        $criteriaPlanes->addInCondition('t.anio', $anios);
        $materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);
        $matPlan = array();
        foreach ($materiasPlan as $value) {
            array_push($matPlan, $value->Materia_id);
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
        header("Content-type: application/json");
        //Envio la informacion en formato jSON
        //2 arreglos, result1 con los complejidades en cada dia y result2 con info de cada examen (fecha, materia y tipo de examen)
        $details = $this->actionGetExamsDetailsTimeline($materias, $planes, $currentYear);
        echo CJSON::encode(array(
            'result' => $details
            ));
    }

    public function actionGetExamsDetailsTimeline($materias, $planes,$currentYear)
    {
        $anios = array_values(json_decode(stripslashes($_POST['anios'])));
        
        $criteriaPlanes = new CDbCriteria;
        $criteriaPlanes->select = 't.materia_id';
        $criteriaPlanes->join = "INNER JOIN plan ON(t.Plan_id=plan.id)";
        $criteriaPlanes->addInCondition('t.plan_id', $planes);
        $criteriaPlanes->addInCondition('t.anio', $anios);
        
        $materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);
        $matPlan = array();
        foreach ($materiasPlan as $value) {
            array_push($matPlan, $value->Materia_id);
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
    {
        $currentYear = $_POST['currentYear'];
        $this->createDaysArray($currentYear);
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
            $materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);
            $materias = array();
            $matPlan = array();
            foreach ($materiasPlan as $value) {
                array_push($matPlan, $value->Materia_id);
            }
            foreach ($matPlan as $value) {
                if (!in_array($value, $materias, true)) {
                    array_push($materias, $value);
                }
            }
            //Obtengo los examenes de las materias dadas
            $examenes = $this->getExams($materias);
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


    private function getExams($materias) {
    		$criteriaMaterias = new CDbCriteria;
            $criteriaMaterias->select = 't.*';
            $criteriaMaterias->join = "INNER JOIN Tipo_Examen as tipoexamen ON(tipoexamen.id=t.tipoexamen_id)";
            $criteriaMaterias->addInCondition('t.materia_id', $materias);
            $criteriaMaterias->order = 't.fechaExamen ASC';
            return Examen::model()->findAll($criteriaMaterias);
    }

     public function actionRefreshExamsEvolution()
    {
        $anios = array_values(json_decode(stripslashes($_POST['anios'])));
        $currentYear = $_POST['currentYear'];
        $this->createDaysArray($currentYear);
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
            $materiasPlan = MateriaPlan::model()->findAll($criteriaPlanes);
            $materias = array();
            $matPlan = array();
            foreach ($materiasPlan as $value) {
                array_push($matPlan, $value->Materia_id);
            }
            foreach ($matPlan as $value) {
                if (!in_array($value, $materias, true)) {
                    array_push($materias, $value);
                }
            }
            //Obtengo los examenes de las materias dadas
            $examenes = $this->getExams($materias);
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
}