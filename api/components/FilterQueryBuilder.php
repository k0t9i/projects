<?php

namespace api\components;

use yii\db\Query;
use yii\db\ActiveQuery;
use api\components\Filterable;

class FilterQueryBuilder
{

    const OP_IN = 'in';
    const OP_NOT_IN = 'not in';
    const OP_LIKE = 'like';
    const OP_NOT_LIKE = 'not like';
    const OP_BETWEEN = 'between';
    const OP_NOT_BETWEEN = 'not between';
    const OP_LT = '<';
    const OP_LTE = '<=';
    const OP_EQ = '=';
    const OP_GTE = '>=';
    const OP_GT = '>';
    const OP_NOT_EQ = '<>';
    const L_OP_OR = 'or';
    const L_OP_AND = 'and';

    private static $_validFields;
    private static $_model;
    private static $_arrayOpMap = [
        self::OP_BETWEEN, self::OP_NOT_BETWEEN, self::OP_IN, self::OP_NOT_IN
    ];
    private static $_simpleOpMap = [
        self::OP_IN, self::OP_NOT_IN, self::OP_LIKE, self::OP_NOT_LIKE,
        self::OP_LT, self::OP_LTE, self::OP_EQ, self::OP_GTE, self::OP_GT,
        self::OP_NOT_EQ
    ];
    private static $_logicalOpMap = [
        self::L_OP_AND, self::L_OP_OR
    ];

    public static function build(array $filters, ActiveQuery $query)
    {
        static::$_model = new $query->modelClass();
        if (!(static::$_model instanceof Filterable)) {
            throw new \InvalidArgumentException("Model in ActiveQuery must implements Filterable");
        }
        static::$_validFields = static::$_model->getFilterFields();
        if (!is_array(static::$_validFields)) {
            static::$_validFields = [];
        }
        static::$_validFields = array_intersect(static::$_model->attributes(), static::$_validFields);
        static::parse($filters, $query);
        return $query;
    }

    private static function parse(array $filters, Query $query, $operator = null)
    {
        if ($filters) {
            if (is_array($filters[0])) {
                $subQuery = new Query();
                $oldOperator = $operator;
                foreach ($filters as $filter) {
                    $operator = static::parse($filter, $subQuery, $operator);
                }
                static::addCondition($query, $subQuery->where, $oldOperator);
            } else {
                $filter = static::prepareFilter($filters);
                $ret = array_pop($filter);
                static::addCondition($query, $filter, $operator);
                return $ret;
            }
        }
    }

    private static function addCondition(Query $query, $condition, $operator)
    {
        if (trim($operator) == static::L_OP_OR) {
            $query->orWhere($condition);
        } else {
            $query->andWhere($condition);
        }
    }

    private static function prepareFilter(array $item)
    {
        if (!isset($item[0]) && !isset($item[1])) {
            throw new \InvalidArgumentException('0 and 1 elements of item array are required');
        }

        if (!in_array($item[0], static::$_validFields)) {
            throw new \InvalidArgumentException($item[0] . ' not in valid fields list (' . implode(', ', static::$_validFields) . ')');
        }

        if (!isset($item[2])) {
            $item[2] = static::OP_IN;
        }
        if (!isset($item[3])) {
            $item[3] = static::L_OP_AND;
        }

        foreach ($item as $k => $v) {
            if ($k == 1) {
                continue;
            }
            $item[$k] = strtolower($v);
        }

        $operands = is_array($item[1]) ? static::$_arrayOpMap : static::$_simpleOpMap;
        if (!in_array($item[2], $operands)) {
            throw new \InvalidArgumentException(implode(', ', $operands));
        }
        if (!in_array($item[3], static::$_logicalOpMap)) {
            throw new \InvalidArgumentException(implode(', ', static::$_logicalOpMap));
        }

        if (is_array($item[1])) {
            foreach ($item[1] as $k => $v) {
                $item[1][$k] = static::typecast($item[0], $v);
            }
        } else {
            $item[1] = static::typecast($item[0], $item[1]);
        }

        if (in_array($item[2], [static::OP_BETWEEN, static::OP_NOT_BETWEEN])) {
            if (!is_array($item[1]) || count($item[1]) < 2) {
                throw new \InvalidArgumentException('Value for "' . $item[2] . '" operator must be an array 2 length');
            }
            $condition = [$item[2], $item[0], $item[1][0], $item[1][1], $item[3]];
        } else {
            $condition = [$item[2], $item[0], $item[1], $item[3]];
        }

        return $condition;
    }

    private static function typecast($field, $value)
    {
        $column = static::$_model->getTableSchema()->getColumn($field);
        if ($value && $column) {
            $value = $column->dbTypecast($value);
        }

        return $value;
    }

}
