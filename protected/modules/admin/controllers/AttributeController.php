<?php

class AttributeController extends Controller
{


    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'create', 'view', 'update'),
                'roles' => array('createApartment', 'viewApartment', 'updateApartment'),
            ),
            array('allow',
                'actions' => array('delete'),
                'roles' => array('manageApartment'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $model = new Attribute;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Attribute'])) {
            $model->attributes = $_POST['Attribute'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('create', array(
            'model' => $model,
        ));
    }

    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id, true);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Attribute'])) {
            $model->attributes = $_POST['Attribute'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $model = $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }


    public function actionRemove($id)
    {
        $this->loadModel($id)->delete();
        $this->redirect(array('index'));
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new Attribute('search');
        //        $cacheDependency = new CDbCacheDependency('SELECT MAX(updated_at) FROM apartment_type');
        //        $model->with('apartmentType')->cache(3600, $cacheDependency);
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Attribute']))
            $model->attributes = $_GET['Attribute'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id, $ml = false)
    {
        if ($ml) {
            $model = Attribute::model()->multilang()->findByPk($id);
        } else {
            $model = Attribute::model()->findByPk($id);
        }
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'attribute-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
