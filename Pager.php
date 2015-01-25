<?php
namespace Chiliec\pager;

use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use Yii;

/**
 * Class Pager
 * @package Chiliec\pager
 * @author  Vladimir Babin <vovababin@gmail.com>
 */
class Pager extends Widget
{
    /**
     * Имя таблицы
     * @var string
     */
    public $tableName;

    /**
     * Значение текущего первичного ключа
     * @var int
     */
    public $id;

    /**
     * Имя столбца с первичным ключом
     * @var string
     */
    public $primaryKey = 'id';

    /**
     * Имя столбца с заголовком
     * @var string
     */
    public $title = 'title';

    /**
     * Время кеширования результатов в секундах
     * @var int
     */
    public $cacheTime = 3600;

    /**
     * Дополнительное SQL-условие
     * @var string
     */
    public $additionalСondition = '1=1';

    /**
     * Путь для формирования ссылки
     * @var string
     */
    public $path = 'action/view';

    /**
     * @var array
     */
    protected $closetLinks;


    /**
     * @inheritdoc
     */
    public function init()
    {        
        if ($this->tableName === null) {
            throw new InvalidConfigException('Table name is not configured!');
        }
        
        $this->closetLinks = Yii::$app->cache->get($this->tableName . 'closestLinks' . $this->id);
        
        if ($this->closetLinks === false) {
            $nextQuery = Yii::$app->db->createCommand(
                "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->primaryKey} > {$this->id} AND {$this->additionalСondition} LIMIT 1"
            );
            if (($next = $nextQuery->queryOne()) === false) {
                $next = Yii::$app->db->createCommand(
                    "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->additionalСondition} ORDER BY {$this->primaryKey} ASC LIMIT 1"
                )->queryOne();
            }
            $prevQuery = Yii::$app->db->createCommand(
                "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->primaryKey} < {$this->id} AND {$this->additionalСondition} ORDER BY {$this->primaryKey} DESC LIMIT 1"
            );
            if (($prev = $prevQuery->queryOne()) === false) {
                $prev = Yii::$app->db->createCommand(
                    "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->additionalСondition} ORDER BY {$this->primaryKey} DESC LIMIT 1"
                )->queryOne();
            }
            $this->closetLinks = ['next' => $next, 'prev' => $prev];
            Yii::$app->cache->set($this->tableName . 'closestLinks' . $this->id, $this->closetLinks, $this->cacheTime);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        return '
        <nav>
            <ul class="pager">
                <li class="previous">'
        . Html::a(
            '&larr; ' . $this->closetLinks['prev'][$this->title],
            [$this->path, $this->primaryKey => $this->closetLinks['prev'][$this->primaryKey]],
            ['class' => 'pull-left', 'rel' => 'prev']
        ) .
        '</li>
                <li class="next">'
        . Html::a(
            $this->closetLinks['next'][$this->title] . ' &rarr;',
            [$this->path, $this->primaryKey => $this->closetLinks['next'][$this->primaryKey]],
            ['class' => 'pull-right', 'rel' => 'next']
        ) .
        '</li>
            </ul>
        </nav>';
    }
}
