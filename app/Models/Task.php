<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    // Add Sortable Functionality
    use \Rutorika\Sortable\SortableTrait;
    use HasFactory;

    public function project()
    {
        return $this->belongsTo('App\Models\Project');
    }
}
