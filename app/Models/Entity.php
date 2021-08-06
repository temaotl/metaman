<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'entityid',
        'file',
        'name_en',
        'name_cs',
        'description_en',
        'description_cs',
        'edugain',
        'hfd',
        'rs',
        'cocov1',
        'sirtfi',
        'metadata',
    ];

    protected $casts = [
        'edugain' => 'boolean',
        'hfd' => 'boolean',
        'rs' => 'boolean',
        'cocov1' => 'boolean',
        'sirtfi' => 'boolean',
        'approved' => 'boolean',
        'active' => 'boolean',
    ];

    public function operators()
    {
        return $this
            ->belongsToMany('App\Models\User')
            ->orderBy('name')
            ->withTimestamps();
    }

    public function federations()
    {
        return $this->belongsToMany('App\Models\Federation', 'membership')
            ->using('App\Models\Membership')
            ->withPivot('approved')
            ->wherePivot('approved', true)
            ->withTimestamps();
    }

    public function federationsRequested()
    {
        return $this->belongsToMany('App\Models\Federation', 'membership')
            ->using('App\Models\Membership')
            ->withPivot('approved')
            ->wherePivot('approved', false)
            ->withTimestamps();
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Models\Group');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if($user->admin)
        {
            return $query->withTrashed();
        }
    }

    public function scopeSearch($query, string $search = null)
    {
        $query
            ->where('entityid', 'like', "%$search%")
            ->orwhere('name_en', 'like', "%$search%")
            ->orWhere('name_cs', 'like', "%$search%")
            ->orWhere('description_en', 'like', "%$search%")
            ->orWhere('description_cs', 'like', "%$search%");
    }

    public function getKindAttribute()
    {
        return $this->type === 'idp' ? 'IdP' : 'SP';
    }
}
