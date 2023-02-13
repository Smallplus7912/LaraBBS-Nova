<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use Image;

class ImageUploadHandler
{

    // 限制文件后缀名
    protected $allowed_ext = ['png', 'jpg', 'gif', 'jpeg'];

    // public function save($file, $folder, $file_prefix, $max_width = false)
    // {
    //     // 文件根据类别，时间进行分类
    //     $folder_name = "$folder/" . date("Ym/d", time());

    //     // 拼接存储的物理路径
    //     $upload_path = public_path() . '/storage/' . $folder_name;

    //     // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
    //     $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

    //     // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
    //     $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

    //     // 如果上传的不是图片将终止操作
    //     if (!in_array($extension, $this->allowed_ext)) {
    //         return false;
    //     }

    //     // 将图片移动到我们的目标存储路径中
    //     $file->move($upload_path, $filename);

    //     // 如果限制了图片宽度，就进行裁剪
    //     if ($max_width && $extension != 'gif') {

    //         // 此类中封装的函数，用于裁剪图片
    //         $this->reduceSize($upload_path . '/' . $filename, $max_width);
    //     }

    //     return [
    //         'path' => config('app.url') . "/storage/$folder_name/$filename"
    //     ];
    // }

    public function saveAvatar($file, $folder, $file_prefix, $max_width = false)
    {
        // 文件根据类别，时间进行分类
        $folder_name = "$folder/" . date("Ym/d", time());

        // 拼接存储的物理路径
        $upload_path = storage_path('app/public/') . $folder_name;

        // 获取文件的后缀名，因图片从剪贴板里黏贴时后缀名为空，所以此处确保后缀一直存在
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        // 拼接文件名，加前缀是为了增加辨析度，前缀可以是相关数据模型的 ID
        $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;

        // 如果上传的不是图片将终止操作
        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        // 将图片移动到我们的目标存储路径中
        $file->move($upload_path, $filename);

        // 如果限制了图片宽度，就进行裁剪
        if ($max_width && $extension != 'gif') {

            // 此类中封装的函数，用于裁剪图片
            $this->reduceSize($upload_path . '/' . $filename, $max_width);
        }

        return [
            'path' => "$folder_name/$filename"
        ];
    }

    /**
     * 图片裁剪
     *
     * @param $file_path
     * @param $max_width
     */
    public function reduceSize($file_path, $max_width)
    {
        // 先实例化，传参是文件的磁盘物理路径
        $image = Image::make($file_path);

        // 进行大小调整的操作
        $image->resize($max_width, null, function ($constraint) {

            // 设定宽度是 $max_width，高度等比例缩放
            $constraint->aspectRatio();

            // 防止裁图时图片尺寸变大
            $constraint->upsize();
        });

        // 对图片修改后进行保存
        $image->save();
    }
}
