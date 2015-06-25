<?php

class MateriaPlanController extends Controller
{
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
                'allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array(
                    'index',
                    'create',
                    'update',
                    'delete',
                    'GeneratePdf',
                    'GenerateExcel'
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
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView()
    {
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

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new MateriaPlan;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "materiaplan-create-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['MateriaPlan'])) {
                $model->attributes = $_POST['MateriaPlan'];
                if ($model->save()) {
                    echo $model->Materia_id;
                } else {
                    echo "false";
                }
                return;
            }
        } else {
            if (isset($_POST['MateriaPlan'])) {
                $model->attributes = $_POST['MateriaPlan'];
                if ($model->save())
                    $this->redirect(array(
                        'view',
                        'id' => $model->Materia_id
                        ));
            }
            $this->render('create', array(
                'model' => $model
                ));
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate()
    {
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["MateriaPlan"]["Materia_id"];
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "materiaplan-update-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['MateriaPlan'])) {
                $model->attributes = $_POST['MateriaPlan'];
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
                return;
            }
            $this->renderPartial('_ajax_update_form', array(
                'model' => $model
                ));
            return;
        }
        if (isset($_POST['MateriaPlan'])) {
            $model->attributes = $_POST['MateriaPlan'];
            if ($model->save())
                $this->redirect(array(
                    'view',
                    'id' => $model->Materia_id
                    ));
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
        $model = new MateriaPlan('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['MateriaPlan'])) {
            $model->attributes = $_GET['MateriaPlan'];
            if (!empty($model->Materia_id))
                $criteria->addCondition('Materia_id = "' . $model->Materia_id . '"');
            if (!empty($model->Plan_id))
                $criteria->addCondition('Plan_id = "' . $model->Plan_id . '"');
            if (!empty($model->anio))
                $criteria->addCondition('anio = "' . $model->anio . '"');
            if (!empty($model->cuatrimestre))
                $criteria->addCondition('cuatrimestre = "' . $model->cuatrimestre . '"');
        }
        $session['MateriaPlan_records'] = MateriaPlan::model()->findAll($criteria);
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
        $model = MateriaPlan::model()->findByPk($id);
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
        if (isset($session['MateriaPlan_records'])) {
            $model = $session['MateriaPlan_records'];
        } else
        $model = MateriaPlan::model()->findAll();
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
        if (isset($session['MateriaPlan_records'])) {
            $model = $session['MateriaPlan_records'];
        } else
        $model = MateriaPlan::model()->findAll();
        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
            ), true);
        //die($html);
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('MateriaPlan Report');
        $pdf->SetSubject('MateriaPlan Report');
        //$pdf->SetKeywords('example, text, report');
        $pdf->SetHeaderData('', 0, "Report", '');
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, "Example Report by " . Yii::app()->name, "");
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
        $pdf->Output("MateriaPlan_002.pdf", "I");
    }
}
