<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;    
use Database\Factories\UserFactory;              
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;         
use Illuminate\Support\Str;                      
use Illuminate\Database\Eloquent\Relations\HasOne; 
use Illuminate\Support\Facades\Storage; 
use Illuminate\Database\Eloquent\SoftDeletes;


#[Fillable(['name', 'email', 'password', 'user_type', 'gender', 'blocked', 'photo_url'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */

    /**
     * 【数据格式翻译官】
     * 数据库里存的数据有时很死板（只是数字或字母），这里负责把它们翻译成程序好理解的格式。
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime', // 把“邮箱验证时间”翻译成标准的“日期时间”格式
            'password' => 'hashed',            // 把“密码”进行加密处理，存取时都确保是安全的乱码
            'blocked' => 'boolean',           // 把“是否被封禁”翻译成“是或否”（真/假）
                                               // 数据库里可能存的是 1 或 0，这里自动转换成网页好用的“勾选/不勾选”状态
        ];
    }

    //生成名字缩写
    public function initials(): string
    {
        /*
        return Str::of($this->name)         // 拿到这个用户的名字
            ->explode(' ')                  // 遇到空格就切开（变成 "Zhang" 和 "San"）
            ->take(2)                       // 只取前两部分（防止有人名字太长）
            ->map(fn ($word) => Str::substr($word, 0, 1)) // 取每部分的第一位字母（"Z" 和 "S"）
            ->implode('');                  // 重新拼接在一起，结果就是 "ZS"
        */

        //获取用户名字，如果有空格就将之前的字保存一个词，直到结束
        $words = Str::of($this->name)->explode(' ');
        //提取第一个词的第一个字母和最后一个词的第一个字母，拼在一起
        $abbreviation = Str::substr($words->first(), 0, 1) . Str::substr($words->last(), 0, 1);

        // 最后把缩写转换成大写字母，并且返回这个结果
        return Str::upper($abbreviation);
        
    }

    /**
     * 【小功能：获取头像的完整网络地址】
     * 网页需要一个能点的链接才能显示图片，这个功能负责把链接找出来。
     */
    public function getPhotoFullUrlAttribute()
    {
        /*
        // 检查：如果用户的photo_url有照片名字，并且再 "photos" 有对应名字的照片
        if ($this->photo_url && Storage::disk('public')->exists("photos/{$this->photo_url}")) {
            //返回对应用户的照片的完整网址 
            //就合成一个完整的网址（比如 http://网页.com/storage/photos/用户图片.jpg）
            return asset("storage/photos/{$this->photo_url}");
        } else {
            // 如果没照片，或者照片找不到了，就使用默认图片
            return asset("storage/photos/anonymous.png");
        }
        */

        // 检查：如果用户的photo_url有照片名字，并且再 "photos" 有对应名字的照片
        $photoPath = $this->normalizedPhotoPath();

        if ($photoPath && Storage::disk('public')->exists($photoPath)) {
            return route('user.photo', ['path' => $photoPath], false);
        }

        return asset('storage/photos/anonymous.png');
    }

    public function hasUploadedPhoto(): bool
    {
        $photoPath = $this->normalizedPhotoPath();

        return (bool) ($photoPath && Storage::disk('public')->exists($photoPath));
    }

    public function normalizedPhotoPath(): ?string
    {
        if (! $this->photo_url) {
            return null;
        }

        return Str::startsWith($this->photo_url, 'photos/')
            ? $this->photo_url
            : "photos/{$this->photo_url}";
    }

    /**
     * 【关系绑定：顾客专属档案】
     * 连线红绳：规定这个用户（user）可以对应（HasOne）“一个顾客（customer）”的信息。
     */
    public function customer(): HasOne
    {
        // 根据提供的用户ID获取对应的客户资料（id（user表）->id（customer表））
        return $this->hasOne(Customer::class, 'id');
    }


    public function isCustomer(): bool
    {
        // 检查这个用户的user_type是不是C，如果是，返回true；否则返回false。
        return $this->user_type === 'C';
    }

  
    public function isStaff(): bool
    {
        return $this->user_type === 'F';
    }

     
    public function isAdmin(): bool
    {
        return $this->user_type === 'A';
    }
}
