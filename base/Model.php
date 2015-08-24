<?php
namespace app\base;

use app\Glob;

/**
 * Description of Model
 *
 * @author Volkov Grigorii
 */
abstract class Model
{
    /**
     * @var array
     */
    public $attributeNames = [];
    
    /**
     * @return array key-value pairs
     */
    public function getAttributes($includePk = true)
    {
        $pks = [];
        if (!$includePk) {
            $pks = $this->getPrimaryKeys();
        }
        $result = [];
        foreach ($this->attributeNames as $value) {
            if (!array_key_exists($value, $pks)) {
                $result[$value] = $this->$value;
            }
        }
        return $result;
    }
    
    /**
     * @param array $attributes
     */
    public function setAttributes($attributes, $includePk = false)
    {
        $attrs = $this->getAttributes($includePk);
        foreach ($attributes as $key => $value) {
            if (array_key_exists($key, $attrs)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param array $attributeNames
     * @return boolean
     */
    public function validate($attributeNames = [])
    {
        return !$this->hasErrors();
    }
    
    /**
     * @param boolean $runValidation
     * @param array $attributeNames
     * @return boolean
     */
    public function save($runValidation = true, $attributeNames = [])
    {
        if (empty($attributeNames)) {
            $attributeNames = array_keys($this->getAttributes(false));
        }
        if ($runValidation) {
            $this->clearErrors();
            if (!$this->validate($attributeNames)) {
                return false;
            }
        }
        return $this->internalSave($attributeNames);
    }
    
    /**
     * @return \PDO
     */
    public static function db()
    {
        return Glob::$app->getDb();
    }
    
    /**
     * @return string
     */
    public static function tableName()
    {
        $cls = new \ReflectionClass(get_called_class());
        return $cls->getShortName();
    }
    
    /**
     * @return array
     */
    public function getPrimaryKeys()
    {
        //get first attribute
        foreach ($this->getAttributes() as $key => $value) {
            return [$key => $value];
        }
        throw new \ErrorException('Primary keys not found');
    }
    
    /**
     * @param array|string|integer $value
     */
    public function setPrimaryKeys($value)
    {
        $value = (array)$value;
        $pks = array_keys($this->getPrimaryKeys());
        foreach ($value as $key => $val) {
            if (is_numeric($key)) {
                if (isset($pks[$key])) {
                    $key = $pks[$key];
                    $this->$key = $val;
                }
            } else if (in_array($key, $pks)) {
                $this->$key = $val;
            }
        }
    }

    /**
     * @return boolean
     */
    public function getIsNewRecord()
    {
        foreach ($this->getPrimaryKeys() as $value) {
            if (is_null($value)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * @param array $attributeNames
     * @return boolean
     */
    protected function internalSave($attributeNames = [])
    {
        if ($this->getIsNewRecord()) {
            return $this->internalInsert($attributeNames);
        } else {
            return $this->internalUpdate($attributeNames);
        }
    }
    
    /**
     * @param array $attributeNames
     * @return boolean
     */
    protected function internalInsert($attributeNames)
    {
        $params = [];
        $q = 'INSERT INTO ' . static::tableName() . ' (' . implode(', ', $attributeNames) . ')';
        $q = $q . ' VALUES (' . implode(', ', array_fill(0, count($attributeNames), '?')) . ')';
        foreach ($attributeNames as $value) {
            $params[] = $this->$value;
        }
        $st = static::db()->prepare($q);
        if ($st->execute($params)) {
            $this->setPrimaryKeys(static::db()->lastInsertId());
            return true;
        }
        throw new \ErrorException($st->errorInfo()[2]);
    }
    
    /**
     * @param array $attributeNames
     * @return boolean
     */
    protected function internalUpdate($attributeNames)
    {
        $params = [];
        $q = 'UPDATE ' . static::tableName() . ' SET ';
        $set = [];
        foreach ($attributeNames as $value) {
             $set[] = $value . ' = ?';
             $params[] = $this->$value;
        }
        $q = $q . implode(', ', $set);
        $q = $q . ' WHERE ';
        $where = [];
        foreach ($this->getPrimaryKeys() as $key => $value) {
            $where[] = $key . ' = ?';
            $params[] = $value;
        }
        $q = $q . implode(' AND ', $where);
        $st = static::db()->prepare($q);
        if ($st->execute($params)) {
            return $st->rowCount();
        }
        throw new \ErrorException($st->errorInfo()[2]);
    }

    private $_errors = [];
    
    /**
     * @param string $attribute
     * @return array
     */
    public function getErrors($attribute = null)
    {
        return is_null($attribute)? $this->_errors : (isset($this->_errors[$attribute])? $this->_errors[$attribute] : []);
    }
    
    /**
     * @param string $attribute
     * @return boolean
     */
    public function hasErrors($attribute = null)
    {
        return !empty($this->getErrors($attribute));
    }
    
    /**
     * Clear current error messages
     */
    public function clearErrors()
    {
        $this->_errors = [];
    }
    
    /**
     * @param string $attribute
     * @param string $message
     */
    public function addError($attribute, $message)
    {
        if (!isset($this->_errors[$attribute])) {
            $this->_errors[$attribute] = [];
        }
        $this->_errors[$attribute][] = $message;
    }
    
    /**
     * @param array $data
     */
    public function populateRow($data)
    {
            
        foreach ($data as $key => $value) {
            if ($this->has($key)) {
                $this->$key = $value;
            }
        }
    }
    
    private $_attributes;

    public function __get($name)
    {
        if (in_array($name, $this->attributeNames)) {
            return isset($this->_attributes[$name])? $this->_attributes[$name] : null;
        }
        if (property_exists($this, $name)) {
            return $this->$name;
        }
        throw new \ErrorException("Property {$name} not exists");
    }
    
    public function __set($name, $value)
    {
        if (in_array($name, $this->attributeNames)) {
            $this->_attributes[$name] = $value;
            return;
        }
        if (property_exists($this, $name)) {
            $this->$name = $value;
            return;
        }
        throw new \ErrorException("Property {$name} not exists");
    }
    
    public function __isset($name)
    {
        return $this->has($name);
    }
    
    public  function has($name)
    {
        return !is_numeric($name) && (in_array($name, $this->attributeNames) || property_exists($this, $name));
    }
}
