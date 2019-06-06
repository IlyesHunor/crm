<?php

namespace app\helpers;

use Yii;
use yii\base\Model;
use yii\web\UploadedFile;

/**
 *
 */
class CsvUploader extends Model
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
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions'=>['xls', 'csv'], 'checkExtensionByMimeType'=>false, 'maxSize'=>1024 * 1024 * 2],
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
