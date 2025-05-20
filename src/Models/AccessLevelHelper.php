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
        'can_manage_own_items' => false,
        'can_manage_all_items' => false,
    ];

    $permissions = [
        'read' => ['can_read' => true],
        'read_create' => ['can_read' => true, 'can_create' => true],
        'read_create_manage_own' => ['can_read' => true, 'can_create' => true, 'can_manage_own_items' => true],
        'read_create_manage_all' => ['can_read' => true, 'can_create' => true, 'can_manage_all_items' => true],
    ];

    return array_merge($defaults, $permissions[$level] ?? []);
}

}
