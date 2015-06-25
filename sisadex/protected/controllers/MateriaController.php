<?php

class MateriaController extends Controller
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
        $model = new Materia;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "materia-create-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Materia'])) {
                //Comienza la transaccion
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    //Chequeo si no existe un registro con el mismo id
                    $usuario = Users::model()->findByPk($_POST['Materia']['id']);
                    $record = $model->find(array(
                        'select' => 'id',
                        'condition' => 'id=:id',
                        'params' => array(
                            ':id' => $_POST['Materia']['id']
                        )
                    ));
                    if ($record === null) {
                        if ($usuario == null) {
                            /*Agrego un usuario y contraseña con el mismo numero de materia */
                            $usuario = new Users;
                            $usuario->id = ltrim($_POST['Materia']['id'], "0, ");
                            $usuario->password = ltrim($_POST['Materia']['id'], "0 ");
                            $usuario->role = 0;
                            $usuario->save();
                        }
                        $model->attributes = $_POST['Materia'];
                        if ($model->save()) {
                            $transaction->commit();
                        }
                    } else {
                        throw new CHttpException('Ya existe ese registro en el sistema');
                    }
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new CHttpException('Se produjo un error al intentar almacenar los datos. Contacte al administrador.');
                }
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
            $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["Materia"]["id"];
            $model = $this->loadModel($id);
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model, "materia-update-form");
            if (isset($_POST['Materia'])) {
                $transaction = $model->dbConnection->beginTransaction();
                try {

                     if (Materia::model()->existsInDatabase($_POST['Materia']['nombreMateria'])){
                        echo "exists";
                        return;
                    }        
                $model->attributes = $_POST['Materia'];
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
            $transaction = Materia::model()->dbConnection->beginTransaction();
            try {
                // we only allow deletion via POST request
                $this->loadModel($id)->delete();
                //Elimino el usuario con el mismo id que la materia
                $usuario = Users::model()->findByPk($id);
                if ($usuario != null)
                    $usuario->delete();
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollBack();
            }
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
        $model = new Materia('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Materia'])) {
            $model->attributes = $_GET['Materia'];
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');
            if (!empty($model->nombreMateria))
                $criteria->addCondition('nombreMateria = "' . $model->nombreMateria . '"');
        }
        $session['Materia_records'] = Materia::model()->findAll($criteria);
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
        $model = Materia::model()->findByPk($id);
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
        if (isset($session['Materia_records'])) {
            $model = $session['Materia_records'];
        } else
            $model = Materia::model()->findAll();
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
        $Criteria = new CDbCriteria();
        $Criteria->condition = "id != -1";
        $model = Materia::model()->findAll($Criteria);
        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
        ), true);
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Materia Report');
        $pdf->SetSubject('Materia Report');
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
        $pdf->Output("Materia_002.pdf", "I");
    }

    /**
     * Elimina todos los registros de materia
     */
    public function actionDeleteAll()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Materia;
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
