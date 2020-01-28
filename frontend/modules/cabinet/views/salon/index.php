<?php

use yii\helpers\Url;

$this->params['breadcrumbs'][] = 'Салоны';

?>

<h1>
    Все салоны
    <a href="<?= Url::to(['add']); ?>" class="btn btn-success">Добавить салон</a>
</h1>


<? //dump($salons); ?>
<? foreach ($salons as $salon): ?>
    
    <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6">
        <h3>
            <?= $salon['name'] ?> <small><?= $salon['adress'] ?></small>
        </h3>
        
        <a href="<?= Url::to(['edit', 'id' => $salon['id']]); ?>" class="btn btn-xs btn-default">Редактировать салон</a>

        <div class="col-xs-4 col-sm-4 col-md-4 col-lg-4">
            <?= yii\helpers\Html::img('/' . $salon['logo'], ['alt' => $salon['name'], 'class'=> 'img-responsive']) ?> 
        </div>
        
    </div>
    

<? endforeach; ?>