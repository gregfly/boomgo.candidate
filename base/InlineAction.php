<?php
namespace app\base;

use app\base\Action;

/**
 * Description of InlineAction
 *
 * @author Volkov Grigorii
 */
class InlineAction extends Action
{
    /**
     * @var string
     */
    public $method;
    
    public function runWithParam($params)
    {
        $callback = [$this->controller, $this->method];
        $missing = [];
        $args = [];
        $method = new \ReflectionMethod($callback[0], $callback[1]);
        foreach ($method->getParameters() as $param) {
            $name = $param->getName();
            if (array_key_exists($name, $params)) {
                if ($param->isArray()) {
                    $args[] = (array)$params[$name];
                } elseif (!is_array($params[$name])) {
                    $args[] = $params[$name];
                } else {
                    throw new \ErrorException("Invalid data received for parameter '{$name}'.");
                }
                unset($params[$name]);
            } elseif ($param->isDefaultValueAvailable()) {
                $args[] = $param->getDefaultValue();
            } else {
                $missing[] = $name;
            }
        }
        if (!empty($missing)) {
            throw new \ErrorException('Missing params: ' . implode(', ', $missing));
        }
        return call_user_func_array($callback, $args);
    }
}
