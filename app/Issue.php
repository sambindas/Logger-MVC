<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;

class Issue extends Model
{
    use HasApiTokens;
    protected $table = 'issue';
    public $timestamps = false;
    protected $primaryKey = 'issue_id';
}
