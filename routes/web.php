<?php

Route::get('/',"MicropostsController@index");

//アカウント新規登録
Route::get("signup","Auth\RegisterController@showRegistrationForm")->name("signup.get");
Route::post("signup","Auth\RegisterController@register")->name("signup.post");

//ログイン認証
Route::get("login","Auth\LoginController@showLoginForm")->name("login");
Route::post("login","Auth\LoginController@login")->name("login.post");
Route::get("logout","Auth\LoginController@logout")->name("logout.get");

//ユーザー機能
Route::group(["middleware" => ["auth"]],function(){
    Route::resource("users","UsersController",["only" => ["index","show"]]);
    
    //prefix(頭につける)　=> users/{id}を
    Route::group(["prefix" => "users/{id}"],function(){
        //下の例で言えばURLは　user/{id}/followとなる
        Route::post("follow","UserFollowController@store")->name("user.follow");
        Route::delete("unfollow","UserFollowController@destroy")->name("user.unfollow");
        Route::get("followings","UsersController@followings")->name("users.followings");
        Route::get("followers","UsersController@followers")->name("users.followers");
        
        //favorite一覧表示のためのルーティング
        
        Route::get('favorites', 'UsersController@favorites')->name('users.favorites');
    });
    
    
    //faborite,unfavariteするためのルーティング
    
    Route::group(['prefix' => 'microposts/{id}'], function () {
        Route::post('favorite', 'FavoritesController@store')->name('favorites.favorite');
        Route::delete('unfavorite', 'FavoritesController@destroy')->name('favorites.unfavorite');
    });

    
    Route::resource("microposts","MicropostsController",["only" => ["store","destroy"]]);

});


