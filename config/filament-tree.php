<?php

return [
    /**
     * Tree model fields
     */
    'column_name' => [
        'order' => 'order',
        'parent' => 'parent_id',
        'title' => 'name',
    ],
    /**
     * Tree model default parent key
     */
    'default_parent_id' => -1,
    /**
     * Tree model default children key name
     */
    'default_children_key_name' => 'children',
];
