<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyInfo extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'worktime',
        'quote',
        'instagram_acc_link',
        'facebook_acc_link',
        'linkedin_acc_link',
        'show_instagram',
        'show_facebook',
        'show_linkedin',
    ];
}
