<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Orchid\Screen\AsSource;

class Slider extends Model
{
    use HasFactory, AsSource;

    protected $table = 'sliders';
    protected $fillable = [
        'title',
        'description',
        'button_text',
        'button_link',
        'order',
        'color_text',
        'img',
    ];
}
