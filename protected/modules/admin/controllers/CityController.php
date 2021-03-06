<?php

class CityController extends Controller
{

    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'create', 'view', 'update', 'ajaxDependsOfCity'),
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
        $model = new City;

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['City'])) {
            $model->attributes = $_POST['City'];
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
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['City'])) {
            $model->attributes = $_POST['City'];
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
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('index'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Manages all models.
     */
    public function actionIndex()
    {
        $model = new City('search');
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['City']))
            $model->attributes = $_GET['City'];

        $this->render('index', array(
            'model' => $model,
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = City::model()->findByPk($id);
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
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'city-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionAjaxDependsOfCity()
    {
        $cacheDependency = new CDbCacheDependency('SELECT MAX(a.updated_at) FROM area a, metro_station');
        $cityId = Yii::app()->request->getPost('city_id');
        $model = City::model()->with(array('areas', 'metroStations'))->cache(3600, $cacheDependency)->findByPk($cityId);
        if (count($model->areas)) {
            $areaOptions = CHtml::tag('option', array('value' => 0), CHtml::encode('Нужно выбрать город'), true);
            if ($model && ($data = CHtml::listData($model->areas, 'id', 'name')) !== null) {
                $areaOptions = CHtml::tag('option', array('value' => 0), CHtml::encode('Выбрать'), true);
                foreach ($data as $id => $value) {
                    $areaOptions .= CHtml::tag('option', array('value' => $id), CHtml::encode($value), true);
                }
            }
        } else {
            $areaOptions = CHtml::tag('option', array('value' => 0), CHtml::encode('Нет данных для этого города'), true);
        }

        if (count($model->metroStations) != 0) {
            $metroOptions = CHtml::tag('option', array('value' => 0), CHtml::encode('Нужно выбрать город'), true);
            if ($model && ($data = CHtml::listData($model->metroStations, 'id', 'name')) !== null) {
                $metroOptions = CHtml::tag('option', array('value' => 0), CHtml::encode('Выбрать'), true);
                foreach ($data as $id => $value) {
                    $metroOptions .= CHtml::tag('option', array('value' => $id), CHtml::encode($value), true);
                }
            }
        } else {
            $metroOptions = CHtml::tag('option', array('value' => 0), CHtml::encode('Нет данных для этого города'), true);
        }

        echo CJSON::encode(array(
            'areas' => $areaOptions,
            'metroStations' => $metroOptions,
        ));
    }
}
