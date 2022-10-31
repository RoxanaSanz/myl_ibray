<?php

namespace app\models;

use Yii;
use yii\base\Model;

class LibroForm extends Model
{
    public $name;
    public $editorial;
    public $autor;
    public $sinopsis;
    public $folio;

    public function rules()
    {
        return [
            ['name', 'required', 'message' => 'Por favor, ingrese el nombre del libro.'],
            ['editorial', 'required', 'message' => 'Por favor, ingrese el nombre de la editorial.'],
            ['autor', 'required', 'message' => 'Por favor, ingrese el nombre del autor.'],
            ['sinopsis', 'required', 'message' => 'Por favor, ingrese la sinopsis del libro.'],
            ['folio', 'required', 'message' => 'Por favor, ingrese el folio del libro.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Nombre del libro',
            'editorial' => 'Editorial',
            'autor' => 'Nombre del autor',
            'sinopsis' => 'Sinopsis del libro',
            'folio' => 'Folio del libro',
        ];
    }
}