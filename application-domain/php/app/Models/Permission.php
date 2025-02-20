<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{
    /**
     * Default Permissions of the Application.
     */
    public static function defaultPermissions()
    {
        return [
            'view_backend',
            'edit_settings',
            'view_logs',

            'view_users',
            'add_users',
            'edit_users',
            'edit_users_permissions',
            'delete_users',
            'restore_users',
            'block_users',

            'view_roles',
            'add_roles',
            'edit_roles',
            'delete_roles',
            'restore_roles',
            'manage_roles',
            'manage_operations',
            'manage_resources',

            'view_backups',
            'add_backups',
            'create_backups',
            'download_backups',
            'delete_backups',
            'manage_users',
            'manage_comments',
            'manage_posts',
            'create_reports'
        ];
    }

    /**
     * Name should be lowercase.
     *
     * @param  string  $value  Name value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }
}
