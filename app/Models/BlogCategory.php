<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class BlogCategory
 * @package App\Models
 *
 * @property-read BlogCategory $parentCategory
 * @property-read sting        $parentTitle
 */
class BlogCategory extends Model
{
    use SoftDeletes;
    /**
     * root ID of the category
     */
    const ROOT = 1;

    protected $fillable
        = [
            'title',
            'slug',
            'parent_id',
            'description',
        ];

    /**
     * GET parent category
     *
     * @return BlogCategory
     */
    public function parentCategory()
    {
        return $this->belongsTo(BlogCategory::class, 'parent_id', 'id');
    }

    /**
     * Simple accessor
     *
     * @return string
     */
    public function getParentTitleAttribute()
    {
        $title = $this->parentCategory->title
            ?? ($this->isRoot()
                ?  'Root'
                : '???');
        return $title;
    }

    /**
     * Is this object ROOT ???? (value=1)
     *
     * @return bool
     */
    public function isRoot()
    {
        return $this->id === BlogCategory::ROOT;
    }

//    /**
//     * Get value and set it uppercase
//     *
//     * @param $valueFromObject
//     *
//     * @return bool|false|string|string[]|null
//     */
//    public function getTitleAttribute($valueFromObject)
//    {
//        return \Str::upper($valueFromObject);
//    }
//
//    /**
//     * Get value and set it to lowercase
//     *
//     * @param $incomingValue
//     */
//    public function setTitleAttributes($incomingValue)
//    {
//        $this->attributes['title'] = \Str::lower($incomingValue);
//    }
}
