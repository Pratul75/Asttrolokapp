<?php

namespace App\Models;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class UserBankSpecification extends Model implements TranslatableContract
{
    use Translatable;

    protected $table = "user_bank_specifications";
    public $timestamps = false;

    protected $guarded = ['id'];

    public $translatedAttributes = ['name'];

    public function getNameAttribute()
    {
        return getTranslateAttributeValue($this, 'name');
    }
}
