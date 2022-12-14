<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class RolesPermission extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles_permissions';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    

    public $sortable = [ 'id','role_id','permission_id','active', 'created_uid', 'updated_uid'];


    public function Role()
    {
        return $this->belongsTo(Role::class, 'role_id','id');
    }

    public function Permission()
    {
        return $this->belongsTo(Permission::class, 'permission_id','id');
    }
    
}
