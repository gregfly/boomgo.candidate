<?php
namespace app\base;

/**
 * Description of Application
 *
 * @author Volkov Grigorii
 */
class Application
{
    /**
     * @var string
     */
    public $controllerNamespace = 'app\controllers';
    
    /**
     * @var \app\base\Action
     */
    public $action;
    
    /**
     * @var string
     */
    public $layout = 'layouts/main';
    
    /**
     * @var string
     */
    public $errorView = 'site/error';
    
    /**
     * @var string
     */
    public $defaultController = 'home';

    public function getRequestedRoute()
    {
        return preg_replace('/\?.*$/', '', $_SERVER['REQUEST_URI']);
    }
    
    public function run()
    {
        try {
            $this->begin();
        } catch (\Exception $ex) {
            if ($this->getResponse()->format === Response::FORMAT_HTML) {
                $this->getResponse()->data = $this->getView()->render($this->errorView, [
                    'exception' => $ex,
                ]);
            } else {
                $this->getResponse()->data = [
                    'name' => get_class($ex),
                    'code' => $ex->getCode(),
                    'message' => $ex->getMessage(),
                ];
            }
        }
        $this->end();
    }
    
    protected function resolveRoute($route)
    {
        $parts = explode('/', ltrim($route, '/'));
        if (empty($parts) || count($parts) > 2) {
            throw new \ErrorException('Failed to resolve requested url');
        }
        return [strlen($parts[0])? $parts[0] : null, isset($parts[1]) && strlen($parts[1])? $parts[1] : null];
    }
    
    public function createController($route)
    {
        list($controllerId, $actionId) = $this->resolveRoute($route);
        if (is_null($controllerId)) {
            $controllerId = $this->defaultController;
        }
        $controllerClass = $this->controllerNamespace . '\\' . ucfirst($controllerId) . 'Controller';
        if (!class_exists($controllerClass)) {
            throw new \ErrorException("Class {$controllerClass} is not avaliable");
        }
        /* @var $controller \app\base\Controller */
        $controller = new $controllerClass();
        $controller->id = $controllerId;
        $action = $controller->createAction($actionId);
        return [$controller, $action];
    }
    
    public function begin()
    {
        set_error_handler([$this, 'errorHandler']);
        
        list($controller, $action) = $this->createController($this->getRequestedRoute());
        $this->getResponse()->data = $this->runAction($action, $this->getRequest()->get());
    }
    
    public function end()
    {
        echo $this->getResponse()->getResponse();
    }
    
    /**
     * @param \app\base\Action $action
     */
    public function runAction($action, $params = [])
    {
        $oldAction = $this->action;
        $this->action = $action;
        ob_start();
        ob_implicit_flush(false);
        $result = $action->runWithParam($params);
        $buf = ob_get_clean();
        if (is_null($result)) {
            $result = $buf;
        }
        $this->action = $oldAction;
        return $result;
    }
    
    private $_response;
    
    /**
     * @return \app\base\Response
     */
    public function getResponse()
    {
        if (!$this->_response) {
            $this->_response = new Response();
        }
        return $this->_response;
    }
    
    private $_view;
    
    /**
     * @return \app\base\View
     */
    public function getView()
    {
        if (!$this->_view) {
            $this->_view = new View();
        }
        return $this->_view;
    }
    
    private $_request;
    
    /**
     * @return \app\base\Request
     */
    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = new Request();
        }
        return $this->_request;
    }
    
    private $_db;
    
    /**
     * @return \PDO
     */
    public function getDb()
    {
        if (!$this->_db) {
            $this->_db = new \PDO('mysql:host=localhost;dbname=app', 'user', 'user');
        }
        return $this->_db;
    }
    
    public function errorHandler($errno, $errstr, $errfile, $errline)
    {
        if (!(error_reporting() & $errno)) {
            return;
        }
        switch ($errno) {
            case E_USER_ERROR:
            echo "<b>My ERROR</b> [$errno] $errstr<br />\n";
            echo "  Фатальная ошибка в строке $errline файла $errfile";
            echo ", PHP " . PHP_VERSION . " (" . PHP_OS . ")<br />\n";
            echo "Завершение работы...<br />\n";
            exit(1);
        break;
        case E_USER_WARNING:
            echo "<b>My WARNING</b> [$errno] $errstr<br />\n";
        break;
        case E_USER_NOTICE:
            echo "<b>My NOTICE</b> [$errno] $errstr<br />\n";
        break;
        default:
            echo "Неизвестная ошибка: [$errno] $errstr<br />\n";
        break;
        }
        return true;
    }
}
