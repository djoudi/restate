<?php
$this->breadcrumbs = array(
    'Метро' => array('index'),
    $model->name => array('view', 'id' => $model->id),
    'Обновление',
);

$this->menu = array(
    array('label' => 'Добавить', 'url' => array('create')),
    array('label' => 'Просмотр', 'url' => array('view', 'id' => $model->id)),
    array('label' => 'Список', 'url' => array('index')),
);
?>

<h1>Обновить станцию метро <?php echo $model->name; ?></h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>