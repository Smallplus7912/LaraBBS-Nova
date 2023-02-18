<?php

// function route_class()
// {
//     return str_replace('.', '-', Route::currentRouteName());
// }

// function category_nav_active($category_id)
// {
//     return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
// }

// function make_excerpt($value, $length = 200)
// {
//     $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
//     return Str::limit($excerpt, $length);
// }
function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function category_nav_active($category_id)
{
    return active_class((if_route('categories.show') && if_route_param('category', $category_id)));
}

function make_excerpt($value, $length = 200)
{
    $excerpt = trim(preg_replace('/\r\n|\r|\n+/', ' ', strip_tags($value)));
    return Str::limit($excerpt, $length);
}

function model_admin_link($title, $model)
{
    return model_link($title, $model, 'admin');
}

function model_link($title, $model, $prefix = '')
{
    // 获取数据模型的复数蛇形命名
    $model_name = model_plural_name($model);

    // 初始化前缀
    $prefix = $prefix ? "/$prefix/" : '/';

    // 拼接url
    $url = config('app.url') . $prefix . $model_name . '/' . $model->id;

    // 组装a标签返回
    return '<a href="' . $url . '" target="_blank">' . $title . '</a>';
}

function model_plural_name($model)
{
    // 从实体中获取完整类名，例如：App\Models\User
    $full_class_name = get_class($model);

    // 获取基础类名，例如：传参 `App\Models\User` 会得到 `User`
    $class_name = class_basename($full_class_name);

    // 蛇形命名，例如：传参 `User`  会得到 `user`, `FooBar` 会得到 `foo_bar`
    $snake_case_name = snake_case($class_name);

    // 获取子串的复数形式，例如：传参 `user` 会得到 `users`
    return str_plural($snake_case_name);
}

/**
 * 查询本次七天
 */
function getThisWeekStartAndEnd()
{
    $date = date('Y-m-d');
    $start = date('Y-m-d', strtotime("$date - 7 days"));

    return $start . '~' . $date;
}

/**
 * 查询上个七天
 */
function getLastWeekStartAndEnd()
{
    $date = date('Y-m-d');

    $start = date('Y-m-d', strtotime("$date - 15 days"));
    $end = date('Y-m-d', strtotime("$date - 8 days"));

    return $start . '~' . $end;
}

/**
 * 通过开始结束时间获取每一天的日期
 */
function getDateFromRange($startdate, $enddate)
{

    $stimestamp = strtotime($startdate);
    $etimestamp = strtotime($enddate);

    // 计算日期段内有多少天
    $days = ($etimestamp - $stimestamp) / 86400 + 1;

    // 保存每天日期
    $date = array();

    for ($i = 0; $i < $days; $i++) {
        $date[] = date('Y-m-d', $stimestamp + (86400 * $i));
    }

    return $date;
}

/**
 * 获取本周或者上周的全部日期
 */
function getWeekDays($type)
{
    $week = explode('~', $type == 'this' ? getThisWeekStartAndEnd() : getLastWeekStartAndEnd());
    $start = $week[0];
    $end = $week[1];

    // 获取这个区间内的所有日期
    $days = getDateFromRange($start, $end);

    return $days;
}

/**
 * 根据开始结束时间分组统计每天新增
 */
function getDateData($weeks, $table)
{
    $res = \Illuminate\Support\Facades\DB::table($table)
        ->select(\Illuminate\Support\Facades\DB::raw("DATE_FORMAT(created_at,'%Y-%m-%d') AS days, IFNULL(count(*), 0) AS count"))
        ->whereBetween('created_at', $weeks)
        ->orderBy('days')
        ->groupBy('days')
        ->get()
        ->keyBy('days')
        ->toArray();
    return json_decode(json_encode($res), true);
}

/**
 * 获取某周的增长数据
 */
function getPerDay($type, $table)
{
    $thisWeeks = explode('~', $type == 'this' ? getThisWeekStartAndEnd() : getLastWeekStartAndEnd());
    $thisDays = getWeekDays($type);
    $thisData = getDateData($thisWeeks, $table);
    $thisPerDay = [];

    foreach ($thisDays as $day) {
        array_push($thisPerDay, $thisData[$day]['count'] ?? 0);
    }
    return $thisPerDay;
}
