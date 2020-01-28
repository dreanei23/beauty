<?php

$this->params['breadcrumbs'][] = ['label' => 'Салоны', 'url' => ['/cabinet/salon']];
$this->params['breadcrumbs'][] = 'Редактировать салон';

?>

<h1>Редактировать салон</h1>


<?= $this->render('_form', [
        'salon' => $salon,
        'cities' => $cities,
]) ?>


