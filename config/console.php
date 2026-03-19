<?php

$common = require __DIR__ . '/common.php';

return yii\helpers\ArrayHelper::merge($common, [
    'controllerNamespace' => 'app\\commands',
]);
