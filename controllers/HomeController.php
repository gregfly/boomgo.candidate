<?php
namespace app\controllers;

use app\Glob;
use app\base\Response;
use app\base\Controller;
use app\models\GameResult;

/**
 * Description of HomeController
 *
 * @author Volkov Grigorii
 */
class HomeController extends Controller
{
    public function actionDefault()
    {
        return $this->render('page');
    }
    
    public function actionCreate()
    {
        $model = new GameResult();
        if (Glob::$app->getRequest()->getIsPost()) {
            $model->setAttributes(Glob::$app->getRequest()->post());
            $model->save();
            if (Glob::$app->getRequest()->getIsAjax()) {
                Glob::$app->getResponse()->format = Response::FORMAT_JSON;
                return !$model->hasErrors()? : $model->getErrors();
            }
        }
        return $this->render('form', [
            'model' => $model,
        ]);
    }
    
    const PER_PAGE = 10;
    
    public function actionIndex($page = 1, $group = GameResult::GROUP_DAY, $perPage = self::PER_PAGE)
    {
        $data = [];
        try {
            foreach (GameResult::findPage($group, (integer)$page, $perPage) as $model) {
                $data[] = [
                    'rank' => $model->rank,
                    'name' => $model->name,
                    'score' => $model->score,
                ];
            }
        } catch (\Exception $ex) {
            $data = [];
        }
        if (Glob::$app->getRequest()->getIsAjax()) {
            Glob::$app->getResponse()->format = Response::FORMAT_JSON;
            return $data;
        }
        return $this->render('index', [
            'data' => $data,
            'page' => $page,
            'group' => $group,
        ]);
    }
}
