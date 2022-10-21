<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Role extends Model
{
    use Sortable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    

    public $sortable = [ 'id','slug','name','description','active', 'created_uid', 'updated_uid'];

    public function RolesPermission()
    {
        return $this->belongsToMany(RolesPermission::class, 'id', 'role_id');
     
    }
}

