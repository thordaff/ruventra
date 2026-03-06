<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NavItem extends Model
{
    protected $fillable = ['title', 'href', 'icon', 'parent_id', 'section', 'is_external', 'order'];

    protected $casts = [
        'is_external' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'nav_item_role');
    }

    public function children()
    {
        return $this->hasMany(NavItem::class, 'parent_id')->orderBy('order');
    }

    public function parent()
    {
        return $this->belongsTo(NavItem::class, 'parent_id');
    }
}
