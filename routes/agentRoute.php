<?php

use Illuminate\Support\Facades\Route;


Route::prefix('agent')->name('agent.')->middleware(['maintenance','addon_status'])->group(function () {

    Route::get('/dd/login',    function(){
        return 'agent login';
    })->name('login');
});