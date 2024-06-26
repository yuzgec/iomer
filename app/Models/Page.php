<?php

namespace App\Models;

use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Image\Manipulations;

use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;

use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;


class Page extends Model implements HasMedia,TranslatableContract,Viewable
{
    use HasFactory,SoftDeletes,InteractsWithMedia,Translatable,InteractsWithViews;

    public $translatedAttributes = ['title', 'slug','short','desc','seo1', 'seo2', 'seo3'];

    protected $guarded = [];
    protected $table = 'page';

    public function getCategory(){
        return $this->belongsTo('App\Models\PageCategory', 'category');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('watermark')->width(750)->height(750)
        ->watermark(public_path('/iomer-logo.png'))
        ->watermarkPosition(Manipulations::POSITION_CENTER)
        ->watermarkHeight(70, Manipulations::UNIT_PERCENT)
        ->watermarkWidth(70, Manipulations::UNIT_PERCENT)
        ->watermarkOpacity(45)
        ->keepOriginalImageFormat();
        $this->addMediaConversion('img')->width(1000)->nonOptimized();
        $this->addMediaConversion('thumb')->width(500)->nonOptimized();
        $this->addMediaConversion('small')->width(150)->nonOptimized();
    }


}
