<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\web\View;
use yii\helpers\ArrayHelper;
use kartik\select2\Select2;


$url_delete_image = Url::to(["delete-image", "id" => $salon["id"]]);
$delete_image_js = <<< JS
    $('.delete-image').on('click', function (e) {
        var result_confirm = confirm('Вы уверены, что хотите удалить изображение?');
        if (result_confirm) {
            var delete_image = $(this);
            var salon_img = delete_image.prev('.salon_img');
            $.ajax({
                type:'POST',
                cache: false,
                url: '$url_delete_image?img=' + delete_image.attr('data-img') + '&type=' + delete_image.attr('data-type'),
                success  : function(response) {
                    salon_img.remove();
                    delete_image.after(response);
                    delete_image.remove();
                }
            });
        }
        e.preventDefault();
    });
JS;


$this->registerJs( $delete_image_js, $position = View::POS_READY, $key = null );
$this->registerJsFile('@web/js/cabinet_add.js',  ['position' => View::POS_END]);

?>



<?php $form = ActiveForm::begin([
    'id' => 'form-input-example',
    'options' => [
        'class' => 'form-horizontal col-lg-6',
        'enctype' => 'multipart/form-data',
    ],
]) ?>


    <?= $form->field($salon, 'city_id')->widget(Select2::classname(), [
        'data' =>  ArrayHelper::map($cities, 'id', 'name'),
        'language' => 'ru',
        'options' => ['placeholder' => 'Выберите город'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
        'pluginEvents' => [
            "select2:select" => "function(e) { 
                var selected_city = e.params.data.text;
                $('#add_form').removeClass('hidden');
                setCenter(selected_city);
                $('#suggest').val(selected_city + ', ');
                $('#suggest').focus();
            }",
            "select2:unselect" =>"function(e) { 
                $('#add_form').addClass('hidden');
            }",
        ],
    ]);?>

    <div id="add_form" class="hidden">

    <?= $form->field($salon, 'adress', [
        'inputOptions' => [
            'id' => 'suggest',
            'class' => 'form-control'
        ]
    ]) ?>

    <div id="ya_map" style="width: 600px; height: 400px"></div>

    <?= $form->field($salon, 'name'); ?>


    <?= Html::activeHiddenInput($salon, 'latitude', [
        'id' => 'latitude'
    ]); ?>

    <?= Html::activeHiddenInput($salon, 'longitude', [
        'id' => 'longitude'
    ]); ?>


    <? if ($salon->logo): ?>
        <div class="row">
            <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                <?= Html::img('/' . $salon['logo'], ['alt' => $salon['name'], 'class'=> 'img-responsive salon_img']) ?>    
                <a class="delete-image" href="<?= Url::to(["delete-image", "id" => $salon["id"], "img" => $salon['logo'], "type" => "logo"]) ?>" data-type="logo" data-img="<?= $salon['logo'] ?>"><span class="glyphicon glyphicon-trash"></span> Удалить</a>
            </div>  
        </div>
    <? endif; ?>

    <?= $form->field($salon, 'logo')->fileInput() ?>

    <? if ($salon->gallery):
        $gallery = explode(', ', $salon->gallery);      
    ?>
        <div class="row">
            <? foreach ($gallery as $key=>$img): ?>
                <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
                    <?= Html::img('/' . $img, ['alt' => $salon['name'], 'class'=> 'img-responsive salon_img']) ?>
                    <a class="delete-image" href="<?= Url::to(["delete-image", "id" => $salon["id"], "img" => $img, "type" => "gallery"]) ?>" data-type="gallery" data-img="<?= $img ?>"><span class="glyphicon glyphicon-trash"></span> Удалить</a>
                </div>
            <? endforeach; ?>  
        </div>
    <? endif; ?>

    <?= $form->field($salon, 'gallery[]')->fileInput(['multiple' => true, 'accept' => 'image/*']) ?>
    <?= Html::submitButton($salon->id ? 'Обновить' : 'Добавить', ['class' => 'btn btn-success']) ?>


    </div>

<?php ActiveForm::end() ?>



