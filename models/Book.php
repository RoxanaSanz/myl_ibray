<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "book".
 *
 * @property int $idBook
 * @property string $nameBook
 * @property string $editorial
 * @property string $author
 * @property string $synopsis
 * @property string $image
 */
class Book extends \yii\db\ActiveRecord
{
    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nameBook', 'editorial', 'author', 'synopsis'], 'required'],
            [['nameBook', 'editorial', 'author'], 'string', 'max' => 255],
            [['synopsis'], 'string', 'max' => 700],
            [['file'], 'file', 'extensions' => 'jpg, png'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'idBook' => 'Folio',
            'nameBook' => 'Nombre',
            'editorial' => 'Editorial',
            'author' => 'Autor',
            'synopsis' => 'Sinopsis',
            'file' => 'Portada',
            'image' => 'Portada',
            'available' => 'Estatus',
        ];
    }
}
