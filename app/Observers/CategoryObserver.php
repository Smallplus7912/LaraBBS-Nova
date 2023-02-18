<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\Reply;
use App\Models\Topic;

class CategoryObserver
{
    public function deleted(Category $category)
    {
        // 删除所有该分类下的文章和评论
        $topics = Topic::query()->where('category_id', $category->id)->pluck('id');
        Topic::query()->whereIn('id', $topics)->delete();
        Reply::query()->whereIn('topic_id', $topics)->delete();
    }
}
