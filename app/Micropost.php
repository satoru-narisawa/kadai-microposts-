<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Micropost extends Model
{
    protected $fillable = ["content","user_id"];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    
    //ここから課題------------------------------------------------------------------------
        
    
    //この投稿をお気に入りにしているユーザーインスタンスの取得
    public function favorite_users()
    {
        return $this->belongsToMany(User::class,"favorites","micropost_id","user_id")->withTimestamps;
    }

}
