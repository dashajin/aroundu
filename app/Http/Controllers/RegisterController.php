<?php

namespace App\Http\Controllers;

use App\Emailcode;
use App\Http\Requests\RegisterRequest;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function index()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:10|unique:users,name',
            'email' => 'required|unique:users,email|email',
            'password' => 'required|min:6|max:16|confirmed',
            'code' => 'required'
        ]);
        //dd(request(['name', 'email']));
        $name = $request['name'];
        $email = $request['email'];
        $password = bcrypt($request['password']);
        $code = $request['code'];
        $emailcode = Emailcode::where('email', '=',$email)->firstOrFail();
        if ($emailcode->code != $code) {
            $validator->errors()->add('code', '验证码错误');
            return back()->withErrors($validator);
        } else {
            $now = Carbon::now();
            $codeTime = $emailcode->updated_at;
            if ($now->diffInMinutes($codeTime) > 5) {
//                dd($now->diffInMinutes($codeTime) > 5);
                $validator->errors()->add('code', '验证码无效');
                return back()->withErrors($validator);
            }
        }
        $user = User::create(compact('name', 'email', 'password'));
        return redirect('/login');
    }

    public function send()
    {
        $code = rand(1000,9999);
        $email = request('email');
        Emailcode::updateOrCreate(['email' => $email], ['code' => $code]);
        Mail::raw('aroundu '.$code, function ($msg) use ($email){
            $msg->subject('aroundu注册验证码');
            $msg->to($email);
        });
        return '发送成功';
    }
}
