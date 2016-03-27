<?php

namespace api\components;

use Yii;
use yii\db\ActiveQuery;
use api\components\Filterable;
use yii\db\Schema;
use yii\db\QueryBuilder;

/**
 * Class for fuild filter query
 */
class FilterQueryBuilder
{

    const PARAM_PREFIX = ':fqp';
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

    /**
     * Array if valid filter fields
     * @see Filterable::getFilterFields
     * 
     * @var array|null
     */
    private static $_validFields;

    /**
     * Current model for filtering
     * 
     * @var yii\db\ActiveRecord
     */
    private static $_model;

    /**
     * Array of operators which agrument can be array
     * 
     * @var array
     */
    private static $_arrayOpMap = [
        self::OP_BETWEEN, self::OP_NOT_BETWEEN, self::OP_IN, self::OP_NOT_IN
    ];

    /**
     * Array of operators which agrument can be primitive types
     * 
     * @var array
     */
    private static $_simpleOpMap = [
        self::OP_IN, self::OP_NOT_IN, self::OP_LIKE, self::OP_NOT_LIKE,
        self::OP_LT, self::OP_LTE, self::OP_EQ, self::OP_GTE, self::OP_GT,
        self::OP_NOT_EQ
    ];

    /**
     * Array of logical operators
     * 
     * @var array
     */
    private static $_logicalOpMap = [
        self::L_OP_AND, self::L_OP_OR
    ];

    /**
     * Params for building condition
     * 
     * @var string 
     */
    private static $_params = [];

    /**
     * Global param counter
     * 
     * @var integer
     */
    private static $_counter = 0;

    /**
     * Build active query condition from array of filters
     * Filters should be in following form:
     * [
     *    [name, value, operator = "in", logicalOperator = "and"]
     * ]
     * Each level of nested arrays adds parentheses in the resulting condition
     * Example:
     * Array of following filters
     * [
     *     ["name", "value of name", "like"],
     *     [
     *         ["id", 10, ">", "or"]
     *         ["id", 1]
     *     ]
     * ]
     * will be converted to the condition
     * name LIKE "%value of name%" AND (id > 10 OR id = 1)
     * 
     * @param array $filters
     * @param ActiveQuery $query
     * @return ActiveQuery
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public static function build(array $filters, ActiveQuery $query)
    {
        static::$_model = new $query->modelClass();
        if (!(static::$_model instanceof Filterable)) {
            throw new \InvalidArgumentException('Model in ActiveQuery must implements Filterable');
        }
        static::$_validFields = static::$_model->getFilterFields();
        if (!is_array(static::$_validFields)) {
            throw new \LogicException(static::$_model->className() . '::getFilterFields must return an array');
        }

        $extraFields = array_diff(static::$_validFields, static::$_model->attributes());
        if ($extraFields) {
            throw new \LogicException(static::$_model->className() . '::getFilterFields return extra fields: ' . implode(', ', $extraFields));
        }

        static::$_params = [];
        static::$_counter = 0;

        $condition = static::parse($filters)[0];
        $query->andWhere($condition);
        $query->params = array_merge($query->params, static::$_params);

        return $query;
    }

    /**
     * Parse filters recursively
     * 
     * @param array $filters
     * @return array
     */
    private static function parse(array $filters)
    {
        if ($filters) {
            if (is_array($filters[0])) {
                $condition = '(';
                foreach ($filters as $filter) {
                    list($part, $operator) = static::parse($filter);
                    $condition .= $part;
                    if (($filter) !== end($filters)) {
                        $condition .= ' ' . strtoupper($operator) . ' ';
                    }
                }
                $condition .= ')';
                return [
                    $condition, $operator
                ];
            } else {
                $filter = static::prepareFilter($filters);
                $operator = array_pop($filter);
                return [
                    static::buildCondition($filter), $operator
                ];
            }
        }
    }

    /**
     * Build part of condition
     * 
     * @param array $condition
     * @return string
     */
    private static function buildCondition($condition)
    {
        $builder = Yii::$app->db->getQueryBuilder();
        
        $params = [$condition[1]];
        $rawRet = $builder->buildCondition($condition, $params);
        
        $keys = preg_grep('/^' . preg_quote(QueryBuilder::PARAM_PREFIX) . '[0-9]+$/', array_keys($params));

        foreach ($keys as $key) {
            $currentParam = static::PARAM_PREFIX . static::$_counter++;
            static::$_params[$currentParam] = $params[$key];
            $rawRet = str_replace($key, $currentParam, $rawRet);
        }
        return $rawRet;
    }

    /**
     * Prepare filter item:
     * checks for required, inserts default values, casting values to correct db type
     * 
     * @param array $item
     * @return array
     * @throws \InvalidArgumentException
     */
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

        $item[2] = strtolower($item[2]);
        $item[3] = strtolower($item[3]);

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

        switch ($item[2]) {
            case static::OP_BETWEEN:
            case static::OP_NOT_BETWEEN:
                if (!is_array($item[1]) || count($item[1]) < 2) {
                    throw new \InvalidArgumentException('Value for "' . $item[2] . '" operator must be an array 2 length');
                }
                $condition = [$item[2], $item[0], $item[1][0], $item[1][1], $item[3]];
                break;
            case static::OP_LIKE:
            case static::OP_NOT_LIKE:
                $types = static::getTextOrStringDbTypes();
                $column = static::$_model->getTableSchema()->getColumn($item[0]);
                if (!array_key_exists($column->dbType, $types)) {
                    throw new \InvalidArgumentException('Operators LIKE and NOT LIKE can be uses only for string or text types');
                }
            //break skipped by design
            default:
                $condition = [$item[2], $item[0], $item[1], $item[3]];
        }

        return $condition;
    }

    /**
     * Casting value to correct db type
     * @see yii\db\Column::dbTypecast
     * 
     * @param string $field
     * @param mixed $value
     * @return mixed
     */
    private static function typecast($field, $value)
    {
        $column = static::$_model->getTableSchema()->getColumn($field);
        if ($value && $column) {
            $value = $column->dbTypecast($value);
        }

        return $value;
    }

    /**
     * Return string or text db types
     * 
     * @return array
     */
    private static function getTextOrStringDbTypes()
    {
        $types = static::$_model->getDb()->getSchema()->typeMap;
        $ret = [];

        foreach ($types as $dbType => $type) {
            if (in_array($type, [Schema::TYPE_STRING, Schema::TYPE_TEXT])) {
                $ret[$dbType] = $dbType;
            }
        }

        return $ret;
    }

}
