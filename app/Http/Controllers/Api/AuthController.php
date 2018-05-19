<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
	public function register(Request $request){

		$request->validate([
			'email'=>'required',
			'name'=>'required',
			'password'=>'required'
		]);

		$user=new User();
		$user->email=$request->email;
		$user->name=$request->name;
		$user->email=$request->email;
		$user->password=bcrypt($request->password);
		$user->save();


		$http = new Client;

		$response = $http->post(url('oauth/token'), [
		    'form_params' => [
		        'grant_type' => 'password',
		        'client_id' => '2',
		        'client_secret' => 'cK13jXYdcIjETs7yKO8wpkvFGoZhN6WgEex9eCbB',
		        'username' => $request->email,
		        'password' => $request->password,
		        'scope' => '',
		    ],
		]);


		return response(['auth'=>json_decode((string) $response->getBody(), true),'user'=>$user]);
		
	}

	public function login(Request $request){
		
		$request->validate([
			'email'=>'required',
			'password'=>'required'
		]);

		$user= User::where('email',$request->email)->first();

		if(!$user){
			return response(['status'=>'error','message'=>'User not found']);
		}

		if(Hash::check($request->password, $user->password)){

				$http = new Client;

			$response = $http->post(url('oauth/token'), [
				'form_params' => [
					'grant_type' => 'password',
					'client_id' => '2',
					'client_secret' => 'cK13jXYdcIjETs7yKO8wpkvFGoZhN6WgEex9eCbB',
					'username' => $request->email,
					'password' => $request->password,
					'scope' => '',
				],
			]);
			return response(['auth' => json_decode((string)$response->getBody(), true), 'user' => $user]);

		
		}else{
			return response(['message'=>'password not match','status'=>'error']);
		}


	}

	public function refreshToken() {

		$http = new Client;

		$response = $http->post(url('oauth/token'), [
		    'form_params' => [
		        'grant_type' => 'refresh_token',
		        'refresh_token' => request('refresh_token'),
		        'client_id' => '2',
		        'client_secret' => 'cK13jXYdcIjETs7yKO8wpkvFGoZhN6WgEex9eCbB',
		        'scope' => '',
		    ],
		]);

		return json_decode((string) $response->getBody(), true);

	}

}
