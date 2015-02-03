<?php
/**
 * @link https://github.com/Chiliec/yii2-pager
 * @author Vladimir Babin <vovababin@gmail.com>
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace chiliec\pager;

use yii\base\Widget;
use yii\helpers\Html;
use yii\base\InvalidConfigException;
use Yii;

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
    public $currentId;

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
     * @var array the HTML attributes for the nav parent element.
     * @see \yii\helpers\Html::renderTagAttributes() for details on how attributes are being rendered.
     */
    public $navOptions = [];

    /**
     * @var array the HTML attributes for the UL element. 'encode' => false will be added automatically.
     */
    public $listOptions = ['class' => 'pager'];

    /**
     * @var array the HTML attributes for previous link.
     */
    public $prevOptions = ['class' => 'pull-left', 'rel' => 'prev'];

    /**
     * @var array the HTML attributes for next link.
     */
    public $nextOptions = ['class' => 'pull-right', 'rel' => 'next'];

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
        if ($this->currentId === null) {
            throw new InvalidConfigException('Current Id is not configured!');
        }
        
        $cacheKey = 'closestLinks' . $this->tableName . $this->currentId;
        if (false === $this->closetLinks = Yii::$app->cache->get($cacheKey)) {
            $nextQuery = Yii::$app->db->createCommand(
                "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->primaryKey} > {$this->currentId} AND {$this->additionalСondition} LIMIT 1"
            );
            if (($next = $nextQuery->queryOne()) === false) {
                $next = Yii::$app->db->createCommand(
                    "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->additionalСondition} ORDER BY {$this->primaryKey} ASC LIMIT 1"
                )->queryOne();
            }
            $prevQuery = Yii::$app->db->createCommand(
                "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->primaryKey} < {$this->currentId} AND {$this->additionalСondition} ORDER BY {$this->primaryKey} DESC LIMIT 1"
            );
            if (($prev = $prevQuery->queryOne()) === false) {
                $prev = Yii::$app->db->createCommand(
                    "SELECT {$this->primaryKey},{$this->title} FROM {$this->tableName} WHERE {$this->additionalСondition} ORDER BY {$this->primaryKey} DESC LIMIT 1"
                )->queryOne();
            }
            $this->closetLinks = ['next' => $next, 'prev' => $prev];
            Yii::$app->cache->set($cacheKey, $this->closetLinks, $this->cacheTime);
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->listOptions = array_merge(['encode' => false], $this->listOptions);
        $content  = Html::beginTag('nav', $this->navOptions);
        $content .= Html::ul([
            Html::a('&larr; ' . $this->closetLinks['prev'][$this->title],
                [$this->path, $this->primaryKey => $this->closetLinks['prev'][$this->primaryKey]],
                $this->prevOptions
            ),
            Html::a($this->closetLinks['next'][$this->title] . ' &rarr;',
                [$this->path, $this->primaryKey => $this->closetLinks['next'][$this->primaryKey]],
                $this->nextOptions
            )
        ], $this->listOptions);
        $content .= Html::endTag('nav');
        return $content;
    }
}
