<?php

class PlanController extends Controller
{
    public $onloadFunction = 'fillMateriasOnTable();';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl' // perform access control for CRUD operations
            );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array(
                'allow',
                'actions' => array(
                    'create',
                    'update',
                    'GeneratePdf',
                    'GenerateExcel',
                    'view',
                    'index',
                    'delete',
                    'TestExistsPlan',
                    'deleteAll'
                    ),
                'users' => array(
                    'admin'
                    )
                ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'GeneratePdf',
                    'GenerateExcel',
                    'index',
                    'view'
                    ),
                'users' => array(
                    '@'
                    )
                ),
            array(
                'deny', // deny all users
                'users' => array(
                    '*'
                    )
                )
            );
}

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
        $this->pageTitle = 'Plan - Detalles';
        $id = $_REQUEST["id"];
        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('ajax_view', array(
                'model' => $this->loadModel($id)
                ));
        } else {
            $this->render('view', array(
                'model' => $this->loadModel($id)
                ));
        }
    }

    public function actionCreate()
    {
        $this->pageTitle = 'Plan - Nuevo';
        $model = new Plan;
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Plan'])) {
            $id = $_POST['Plan']['anioPlan'] . $_POST['Plan']['Carrera_id'];
            $model->attributes = $_POST['Plan'];
            $model->id = $id;
            $resultado = $_POST['result'];
            $materias = $this->parseString($resultado);
            if ($model->save()) {
                $this->agregarMaterias($materias, $id);
                $this->redirect(array(
                    'view',
                    'id' => $model->id
                    ));
            }
        }
        $this->render('create', array(
            'model' => $model
            ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        $id = $_POST["id"];
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();
            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset(Yii::app()->request->isAjaxRequest))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array(
                    'index'
                    ));
            else
                echo "true";
        } else {
            if (!isset($_GET['ajax']))
                throw new CHttpException(400, 'Solicitud de página inválida.');
            else
                echo "false";
        }
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();
        $model = new Plan('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Plan'])) {
            $model->attributes = $_GET['Plan'];
            if (!empty($model->anioPlan))
                $criteria->addCondition('anioPlan = "' . $model->anioPlan . '"');
            if (!empty($model->Carrera_id))
                $criteria->addCondition('Carrera_id = "' . $model->Carrera_id . '"');
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');
        }
        $session['Plan_records'] = Plan::model()->findAll($criteria);
        $this->render('index', array(
            'model' => $model
            ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Plan::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model, $form_id)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form_id) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGenerateExcel()
    {
        $session = new CHttpSession;
        $session->open();
        if (isset($session['Plan_records'])) {
            $model = $session['Plan_records'];
        } else
        $model = Plan::model()->findAll();
        Yii::app()->request->sendFile(date('YmdHis') . '.xls', $this->renderPartial('excelReport', array(
            'model' => $model
            ), true));
    }

    public function actionGeneratePdf()
    {
        $session = new CHttpSession;
        $session->open();
        Yii::import('application.extensions.ajaxgii.bootstrap.*');
        require_once('tcpdf/tcpdf.php');
        require_once('tcpdf/config/lang/eng.php');
        if (isset($session['Plan_records'])) {
            $model = $session['Plan_records'];
        } else
        $model = Plan::model()->findAll();
        $criteria = new CDbCriteria();
        $criteria->order = 'anioPlan';
        $model = Plan::model()->findAll($criteria);
        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
            ), true);
        //die($html);
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Plan Report');
        $pdf->SetSubject('Plan Report');
        //$pdf->SetKeywords('example, text, report');
        $pdf->SetHeaderData('', 0, "Report", '');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, 10, "Reporte generado por " . Yii::app()->name, "");
        $pdf->setHeaderFont(Array(
            'helvetica',
            '',
            8
            ));
        $pdf->setFooterFont(Array(
            'helvetica',
            '',
            6
            ));
        $pdf->SetMargins(15, 18, 15);
        $pdf->SetHeaderMargin(5);
        $pdf->SetFooterMargin(10);
        $pdf->SetAutoPageBreak(TRUE, 0);
        $pdf->SetFont('dejavusans', '', 7);
        $pdf->AddPage();
        $pdf->writeHTML($html, true, false, true, false, '');
        $pdf->LastPage();
        $pdf->Output("Plan_002.pdf", "I");
    }

    protected function agregarMaterias($materiasArray, $planID)
    {
        /* Recibo un arreglo de 10 componentes. Cada uno con un arreglo asociativo con las materias de ese cuatrimestre
        [0]=> Codigo de materia
        [1]=> Codigo de materia
        */
        for ($i = 0; $i < count($materiasArray); $i++) {
            $cuat = $this->getCuatrimestre($i);
            $anio = $this->getYear($i);
            $materias = $materiasArray[$i];
            foreach ($materias as $materiaID) {
                if (strlen($materiaID) > 0) {
                    $modeloMateriaPlan = new MateriaPlan;
                    $modeloMateriaPlan->Plan_id = $planID;
                    $modeloMateriaPlan->Materia_id = $materiaID;
                    $modeloMateriaPlan->anio = $anio;
                    $modeloMateriaPlan->cuatrimestre = $cuat;
                    $modeloMateriaPlan->save();
                }
            }
        }
    }

    function getYear($number)
    {
        return floor($number / 2) + 1;
    }

    function getCuatrimestre($number)
    {
        if ($number % 2 == 0)
            return 1;
        else
            return 2;
    }

    protected function gridDataColumn($planID)
    {
        $post = MateriaPlan::model()->findAll('Plan_id=:id', array(
            ':id' => $planID
            ));
        echo "\n";
        echo "<ul>";
        foreach ($post as $p) {
            echo "<li>" . $p->Materia_id . "</li>";
            echo "<li>" . $p->anio . "</li>";
            echo "<li>" . $p->cuatrimestre . "</li>";
        }
        echo "</ul>";
    }

    public function getTableId($anio, $cuat)
    {
        $t = $anio * 2;
        if ($cuat % 2 == 0)
            return $t;
        else
            return $t - 1;
    }

    protected function parseString($str)
    {
        $str = trim($str);
        $arr = substr($str, 1, strlen($str) - 2);
        $arr = str_replace('["', '', $arr);
        $arr = str_replace('"]', '', $arr);
        $arreglo = explode("],[", $arr);
        $arreglo[0] = $arr = str_replace('[', '', $arreglo[0]);
        $arreglo[count($arreglo) - 1] = $arr = str_replace(']', '', $arreglo[count($arreglo) - 1]);
        $result = array();
        for ($i = 0; $i < count($arreglo); $i++) {
            array_push($result, explode(",", $arreglo[$i]));
        }
        return $result;
    }

    public function actionUpdate($id)
    {
        $this->pageTitle = 'Plan - Modificar';
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);
        if (isset($_POST['Plan'])) {
            MateriaPlan::model()->deleteAll('Plan_id =' . $id);
            $id = $_POST['Plan']['anioPlan'] . $_POST['Plan']['Carrera_id'];
            $model->attributes = $_POST['Plan'];
            $model->id = $id;
            $resultado = $_POST['result'];
            $materias = $this->parseString($resultado);
            $this->agregarMaterias($materias, $id);
            $this->redirect(array(
                'view',
                'id' => $model->id
                ));
        }
        $this->render('update', array(
            'model' => $model
            ));
    }

    /**
     * Dado un año y una carrera chequea si existe otro registro en la base de datos
     */
    public function actionTestExistsPlan($anioPlan, $Carrera_id)
    {
        $record = Plan::model()->find(array(
            'select' => 'id',
            'condition' => 'anioPlan=:anioPlan AND Carrera_id=:Carrera_id',
            'params' => array(
                ':anioPlan' => $anioPlan,
                ':Carrera_id' => $Carrera_id
                )
            ));
        $resp = ($record === null) ? "false" : "true";
        header("Content-type: application/json");
        echo CJSON::encode($resp);
    }

    /**
     * Elimina todos los registros de plan
     */
    public function actionDeleteAll()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Plan;
            if ($passModel->isPasswordCorrect($pass)) {
                $model->deleteAll();
                echo "true";
            } else
            echo "false";
            // we only allow deletion via POST request
        } else {
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }
}
