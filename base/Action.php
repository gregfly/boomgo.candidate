<?php
namespace app\base;

/**
 * Description of Action
 *
 * @author Volkov Grigorii
 */
abstract class Action
{
    /**
     * @var string
     */
    public $id;
    
    /**
     * @var \app\base\Controller
     */
    public $controller;
    
    public abstract function runWithParam($params);
}
