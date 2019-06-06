<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
    
    //１対多の関係　Userインスタンス（レコード）はたくさんのMicropost（クラスのインスタンス）を持っている
    public function microposts()
    {
        return $this->hasMany(Micropost::class);
    }
 
 
    
    //ユーザー　が　フォローしているuser達を取得
    public function followings()
    {
        //ユーザーインスタンス->に関係する中間テーブルのカラム取得（第１引数　クラス名　第２引数　中間テーブル名　
        //第３引数　中間テーブルにある自分を示すIDカラム名　第４引数　中間テーブルにある関係先を示すIDカラム名)
        // ->created_at update_at　を保存するためのメソッド　タイムスタンプ管理できるようになる
        return $this->belongsToMany(User::class, "user_follow", "user_id", "follow_id")->withTimestamps();
    }
    
    //ユーザー　を　フォローしているuser達を取得
    public function followers()
    {
        //ユーザーインスタンス->に関係する中間テーブルのカラム取得(第１引数　クラス名　第２引数　中間テーブル名
        //第３引数　中間テーブルにある自分を示すIDカラム名　第４引数　中間テーブルにある関係先を示すIDカラム名）
        //　->created_at update_at　を保存するためのメソッド　タイムスタンプ管理できるようになる
        return $this->belongsToMany(User::class, "user_follow", "follow_id", "user_id")->withTimestamps();
    }
    
    
    public function follow($userId)
    {
        //既にフォローしているかの確認
        $exist = $this->is_following($userId);
        //相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if($exist || $its_me){
            //既にフォローしていれば何もしない
            return false;
        }
        else {
            //未フォローであればフォローする
            //インスタンスが->中間テーブルのカラムを取得->attachでテーブルに保存($userId(中間テーブルのレコード内容))
            $this->followings()->attach($userId);
            return true;
        }
    }

    public function unfollow($userId)
    {
        //既にフォローしているかの確認
        $exist = $this->is_following($userId);
        //相手が自分自身ではないかの確認
        $its_me = $this->id == $userId;
        
        if($exist && !$its_me) {
            //既にフォローしていればフォロー外す
            //インスタンスが->中間テーブルのカラムを取得->datachでテーブルから削除
            $this->followings()->detach($userId);
            return true;
        }
        else{
            //未フォローであれば何もしない
            return false;
        }
    }
    
    //フォローしているかの確認
    public function is_following($userId)
    {
        //$this=Userモデルのインスタンス->フォローしているユーザーを取得
        //->where("フォローIDが（前のメソッドで取得してる）"＝"$userId")なら->レコードが存在するか確認する（フォローしてるか確認）
        return $this->followings()->where("follow_id",$userId)->exists();
    }
    
    public function feed_microposts()
    {
        //ユーザーが->フォローしているユーザーを取得->pluck(引数のテーブル.のカラムのみを取り出すここではusersテーブルのidカラムを取得)
        // ->toArray()は配列にするメソッド（つまりフォローしているユーザーのIDカラムのデータを配列にして変数へ代入してる）
        $follow_user_ids = $this->followings()->pluck("users.id")->toArray();
        //ついでに自分のidも配列に追加
        $follow_user_ids[] = $this->id;
        //micropostsテーブルのuser_idカラムの中で $follow_user_idsが含まれているものをリターンしている。
        //whereInは（第１引数の中に含まれる（データベースのカラム）　第２引数をセレクトする
        return Micropost::whereIn("user_id",$follow_user_ids);
    }
    
    
    //ここから課題！ーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーーー
    
    
    
    
    //ユーザーがお気に入りにしているMicropostインスタンスを取得
    public function favorites()
    {
        return $this->belongsToMany(Micropost::class,"favorites","user_id","micropost_id")->withTimestamps();
    }
    
    //投稿をお気に入りにする
    public function favorite($micropostId)
    {
        //すでにお気に入りにしているか確認(していればtrue)
        $exist = $this->favoriting($micropostId);

        if($exist)
        {
            return false;
        }
        else
        {
            //ユーザーインスタンスから->お気に入りしているMicropostインスタンスを取得し->中間テーブルへ保存(引数は相手のid)
            $this->favorites()->attach($micropostId);
            return true;
        }
    }
    
    //お気に入りから外す
    public function unfavorite($micropostId)
    {
        //すでにお気に入りしているか確認(していればtrue)
        $exist = $this->favoriting($micropostId);

        if($exist)
        {
            $this->favorites()->detach($micropostId);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    //お気に入りにしているかの確認
    public function favoriting($micropostId)
    {
        //userインスタンスがお気に入りにしているMicropostインスタンスを取得->micropost_id=$micropostIdなら
        //->確認する（同一ならtrue）
        return $this->favorites()->where("micropost_id",$micropostId)->exists();
    }


}

