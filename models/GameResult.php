<?php
namespace app\models;

use app\base\Model;

/**
 * Description of GameResult
 *
 * @author Volkov Grigorii
 * 
 * @property integer $id
 * @property string $name
 * @property integer $score
 * @property integer $date
 */
class GameResult extends Model
{
    const GROUP_ALL = 10000;
    const GROUP_DAY = 10001;
    const GROUP_MONTH = 10002;
    const GROUP_WEEK = 10003;
    
    /**
     * @inheritdoc
     */
    public $attributeNames = ['id', 'name', 'score', 'date'];
    
    /**
     * @var integer
     */
    public $rank;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'game_result';
    }
    
    /**
     * @inheritdoc
     */
    public function validate($attributeNames = [])
    {
        if (in_array('name', $attributeNames)) {
            if (!is_string($this->name) || !strlen($this->name)) {
                $this->addError('name', 'Необходимо ввести имя игрока');
            }
        }
        if (in_array('score', $attributeNames)) {
            if (!strlen($this->score)) {
                $this->addError('score', 'Необходимо ввести счёт');
            } else {
                $this->score = (integer)$this->score;
                if ($this->score < 0) {
                    $this->addError('score', 'Счёт не может быть отрицательным числом');
                }
            }
        }
        if (in_array('date', $attributeNames)) {
            if (!strlen($this->date)) {
                $this->addError('date', 'Необходимо ввести дату');
            }
            $this->date = is_numeric($this->date)? $this->date : strtotime($this->date);
        }
        return parent::validate($attributeNames);
    }
    
    /**
     * @param integer $group
     * @param integer $page 1..n
     * @param integer $perPage
     * @return GameResult[]
     */
    public static function findPage($group, $page, $perPage)
    {
        $page = $page - 1;
        $t = static::tableName();
        switch ($group) {
        case self::GROUP_DAY:
            $group = 'DAY(from_unixtime(date)), name';
        break;
        case self::GROUP_MONTH:
            $group = 'MONTH(from_unixtime(date)), name';
        break;
        case self::GROUP_WEEK:
            $group = 'WEEK(from_unixtime(date)), name';
        break;
        case self::GROUP_ALL:
        default:
            $group = 'name';
        }
        $q = <<<SQL
SELECT
    name,
    SUM(score) as score
FROM {$t}
GROUP BY {$group}
ORDER BY score DESC
LIMIT ?, ?
SQL;
        $startIndex = $page * $perPage;
        $st = static::db()->prepare($q);
        $st->bindValue(1, $startIndex, \PDO::PARAM_INT);
        $st->bindValue(2, $perPage, \PDO::PARAM_INT);
        if (!$st->execute()) {
            throw new \ErrorException($st->errorInfo()[2]);
        }
        
        $result = [];
        foreach ($st->fetchAll() as $row) {
            $model = new GameResult();
            $row['rank'] = ++$startIndex;
            $model->populateRow($row);
            $result[] = $model;
        }
        
        return $result;
    }
}
