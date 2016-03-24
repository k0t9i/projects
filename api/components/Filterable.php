<?php

namespace api\components;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;

interface Filterable
{

    public function getFilterFields();
}
