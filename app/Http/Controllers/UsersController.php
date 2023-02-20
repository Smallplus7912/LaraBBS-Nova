<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest;

use App\Handlers\ImageUploadHandler;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use  Illuminate\Support\Str;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['show']]);
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $this->authorize('update', $user);
        return view('users.edit', compact('user'));
    }

    public function update(UserRequest $request, User $user)
    {
        $this->authorize('update', $user);
        $data = $request->all();

        if ($request->avatar) {
            //将文件赋值给file
            $file = $request->avatar;
            //定义文件前缀
            $file_prefix = $user->id;
            //后缀名
            $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';
            //文件名
            $filename = $file_prefix . '_' . time() . '_' . Str::random(10) . '.' . $extension;
            //保存
            $upload = Storage::disk('oss')->putFileAs('/avatars', $file, $filename);
            //$url = Storage::disk('public')->url('avatars/'. $filename);
            //保存绝对地址，应用前端显示需要文件的绝对地址
            $address = 'http://avatar86177.oss-cn-hangzhou.aliyuncs.com/';
            $data['address'] = $address;
            $data['avatar'] = $upload;
        }
        $user->update($data);
        return redirect()->route('users.show', $user->id)->with('success', '个人资料更新成功！');
    }
    
}