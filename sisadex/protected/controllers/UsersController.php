<?php

class UsersController extends Controller
{
    public $breadcrumbs;
    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     */
    public $layout = 'main';

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
                    'index',
                    'view',
                    'delete',
                    'ChangePassword',
                    'DeleteAll'
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
     * Unused
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
        $model = new Users;
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "users-create-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Users'])) {
                $model->id = ltrim($_POST['Users']['id'], "0");
                $model->password = ltrim($_POST['Users']['id'], "0");
                $model->role = 0;
                if ($model->save()) {
                    echo $model->id;
                } else {
                    echo "false";
                }
                return;
            }
        } else {
            if (isset($_POST['Users'])) {
                $model->attributes = $_POST['Users'];
                var_dump($_POST['Users']);
                $model->id = ltrim($_POST['Users']['id'], "0");
                $model->password = ltrim($_POST['Users']['id'], "0");
                $model->role = 0;
                if ($model->save())
                    $this->redirect(array(
                        'view',
                        'id' => $model->id
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
        $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["Users"]["id"];
        $model = $this->loadModel($id);
        // Uncomment the following line if AJAX validation is needed
        $this->performAjaxValidation($model, "users-update-form");
        if (Yii::app()->request->isAjaxRequest) {
            if (isset($_POST['Users'])) {
                $model->attributes = $_POST['Users'];
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
        if (isset($_POST['Users'])) {
            $model->attributes = $_POST['Users'];
            if ($model->save())
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
        $model = new Users('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Users'])) {
            $model->attributes = $_GET['Users'];
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');
            if (!empty($model->password))
                $criteria->addCondition('password = "' . $model->password . '"');
            if (!empty($model->role))
                $criteria->addCondition('role = "' . $model->role . '"');
        }
        $session['Users_records'] = Users::model()->findAll($criteria);
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
        $model = Users::model()->findByPk($id);
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

    /**
     * Unused
     */
    public function actionGenerateExcel()
    {
        $session = new CHttpSession;
        $session->open();
        if (isset($session['Users_records'])) {
            $model = $session['Users_records'];
        } else
        $model = Users::model()->findAll();
        Yii::app()->request->sendFile(date('YmdHis') . '.xls', $this->renderPartial('excelReport', array(
            'model' => $model
            ), true));
    }

    /**
     * Unused
     */
    public function actionGeneratePdf()
    {
        $session = new CHttpSession;
        $session->open();
        Yii::import('application.extensions.ajaxgii.bootstrap.*');
        require_once('tcpdf/tcpdf.php');
        require_once('tcpdf/config/lang/eng.php');
        if (isset($session['Users_records'])) {
            $model = $session['Users_records'];
        } else
        $model = Users::model()->findAll();
        $html = $this->renderPartial('expenseGridtoReport', array(
            'model' => $model
            ), true);
        //die($html);
        $pdf = new TCPDF();
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor(Yii::app()->name);
        $pdf->SetTitle('Users Report');
        $pdf->SetSubject('Users Report');
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
        $pdf->Output("Users_002.pdf", "I");
    }
    
    /**
     * Cambia la contraseña del administrador
     */
    public function actionChangePassword()
    {
        $model = new ChangePasswordForm;
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
        // collect user input data
        if (isset($_POST['ChangePasswordForm'])) {
            $model->attributes = $_POST['ChangePasswordForm'];
            // Validar input del usuario y cambiar contraseña.
            if ($model->validate() && $model->changePassword()) {
                Yii::app()->user->setFlash('success', '<strong>Éxito!</strong> Su contraseña fue cambiada.');
                $this->redirect(array(
                    'index'
                    ));
            }
        }
        // Mostrar formulario de cambio de contraseña.
        $this->render('changePassword', array(
            'model' => $model
            ));
    }

    /**
     * Elimina todos los usuarios
     */
    public function actionDeleteAll()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Users;
            if ($passModel->isPasswordCorrect($pass)) {
                $model->deleteAll("id != 'admin'");
                echo "true";
            } else
            echo "false";
            // we only allow deletion via POST request
        } else {
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }
}
