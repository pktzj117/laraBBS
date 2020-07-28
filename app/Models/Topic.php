<?php

namespace App\Models;

class Topic extends Model
{
    protected $fillable = [
        'title', 'body', 'category_id', 'excerpt', 'slug'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWithOrder($query, $order)
    {

        //不同的排序,是哟个不同的数据读取逻辑
        switch ($order) {
            case 'recent':
                $query->recent();
                break;

            default:
                $query->recentReplied();
                break;
        }

        //预加载防止 N+1问题
        return $query->with('user', 'category');
    }

    public function scopeRecentReplied($query)
    {
        //当话题又新回复时, 我们将编写逻辑更新话题模型的 reply_cout 属性
        //此时会自动触发框架对数据模型 updated_at 时间戳的更新
        return $query->orderBy('updated_at', 'desc');
    }

    public function scopeRecent($query)
    {
        // 安装创建时间排序
        return $query->orderBy('created_at', 'desc');
    }
}
