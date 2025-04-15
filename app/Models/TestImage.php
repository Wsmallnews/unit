<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\MediableInterface;
use Plank\Mediable\Mediable;

class TestImage extends Model implements MediableInterface
{
    use SoftDeletes;
    use Mediable;
    
    protected $table = 'sn_test_images';

    protected $guarded = [];
    
    
}
