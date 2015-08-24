<?php
namespace app\controllers;

use app\base\Controller;
use app\models\GameResult;

/**
 * Description of DbController
 *
 * @author Volkov Grigorii
 */
class DbController extends Controller
{
    public function actionRandom($n = 50)
    {
        $names = [
            'Игорь',
            'Сергей',
            'Семен',
            'Кирилл',
            'Михаил',
        ];
        $dates = [
            strtotime('01.06.2015'),
            strtotime('07.07.2015'),
            strtotime('16.07.2015'),
            strtotime('20.07.2015'),
            strtotime('21.08.2015'),
            strtotime('22.08.2015'),
            strtotime('23.08.2015'),
        ];
        $array = array_fill(0, $n, null);
        foreach ($array as $key => $value) {
            $array[$key] = $value = new GameResult();
            $value->name = $names[mt_rand(0, count($names))];
            $value->date = $dates[mt_rand(0, count($dates))];
            $value->score = mt_rand(10, 100);
            $value->save();
        }
    }
}
