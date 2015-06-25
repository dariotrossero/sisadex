<?php
Yii::app()->clientScript->registerCssFile(Yii::app()->baseUrl . '/css/dragDrop.css'); ?>
<div class="titulo">
    <h1>Detalles del plan</h1>
</div>
<?php
$this->widget('bootstrap.widgets.TbMenu', array(
    'type' => 'pills',
    'items' => array(
        array('label' => 'Nuevo', 'visible' => Yii::app()->user->isadmin(), 'icon' => 'icon-plus', 'url' => Yii::app()->controller->createUrl('create'), 'linkOptions' => array()),
        array('label' => 'Listado', 'icon' => 'icon-th-list', 'url' => Yii::app()->controller->createUrl('index'), 'linkOptions' => array()),
        array('label' => 'Modificar', 'visible' => Yii::app()->user->isadmin(), 'icon' => 'icon-edit', 'url' => Yii::app()->controller->createUrl('update', array('id' => $model->id)), 'linkOptions' => array()),
    )));
?>
<div class='printableArea'>
    <?php $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes' => array(
            'anioPlan',
            array('name' => 'Carrera', 'type' => 'text', 'value' => $model->carrera->nombreCarrera),
        ),
    )); ?>
</div>
<?php
$arr = $model->materias;
if (count($arr) > 0) {
    echo '<table border="1" class="tg-table-light">';
    echo ' <tr>
            <th>1º Cuatrimestre</th>
            <th>2º Cuatrimestre</th>
          </tr>';
    for ($i = 1; $i < 6; $i++) {
        echo '   <tr class="tg-even">
            <td class="anio" colspan="2">' . $i . 'º Año</td>
          </tr>';
        echo '<td><ul>';
        $results = Materia::model()->findAllBySql('SELECT Materia.nombreMateria,Materia.id FROM Materia_has_Plan INNER JOIN Materia WHERE Materia_id=Materia.id AND anio=' . $i . ' AND cuatrimestre=1 AND Plan_id=' . $model->id);
        foreach ($results AS $result) {
            $nombre = $result->nombreMateria;
            $codigo = $result->id;
            echo '<li class="materia">' . $codigo . ' - ' . $nombre;
        }
        echo '</ul></td>';
        echo '<td><ul>';
        $results = Materia::model()->findAllBySql('SELECT Materia.nombreMateria,Materia.id FROM Materia_has_Plan INNER JOIN Materia WHERE Materia_id=Materia.id AND anio=' . $i . ' AND cuatrimestre=2 AND Plan_id=' . $model->id);
        foreach ($results AS $result) {
            $nombre = $result->nombreMateria;
            $codigo = $result->id;
            echo '<li class="materia">' . $codigo . ' - ' . $nombre;
        }
        echo '</ul></td>';
        echo '</tr>';
    }
    echo "</table>";
} else echo '<div class="alert alert-warning">
  No se han cargado materias.</div>'
?>
