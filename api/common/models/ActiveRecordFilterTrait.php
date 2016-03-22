<?php

namespace api\common\models;

use yii\db\ActiveQuery;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;

trait ActiveRecordFilterTrait {
    
    public function init()
    {
        if (!($this instanceof ActiveRecord)){
            throw new \LogicException('Class uses ActiveRecordFilterTrait must be instance of \yii\db\ActiveRecord');
        }
        if (!($this instanceof Filterable)){
            throw new \LogicException('Class uses ActiveRecordFilterTrait must implements Filterable interface');
        }
        parent::init();
    }
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();

        if (!isset($scenarios[static::SCENARIO_FILTER])) {
            $scenarios[static::SCENARIO_FILTER] = $this->filterField();
        }

        return $scenarios;
    }

    public function search(ActiveQuery $query = null)
    {      
        if (is_null($query)) {
            $query = static::find();
        }
        $this->prepareFilterQuery($query);
        return new ActiveDataProvider([
            'query' => $query
        ]);
    }

    abstract protected function filterField();
    
    protected function prepareFilterQuery(ActiveQuery $query)
    {
        foreach ($this->safeAttributes() as $attribute) {
            $column = static::getTableSchema()->getColumn($attribute);
            if (isset($this->{$attribute}) && $column) {
                $value = $column->dbTypecast($this->{$attribute});
                $query->andWhere([static::tableName() . '.' . $attribute => $value]);
            }
        }
    }
    
}
