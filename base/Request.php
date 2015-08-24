<?php
namespace app\base;

/**
 * Description of Request
 *
 * @author Volkov Grigorii
 */
class Request
{
    /**
     * @return string
     */
    public function getMethod()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }
    
    /**
     * @return boolean
     */
    public function getIsGet()
    {
        return $this->getMethod() === 'GET';
    }
    
    /**
     * @return boolean
     */
    public function getIsPost()
    {
        return $this->getMethod() === 'POST';
    }
    
    /**
     * @return boolean
     */
    public function getIsAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
    
    /**
     * @return mixed
     */
    public function get($name = null, $dafault = null)
    {
        return is_null($name)? $_GET : (isset($_GET[$name])? $_GET[$name] : $default);
    }
    
    /**
     * @return mixed
     */
    public function post($name = null, $dafault = null)
    {
        return is_null($name)? $_POST : (isset($_POST[$name])? $_POST[$name] : $default);
    }
}
