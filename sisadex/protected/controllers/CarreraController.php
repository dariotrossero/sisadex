<?php

class CarreraController extends Controller
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
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'create',
                    'update',
                    'GeneratePdf',
                    'GenerateExcel',
                    'delete',
                    'index',
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     */
    public function actionCreate()
    {
        $model = new Carrera;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "carrera-create-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Carrera'])) {
                $model->attributes = $_POST['Carrera'];
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
                return;
            }
        } else {
            throw new CHttpException(404, 'La página solicitada no existe.');
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["Carrera"]["id"];
            $model = $this->loadModel($id);
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model, "carrera-update-form");
           if (isset($_POST['Carrera'])) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    if (Carrera::model()->existsInDatabase($_POST['Carrera']['nombreCarrera'])){
                        echo "exists";
                        return;
                    }        
                $model->attributes = $_POST['Carrera'];
                    if ($model->save()) {
                        $transaction->commit();
                        echo "true";
                    } 
                    else {
                        echo "false";
                    }
                    return;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new CHttpException('Se produjo un error al intentar almacenar los datos. Contacte al administrador.');
                }
            } 
            $this->renderPartial('_ajax_update_form', array(
                'model' => $model
                ));
            return;
        } 
        else {
            throw new CHttpException(404, 'La página solicitada no existe.');
        }
    }


    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
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

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $session = new CHttpSession;
        $session->open();
        $criteria = new CDbCriteria();
        $model = new Carrera('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Carrera'])) {
            $model->attributes = $_GET['Carrera'];
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');
            if (!empty($model->nombreCarrera))
                $criteria->addCondition('nombreCarrera = "' . $model->nombreCarrera . '"');
        }
        $session['Carrera_records'] = Carrera::model()->findAll($criteria);
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
        $model = Carrera::model()->findByPk($id);
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
        if (isset($session['Carrera_records'])) {
            $model = $session['Carrera_records'];
        } else
        $model = Carrera::model()->findAll();
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
        if (isset($session['Carrera_records'])) {
            $model = $session['Carrera_records'];
        } else
        $model = Carrera::model()->findAll();
        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
            ), true);
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Carrera Report');
        $pdf->SetSubject('Carrera Report');
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
        $pdf->Output("Carrera_002.pdf", "I");
    }

    /**
     * Elimina todos los registros de carrera
     */
    public function actionDeleteAll()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Carrera;
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
