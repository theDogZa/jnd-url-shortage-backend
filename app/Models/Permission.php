<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Permission extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'Permissions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    

    public $sortable = [ 'id','slug','name','description','group_code','active', 'created_uid', 'updated_uid'];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'roles_permissions')->where('roles_permissions.active', 1);
    }
}

