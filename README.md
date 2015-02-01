Pager Widget for Yii 2 [![Latest Stable Version](https://poser.pugx.org/chiliec/yii2-pager/v/stable.svg)](https://packagist.org/packages/chiliec/yii2-pager) [![Total Downloads](https://poser.pugx.org/chiliec/yii2-pager/downloads.svg)](https://packagist.org/packages/chiliec/yii2-pager) [![Latest Unstable Version](https://poser.pugx.org/chiliec/yii2-pager/v/unstable.svg)](https://packagist.org/packages/chiliec/yii2-pager) [![License](https://poser.pugx.org/chiliec/yii2-pager/license.svg)](https://packagist.org/packages/chiliec/yii2-pager)
======================

Quick previous and next links for simple pagination implementations with light markup and styles. It's great for simple sites like blogs or magazines. Based on [Bootstrap Pager](http://getbootstrap.com/components/#pagination-pager).

Usage
-----

```php
<?php
    echo \Chiliec\pager\Pager::widget([
        'tableName' => $model->tableName(),
        'currentId' => $model->id, // current primary key
        'path' => 'story/view', // path for link
        // optional fields in most cases
        'primaryKey' => 'id', // name of primary key column
        'title' => 'title', // name of title column
        'additionalСondition' => 'published = 1', // additional SQL-condition
        'cacheTime' => 3600, // time for cache results
        'navOptions' => [],
        'listOptions' => ['class' => 'pager'],
        'prevOptions' => ['class' => 'pull-left', 'rel' => 'prev'],
        'nextOptions' => ['class' => 'pull-right', 'rel' => 'next'],
    ]); 
?>
```

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist Chiliec/yii2-pager "~2.0"
```

or add

```
"Chiliec/yii2-pager": "~2.0"
```

to the require section of your `composer.json` file.
