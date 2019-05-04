<?php

namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 *
 */
class PdfUploader extends Model
{
    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => true, 'extensions' => 'pdf'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'file' => 'File',
        ];
    }
}
