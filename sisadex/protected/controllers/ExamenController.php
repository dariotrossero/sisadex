<?php

class ExamenController extends Controller
{
    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public $cantExamenes = 1;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl' // perform access control for CRUD operations
            );
    }

    public function accessRules()
    {
        return array(
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'create',
                    'update',
                    'delete',
                    'GeneratePdf',
                    'GenerateExcel',
                    'GetTipos',
                    'CheckExamenOnSameDay',
                    'index',
                    'view',
                    'DeleteAllMyRecords',
                    'CountRecords'
                    ),
                'users' => array(
                    '@'
                    )
                ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'deleteSelected',
                    'deleteAll'
                    ),
                'users' => array(
                    'admin'
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
     * @internal param int $id the ID of the model to be displayed
     */
    public function actionView()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = $_REQUEST["id"];
            $this->renderPartial('ajax_view', array(
                'model' => $this->loadModel($id)
                ));
        } else {
            $this->render('view', array(
                'model' => $this->loadModel($id)
                ));
        }
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     * @return array|\CActiveRecord|mixed|null
     */
    public function loadModel($id)
    {
        $model = Examen::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'La página solicitada no existe.');
        return $model;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $this->pageTitle = 'Examen - Nuevo';
        $model = new Examen;
        $modelos = array();
        $valid = true;
        for ($i = 1; $i <= 10; $i++)
            $modelos[$i] = new Examen;
        // Uncomment the following line if AJAX validation is needed
        //    $this->performAjaxValidation($model,"examen-create-form");
        if (isset($_POST['Examen'])) {
            $datos = $_POST['Examen'];
            if (Yii::app()->user->isAdmin())
                $materia_id = $datos[1]['materia_id'];
            else
                $materia_id = Yii::app()->user->name;
            $this->cantExamenes = $_POST['cantExamenes'];
            for ($i = 1; $i <= $this->cantExamenes; $i++) {
                //Si se selecciona un tipo de examen para que no falle la validacion se setea algo al atributo
                if ($datos[$i]["tipoexamen_id"] != -1)
                    // $datos[$i]['TipoExamenPersonalizado'] = "Ingrese tipo de examen";

                    //Si soy administrador obtendo el materia_id desde el form sino desde el usuario

                    //     $mat_id            = (Yii::app()->user->isadmin()) ? $datos[1]['materia_id'] : Yii::app()->user->name;
                    $datos[$i]['materia_id'] = $materia_id;
                $modelos[$i]->attributes = $datos[$i];
                if ($datos[$i]['tipoexamen_id'] == -1) {
                    //insertarlo en examen
                    $tipoexamen = new Tipoexamen;
                    $id_tipo = $tipoexamen->insertWithoutFail($materia_id, $datos[$i]['TipoExamenPersonalizado']);
                    $modelos[$i]->tipoexamen_id = $id_tipo;
                }
                if (!Yii::app()->user->isAdmin()) //si no es administrador se setea el id de la materia desde user->name
                $modelos[$i]->materia_id = Yii::app()->user->name;
            }
            for ($i = 1; $i <= $this->cantExamenes; $i++)
                $valid = $modelos[$i]->validate() && $valid;
            if ($valid) {
                for ($i = 1; $i <= $this->cantExamenes; $i++)
                    $modelos[$i]->save();
                $this->redirect(array(
                    'index'
                    ));
            }
        }
        $this->render('create', array(
            'model' => $model,
            'modelos' => $modelos
            ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate()
    {
        $this->pageTitle = 'Examen - Modificar';
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["Examen"]["id"];
        $model = $this->loadModel($id);
        $model->fechaExamen = Utils::dateToDMY($model->fechaExamen);
        // Uncomment the following line if AJAX validation is needed
        //$this->performAjaxValidation($model,"examen-update-form");
        if (isset($_POST['Examen'])) {
            //Si se selecciona un tipo de examen para que no falle la validacion se setea algo al atributo
            if ($_POST['Examen']['tipoexamen_id'] != -1)
                $_POST['Examen']['TipoExamenPersonalizado'] = "no_vacio :)";
$transaction = $model->dbConnection->beginTransaction();
try {
                //Si soy administrador obtendo el materia_id desde el form sino desde el usuario
    $mat_id = (Yii::app()->user->isadmin()) ? $_POST['Examen']['materia_id'] : Yii::app()->user->name;
    $model->attributes = $_POST['Examen'];
                //$model->fechaExamen=$this->dateToYMD($_POST['Examen']['fechaExamen']);
    if ($_POST['Examen']['tipoexamen_id'] == -1) {
                    //Se eligio un tipo nuevo, se inserta en la base de datos y luego se obtiene el id para
                    //insertarlo en examen
        $tipoexamen = new Tipoexamen;
        $tipoexamen->nombreTipoExamen = $_POST['Examen']['TipoExamenPersonalizado'];
        $tipoexamen->Materia_id = $mat_id;
        $tipoexamen->save();
        $lastInsert = Yii::app()->db->getLastInsertID();
        $model->tipoexamen_id = $lastInsert;
    }
    if ($model->save()) {
        $transaction->commit();
        $this->redirect(array(
            'index'
            ));
    }
} catch (Exception $e) {
    $transaction->rollBack();
    throw new CHttpException('Se produjo un error al intentar almacenar los datos. Contacte al administrador.');
}
}
$this->render('update', array(
    'model' => $model
    ));
}

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            $id = $_POST["id"];
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
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }

    public function actionDeleteAll()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Examen;
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

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();
        $model = new Examen('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Examen'])) {
            $model->attributes = $_GET['Examen'];
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');
            if (!empty($model->fechaExamen))
                $criteria->addCondition('fechaExamen = "' . $model->fechaExamen . '"');
            if (!empty($model->tipoexamen_id))
                $criteria->addCondition('tipoexamen_id = "' . $model->tipoexamen_id . '"');
            if (!empty($model->materia_id))
                $criteria->addCondition('materia_id = "' . $model->materia_id . '"');
            if (!empty($model->descripcionExamen))
                $criteria->addCondition('descripcionExamen = "' . $model->descripcionExamen . '"');
        }
        $session['Examen_records'] = Examen::model()->findAll($criteria);
        $this->render('index', array(
            'model' => $model
            ));
    }

    /**
     * Genera un archivo PDF
     */
    public function actionGenerateExcel()
    {
        $session = new CHttpSession;
        $session->open();
        if (isset($session['Examen_records'])) {
            $model = $session['Examen_records'];
        } else
        $model = Examen::model()->findAll();
        Yii::app()->request->sendFile(date('YmdHis') . '.xls', $this->renderPartial('excelReport', array(
            'model' => $model
            ), true));
    }

    /**
     * Genera un archivo PDF
     */
    public function actionGeneratePdf()
    {
        $session = new CHttpSession;
        $session->open();
        Yii::import('application.extensions.ajaxgii.bootstrap.*');
        require_once('tcpdf/tcpdf.php');
        require_once('tcpdf/config/lang/eng.php');
        if (isset($session['Examen_records'])) {
            $model = $session['Examen_records'];
        } else
        $model = Examen::model()->findAll();
        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
            ), true);
        //die($html);
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Examen Report');
        $pdf->SetSubject('Examen Report');
        //$pdf->SetKeywords('example, text, report');
        $pdf->SetHeaderData('', 0, "Report", '');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Reporte generado por " . Yii::app()->name, "");
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
        $pdf->Output("Examen_002.pdf", "I");
    }

    /**
     * Dada una materia devuelve los tipos de examen para esa materia en formato json
     */
    public function actionGetTipos($id)
    {
        $resp = Tipoexamen::model()->getTiposExamenes($id);
        header("Content-type: application/json");
        echo CJSON::encode($resp);
    }

    /**
     * Dada una materia y una fecha, chequea si existe otro examen del mismo plan el mismo dia
     */
    public function actionCheckExamenOnSameDay($fechaExamen, $materia_id)
    {
        $fechaExamen = Utils::DateToYMD($fechaExamen);
        $sql = 'select (1) from examen where fechaExamen=:fechaExamen
        and materia_id IN (select distinct materia.id from materia INNER JOIN materia_has_plan INNER JOIN
          (select plan_id as subPlanId ,anio as subAnio, cuatrimestre as subCuat  from materia_has_plan where materia_id=:materia_id)
          on materia.id=materia_id and anio=subAnio and cuatrimestre= subCuat and plan_id=subPlanId and materia_id!=:materia_id)';
$command = Yii::app()->db->createCommand($sql);
$command->bindValue('materia_id', $materia_id);
$command->bindValue('fechaExamen', $fechaExamen);
$lista = $command->queryScalar();
$resp = ($lista > 0) ? "true" : "false";
header("Content-type: application/json");
echo CJSON::encode($resp);
}

    /**
     * Elimina todos los registros de examen pertenecientes a una materia
     */
    public function actionDeleteAllMyRecords()
    {
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $materia = $_POST["mat"];
            $model = new Examen;
            if ($pass == $materia) {
                $model->deleteAll("materia_id == " . $materia);
                echo "true";
            } else
            echo "false";
            // we only allow deletion via POST request
        } else {
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }

    /**
     * Performs the AJAX validation.
     * @param $model
     * @param $form_id
     * @internal param \the $CModel model to be validated
     */
    protected function performAjaxValidation($model, $form_id)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === $form_id) {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
