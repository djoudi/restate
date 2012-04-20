<?php
$this->breadcrumbs = array(
    'Типы объектов' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Обновление',
);

$this->menu = array(
    array('label' => 'Добавить', 'url' => array('create')),
    array('label' => 'Просмотр', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Список', 'url' => array('index')),
);
?>

<h1>Обновить тип объекта <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>