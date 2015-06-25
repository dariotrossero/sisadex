<?php

class TipoexamenController extends Controller
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
                    'deleteAll',
                    'deleteAllGlobals'
                    ),
                'users' => array(
                    'admin'
                    )
                ),
            array(
                'allow', // allow authenticated user to perform 'create' and 'update' actions
                'actions' => array(
                    'create',
                    'update',
                    'index',
                    'delete',
                    'DeleteAllMyRecords'
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
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Tipoexamen;
        // Uncomment the following line if AJAX validation is needed
        if (Yii::app()->request->isAjaxRequest) {
            $this->performAjaxValidation($model, "tipoexamen-create-form");
            if (isset($_POST['Tipoexamen'])) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    $model->attributes = $_POST['Tipoexamen'];
                    if (Yii::app()->user->isAdmin())
                        $model->Materia_id = -1;
                    else
                        $model->Materia_id = Yii::app()->user->name;
                    $criteria = new CDbCriteria();
                    $criteria->select = 'nombreTipoExamen, Materia_id';
                    $criteria->condition = 'LOWER(nombreTipoExamen)=:nombreTipoExamen AND Materia_id=:Materia_id';
                    $criteria->params = array(
                        ':nombreTipoExamen' => strtolower($_POST['Tipoexamen']['nombreTipoExamen']),
                        ':Materia_id' => $model->Materia_id
                        );
                    $records = Tipoexamen::model()->find($criteria);
                    if (count($records) > 0) {
                        echo "false";
                        return;
                    } //count($records) > 0
                    if ($model->save()) {
                        $transaction->commit();
                    } //$model->save()
                    else {
                        echo "false";
                    }
                    return;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    throw new CHttpException('Se produjo un error al intentar almacenar los datos. Contacte al administrador.');
                }
            } //isset($_POST['Tipoexamen'])
        } //Yii::app()->request->isAjaxRequest
        else {
            throw new CHttpException(404, 'La página solicitada no existe.');
        }
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate()
    {
        if (Yii::app()->request->isAjaxRequest) {
            $id = isset($_REQUEST["id"]) ? $_REQUEST["id"] : $_REQUEST["Tipoexamen"]["id"];
            $model = $this->loadModel($id);
            // Uncomment the following line if AJAX validation is needed
            $this->performAjaxValidation($model, "tipoexamen-update-form");
            if (isset($_POST['Tipoexamen'])) {
                $transaction = $model->dbConnection->beginTransaction();
                try {
                    if (Yii::app()->user->isAdmin())
                        $Materia_id = -1;
                    else
                        $Materia_id = Yii::app()->user->name;
          
                    if (Tipoexamen::model()->existsInDatabase($_POST['Tipoexamen']['nombreTipoExamen'],$Materia_id,$id)) {
                        echo "exists";
                        return;
                    } 
                    $model->attributes = $_POST['Tipoexamen'];
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
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete()
    {
        if (Yii::app()->request->isPostRequest) {
            $id = $_POST["id"];
            $transaction = Tipoexamen::model()->dbConnection->beginTransaction();
            try { // we only allow deletion via POST request
                $this->loadModel($id)->delete();
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
        } 
        else {
            throw new CHttpException(404, 'La página solicitada no existe.');
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
        $model = new Tipoexamen('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Tipoexamen'])) {
            $model->attributes = $_GET['Tipoexamen'];
            if (!empty($model->nombreTipoExamen))
                $criteria->addCondition('nombreTipoExamen = "' . $model->nombreTipoExamen . '"');
            if (!empty($model->Materia_id))
                $criteria->addCondition('Materia_id = "' . $model->Materia_id . '"');
            if (!empty($model->id))
                $criteria->addCondition('id = "' . $model->id . '"');
        } 
        $session['Tipoexamen_records'] = Tipoexamen::model()->findAll($criteria);
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
        $model = Tipoexamen::model()->findByPk($id);
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

    public function actionDeleteAll()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Tipoexamen;
            if ($passModel->isPasswordCorrect($pass)) {
                $model->deleteAll();
                echo "true";
            } 
            else
                echo "false";
            // we only allow deletion via POST request
        } 
        else {
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }

    /**
     * Elimina todos los registros comunes a todas las materias
     */
    public function actionDeleteAllGlobals()
    {
        $passModel = new ChangePasswordForm;
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $model = new Tipoexamen;
            if ($passModel->isPasswordCorrect($pass)) {
                $model->deleteAll("materia_id == -1");
                echo "true";
            } 
            else
                echo "false";
            // we only allow deletion via POST request
        } 
        else {
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }

    /**
     * Elimina todos los registros pertenecientes a una materia
     */
    public function actionDeleteAllMyRecords()
    {
        if (Yii::app()->request->isPostRequest) {
            $pass = $_POST["pass"];
            $materia = $_POST["mat"];
            $model = new Tipoexamen;
            if ($pass == $materia) {
                $model->deleteAll("materia_id == " . $materia);
                echo "true";
            } 
            else
                echo "false";
            // we only allow deletion via POST request
        } 
        else {
            throw new CHttpException(400, 'Solicitud de página inválida.');
        }
    }
}
