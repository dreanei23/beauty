<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\imagine\Image;
use yii\helpers\Inflector;


class UploadPhoto extends Model
{

    public $image;
    public $images;
    
    public function saveImage($image, $prefix = 'img') 
    {
        //полный путь к файлу
        $full_path = $this->fileName($prefix . '-' . $image->baseName, $image->extension);

        //сохраняем файл
        $image->saveAs($full_path);

        //обрезать пропорционально, + ухудшить качество
        Image::resize(Yii::getAlias('@webroot/' . $full_path), 1000, 1000)
            ->save(Yii::getAlias('@webroot/' . $full_path), ['quality' => 80]);

        return $full_path;
    }
    

    public function filePath() 
    {
        //id пользователя
        $user_id = Yii::$app->user->identity->id ?: 'guest';

        //путь к папке с фото
        $path_folder = 'images/' . date("Y/m/d") . '/' . $user_id . '/';

        
        //если папки нет - создаем
        if(!is_dir($path_folder)) {
            mkdir($path_folder, 0755, true);
        }

        return $path_folder;
    }

    public function fileName($baseName, $extension)
    {
        //название фото
        $file_name = time() . '-' . Inflector::slug($baseName) . '.' . $extension;

        return $this->filePath() . $file_name;
    } 

}