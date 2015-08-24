<?php
namespace app\base;

/**
 * Description of Response
 *
 * @author Volkov Grigorii
 */
class Response
{
    const FORMAT_RAW = 'raw';
    const FORMAT_HTML = 'html';
    const FORMAT_JSON = 'json';
    
    /**
     * @var string
     */
    public $format = self::FORMAT_HTML;
    
    /**
     * @var mixed
     */
    public $data;
    
    private $_response;
    
    /**
     * @return string
     */
    public function getResponse()
    {
        if (!$this->_response) {
            switch ($this->format) {
            case self::FORMAT_RAW:
                $this->_response = $this->data;
            break;
            case self::FORMAT_HTML:
                header('Content-Type: text/html');
                $this->_response = $this->data;
            break;
            case self::FORMAT_JSON:
                header('Content-Type: application/json');
                $this->_response = json_encode($this->data);
            break;
            default:
                throw new \ErrorException();
            }
        }
        return $this->_response;
    }
}
