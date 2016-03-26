<?php

namespace api\components;

/**
 * Interface for model which can be filtered
 * @see api\components\FilterQueryBuilder
 */
interface Filterable
{

    /**
     * Get array of field names which can used for filtering
     * 
     * @return array
     */
    public function getFilterFields();
}
