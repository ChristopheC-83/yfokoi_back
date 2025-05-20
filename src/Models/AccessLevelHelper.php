<?php


declare(strict_types=1);

namespace Src\Models;

class AccessLevelHelper
{
    public static function mapLevelToPermissions(string $level): array
{
    $defaults = [
        'can_read' => false,
        'can_create' => false,
        'can_update_own_item' => false,
        'can_update_all_items' => false,
        'can_delete_own_item' => false,
        'can_delete_all_items' => false,
    ];

    $permissions = [
        'read' => [
            'can_read' => true,
        ],
        'read_create' => [
            'can_read' => true,
            'can_create' => true,
        ],
        'read_create_own_modify' => [
            'can_read' => true,
            'can_create' => true,
            'can_update_own_item' => true,
        ],
        'read_create_own_modify_delete' => [
            'can_read' => true,
            'can_create' => true,
            'can_update_own_item' => true,
            'can_delete_own_item' => true,
        ],
        'read_create_all_modify' => [
            'can_read' => true,
            'can_create' => true,
            'can_update_all_items' => true,
        ],
        'read_create_all_modify_delete' => [
            'can_read' => true,
            'can_create' => true,
            'can_update_all_items' => true,
            'can_delete_all_items' => true,
        ],
    ];

    return array_merge($defaults, $permissions[$level] ?? []);
}

}
