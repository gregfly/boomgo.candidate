<?php

namespace app\base;

use app\Glob;
use app\base\InlineAction;

/**
 * Description of Controller
 *
 * @author Volkov Grigorii
 */
class Controller
{
    /**
     * @var string
     */
    public $id;
    
    /**
     * @var string
     */
    public $defaultAction = 'default';
    
    /**
     * @param string $actionId
     * @return \app\base\Action
     */
    public function createAction($actionId)
    {
        if (is_null($actionId)) {
            $actionId = $this->defaultAction;
        }
        $inlineMethod = 'action' . ucfirst($actionId);
        if (method_exists($this, $inlineMethod)) {
            $action = new InlineAction();
            $action->controller = $this;
            $action->id = $actionId;
            $action->method = $inlineMethod;
            return $action;
        }
        throw new \ErrorException('Page not found');
    }
    
    /**
     * @param string $view
     * @param array $params
     * @return string
     */
    public function render($view, $params = [])
    {
        return Glob::$app->getView()->render($view, $params);
    }
    
    /**
     * @return string
     */
    public function getLayout()
    {
        return Glob::$app->layout;
    }
}
