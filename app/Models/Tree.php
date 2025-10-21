<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Tree extends Model
{
    use NodeTrait;

    protected $table = 'trees';

    protected $casts = [
    ];

    protected $guarded = [];

    public function getScopeAttributes(): array
    {
        return ['team_id'];
    }


    public static function getTreeLabelAttribute(): string
    {
        return 'name';
    }
}
