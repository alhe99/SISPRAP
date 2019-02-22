<?php

namespace App\Http\Controllers;

use App\Events\MessageSentEvent;
use App\Mensaje;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
	public function privateMessages(User $user)
	{
		$privateCommunication= Mensaje::with('user')
		->where(['user_id'=> auth()->id(), 'receiver_id'=> $user->id])
		->orWhere(function($query) use($user){
			$query->where(['user_id' => $user->id, 'receiver_id' => auth()->id()]);
		})
		->get();

		return $privateCommunication;
	}
	public function getMessagesToStudent(){
		$user = Auth::user();
		$mensajes = Mensaje::where(['usuario_id'=> $user->id, 'receiver_id'=> 0])
		->orWhere(function($query) use($user){
			$query->where(['usuario_id' => 0 , 'receiver_id' => $user->id]);
		})->orderBy('created_at','asc')->get();

		return $mensajes;
	}
	public function sendPrivateMessage(Request $request)
	{
		$user = User::with('estudiante.carrera')->find(Auth::user()->id);

		$message = Mensaje::create([
			'mensaje' => $request->mensaje,
			'usuario_id' => $user->id,
			'receiver_id' => $request->receiver_id
		]);

		broadcast(new MessageSentEvent($user, $message))->toOthers();
		return response(['status'=>'Message private sent successfully','message'=>$message]);
	}
}
