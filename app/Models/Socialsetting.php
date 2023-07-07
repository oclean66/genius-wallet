<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Socialsetting extends Model
{
    protected $fillable = ['fclient_id','fclient_secret','website_url','fredirect','gclient_id','gclient_secret','gredirect','social_icons','social_urls','facebook_check','google_check'];
    public $timestamps = false;
}

