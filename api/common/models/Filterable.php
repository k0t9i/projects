<?php

namespace api\common\models;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;

interface Filterable {

    const SCENARIO_FILTER = 'scenario-filter';
    
    /**
     * @param ActiveQuery $query
     * @return ActiveDataProvider
     */
    public function search(ActiveQuery $query = null);
}
