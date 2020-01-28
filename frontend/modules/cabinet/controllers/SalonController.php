<?php

namespace app\modules\cabinet\controllers;

use common\models\Salon;
use common\models\City;
use common\models\UploadPhoto;
use yii\web\UploadedFile;
use Yii;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `cabinet` module
 */
class SalonController extends MainController
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        
        $salons = Salon::find()->asArray()->all();

        return $this->render('index', [
            'salons' => $salons,
        ]);
    }



    public function actionAdd()
    {
        //получаем все города
        $cities = City::find()->all();

        //создаем объект салона
        $salon = new Salon();

        
        if($salon->load(Yii::$app->request->post()) && $salon->validate()) {

            
            $uploadPhoto = new UploadPhoto;

            //Загрузка одного изображения
            $uploadPhoto->image = UploadedFile::getInstance($salon, 'logo');
            if ($uploadPhoto->image) {
                $full_path = $uploadPhoto->saveImage($uploadPhoto->image, 'logo');
                $salon->logo = $full_path;
            } else {
                $salon->logo = '';
            }

            //Загрузка нескольких изображений
            $uploadPhoto->images = UploadedFile::getInstances($salon, 'gallery');
            if ($uploadPhoto->images) {
                foreach ($uploadPhoto->images as $image) {
                    $full_path = $uploadPhoto->saveImage($image, 'glr');
                    $gallery[] = $full_path;
                }
                $salon->gallery = implode(', ', $gallery);
            } else {
                $salon->gallery = '';
            }


            //обрабатываем остальные поля
            //$salon->city_id = 1;
            $salon->user_id = 1;

            dump($salon);
            exit;
            //сохраняем в бд
            if ($salon->save()) {
                Yii::$app->session->setFlash('success', "Салон добавлен!");
                return $this->refresh();
            }

        }

        return $this->render('add', [
            'salon' => $salon,
            'cities' => $cities,
        ]);
    
    }

    public function actionEdit($id=0)
    {

        $salon = Salon::findOne($id);

        if (!$id || !$salon) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $old_salon_logo = $salon->logo;
        
        if($salon->load(Yii::$app->request->post()) && $salon->validate()) {

            //Загрузка одного изображения
            $uploadPhoto = new UploadPhoto;
            $uploadPhoto->image = UploadedFile::getInstance($salon, 'logo');
            if ($uploadPhoto->image) {
                if ($old_salon_logo) unlink(Yii::getAlias('@webroot/') . $old_salon_logo);
                $full_path = $uploadPhoto->saveImage($uploadPhoto->image);
                $salon->logo = $full_path;
            }

            if ($salon->save()) {
                Yii::$app->session->setFlash('success', "Салон обновлен!");
                return $this->refresh();
            }

        }

        return $this->render('edit', [
            'salon' => $salon,
        ]);

    }


    public function actionDeleteImage($id=0)
    {
        $salon = Salon::findOne($id); 
        
        if (!$id || !$salon) {
            throw new NotFoundHttpException('Страница не найдена');
        }

        $get = Yii::$app->request->get();

        if ($get["type"] == "logo") {
            $salon->logo = '';
        }

        if ($get["type"] == "gallery") {
            $gallery = explode(", ", $salon->gallery); //текущие фото превращаем в массив
            $key = array_search($get["img"], $gallery); //ищем в массиве фото, которое надо удалить
            if ($key >= 0) {
                unset($gallery[$key]); //удаляем фото и массив превращаем обратно в строку
                $salon->gallery = implode(", ", $gallery);
            }
        }

        unlink(Yii::getAlias('@webroot/') . $get["img"]);
        $salon->update();

        if (Yii::$app->request->isAjax) {
            return '<span class="text-success">Изображение удалено!</span>';
        } else {
            Yii::$app->session->setFlash('success', "Изображение удалено!");
            return $this->redirect(['edit', 'id' => $salon->id]);
        }

    }


}
