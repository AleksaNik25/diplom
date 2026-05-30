<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

class Assist extends Model
{

    public static function getItems(string $tableName): array
    {
        return ActiveRecord::find()
            ->from($tableName)
            ->select('title')
            ->indexBy('id')
            ->column();
    }

    public static function getColsItems(string $tableName, array $cols): array
    {

        return (new \yii\db\Query())
            ->from($tableName)
            ->indexBy('id')
            ->all();
    }


    public static function sendMail($data)
    {
        Yii::$app->mailer->htmlLayout = "@app/mail/layouts/html";
        return Yii::$app->mailer
            ->compose('mail', [
                "data" => $data
            ])
            ->setFrom("iv2-22-web@mail.ru")
            ->setTo("iv2-22-web@mail.ru")
            ->setSubject("Тестовое письмо")
            ->send();
    }
}
