<?php


use App\Orchid\Screens\SliderScreen;
use App\Orchid\Screens\User\UserEditScreen;
use Illuminate\Support\Facades\Route;
use Tabuna\Breadcrumbs\Trail;

Route::screen('users/{user}/edit', UserEditScreen::class)->name('platform.users.edit');
Route::screen('slider', SliderScreen::class)->name('platform.slider');
Route::screen('slider', SliderScreen::class)
    ->name('platform.slider')
    ->breadcrumbs(function (Trail $trail){
        return $trail
            ->parent('platform.index')
            ->push('Slider');
    });

