<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterRequest;
use App\Notifications\GotFollower;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Image;

class UserController extends Controller
{
    use Notifiable;

    public function setting()
    {
        return view('user.setting')->withUser(\Auth::user());
    }

    public function uploadAvatar(Request $request)
    {
//        $file = $request->file('file');
//        $dir = '/images/'.date('Y').'/'.date('m').'/'.date('d');
//        if (!Storage::exists($dir)) {
//            Storage::makeDirectory($dir);
//        }
//        $path = md5(time()).'.'.$file->getClientOriginalExtension();
//        Storage::putFileAs($dir, $file, $path);
//        $user = User::find(\Auth::id());
//        $user->avatar = 'http://localhost/storage'.$dir.'/'.$path;
//        $user->save();
//        dd($user);
//        return '/storage'.$dir.'/'.$path;

        $file = $request->file('file');
        $disk = \Storage::disk('qiniu');
        $time = date('Y/m/d-H:i:s-');
        $name = $time.uniqid();
        $filename = $disk->put($name, $file);//上传
        if(!$filename) {
            return [
                'error' => 1,
                'msg' => '图片上传失败',
            ];
        }
        $img_url = $disk->getDriver()->downloadUrl($filename); //获取下载链接
        $user = User::find(\Auth::id());
        $user->avatar = $img_url;
        $user->save();
        return $img_url;
    }

    public function settingStore()
    {

    }

    public function show($id)
    {
        $user = User::find($id);
        return view('user.show', compact('user'));
    }

    public function follow($id)
    {
        $user = \Auth::user();
        $user->followThisUser($id);
        $following = User::find($id);
        $following->notify(new GotFollower($user));
        return back();
    }

    public function unfollow($id)
    {
        $user = \Auth::user();
        $user->unfollowThisUser($id);
        return back();
    }
}
