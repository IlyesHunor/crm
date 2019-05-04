<?php

namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 *
 */
class ImageUploader extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'image' => 'Image',
        ];
    }
}
