<?php
namespace app\base;

use app\Glob;

/**
 * Description of View
 *
 * @author Volkov Grigorii
 */
class View
{
    /**
     * @var string
     */
    public $ext = '.php';
    
    /**
     * @var string
     */
    public $title = 'Application';
    
    public function findLayout()
    {
        return Glob::$app->action? Glob::$app->action->controller->getLayout() : Glob::$app->layout;
    }
    
    public function render($view, $params = [])
    {
        $layout = $this->findLayout();
        $content = $this->getViewContent($view, $params);
        if ($layout === false) {
            return $content;
        }
        return $this->getViewContent($layout, [
            'content' => $content,
        ]);
    }
    
    public function getViewContent($view, $params)
    {
        $viewPath = $this->buildViewPath($view);
        if ($viewPath === false) {
            throw new \ErrorException("View '{$view}' not found");
        }
        extract($params);
        ob_start();
        ob_implicit_flush(false);
        include $viewPath;
        return ob_get_clean();
    }
    
    public function buildViewPath($view)
    {
        $parts = [
            __DIR__,
            '..',
        ];
        if (strpos($view, DIRECTORY_SEPARATOR . DIRECTORY_SEPARATOR) !== 0) {
            $parts[] = 'views';
        }
        if (strpos($view, DIRECTORY_SEPARATOR) === false) {
            $parts[] = Glob::$app->action->controller->id;
        }
        $parts[] = $view . $this->ext;
        $filename = implode(DIRECTORY_SEPARATOR, $parts);
        if (file_exists($filename)) {
            return $filename;
        }
        return false;
    }
}
