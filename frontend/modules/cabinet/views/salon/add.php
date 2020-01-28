<?php

$this->params['breadcrumbs'][] = ['label' => 'Салоны', 'url' => ['/cabinet/salon']];
$this->params['breadcrumbs'][] = 'Добавить салон';

?>

<h1>Добавить салон</h1>


<?= $this->render('_form', [
        'salon' => $salon,
        'cities' => $cities,
]) ?>


