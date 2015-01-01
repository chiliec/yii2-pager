Pager Widget for Yii 2
======================

Quick previous and next links for simple pagination implementations with light markup and styles. It's great for simple sites like blogs or magazines. Based on [Bootstrap Pager](http://getbootstrap.com/components/#pagination-pager).

Usage
-----

```php
<?php
use Chiliec\Pager;

Pager::widget([
        'tableName' => $model->tableName(), // table name
        'id' => $model->id, // current primary key
        'primaryKey' => 'id', // name of primary key column
        'title' => 'title', // name of title column
        'path' => 'story/view', // path for link
        'additionalСondition' => 'published = 1', // additional SQL-condition
        'cacheTime' => 3600, // time for cache results
    ]); ?>
```

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist Chiliec/yii2-pager "*"
```

or add

```
"Chiliec/yii2-pager": "*"
```

to the require section of your `composer.json` file.