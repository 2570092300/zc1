<?php

namespace App\Http\Controllers;

use App\Sina;
use Illuminate\Http\Request;

class Test extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function index_do(Request $request)
    {
        // return 1;
        $this->validate($request, [
            'email' => 'required|unique:sina',
            'pwd' => 'required',
            'repwd' => 'required',
            'phone' => 'required',
        ]);

        if ($request['pwd'] == $request['repwd']) {
            $model = new Sina();
            $model->email = $request['email'];
            $model->pwd = md5($request['pwd']);
            $model->phone = $request['phone'];
            $model->nickname = 'sina_' . rand(100000, 999999);
            $sina = $model->save();
            if ($sina) {
                return $this->login();
            } else {
                return $this->index();
            }
        } else {
            return $this->index();
        }
    }

    public function login()
    {
        return view('login');
    }

    public function login_do(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'pwd' => 'required',
        ]);

        $model = new Sina();
        $user = $model->where('email', $request['email'])->where('pwd', md5($request['pwd']))->first();

        if ($user) {
            return view('list', ['data' => $user]);
        } else {
            return '信息错误';
        }
    }
}
