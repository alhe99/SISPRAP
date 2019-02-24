<?php

namespace App\Http\Controllers;

use App\User;
use App\Mensaje;
use App\Estudiante;
use Illuminate\Http\Request;
use App\Events\MessageSentEvent;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class MensajeController extends Controller
{
	public function getMessagesToStudent(){
		$user = Auth::user();
		$mensajes = Mensaje::where(['usuario_id'=> $user->id, 'receiver_id'=> 0])
		->orWhere(function($query) use($user){
			$query->where(['usuario_id' => 0 , 'receiver_id' => $user->id]);
		})->orderBy('created_at','asc')->get();

                $msj_unread = Mensaje::where([['usuario_id',0],['receiver_id',$user->id] ,['read',false]])->latest()->count();

		return [
			"messages" => $mensajes,
			"messages_unread" => $msj_unread
		];
	}
	public function sendPrivateMessage(Request $request)
	{
		$user = User::with('estudiante.carrera')->find(Auth::user()->id);

		$message = Mensaje::create([
			'mensaje' => $request->mensaje,
			'usuario_id' => $user->id,
			'receiver_id' => $request->receiver_id
		]);
		$user->last_token_session = $message->created_at;
		$user->update();
		broadcast(new MessageSentEvent($user, $message))->toOthers();
		return response(['status'=>'Message sent successfully','message'=>$message]);
	}
	public function getListOfMessagesAdmin(Request $request){
		$buscar = $request->buscar;
                $usuarios = User::has('mensajes')->usuario($buscar)->orderBy('last_token_session','DESC')->where('rol_id',3)->get();
		$arrayMensajes = array();
		foreach ($usuarios as $key => $usuario) {
			array_push($arrayMensajes,array( 
				"usuario_id" => $usuario->id,
				"usuario" => $usuario->estudiante->nombre." ".substr($usuario->estudiante->apellido,0,strpos($usuario->estudiante->apellido," ")),
				"foto" => $usuario->estudiante->foto_name,
				"msj_unread" => Mensaje::where([['usuario_id', $usuario->id],['receiver_id',0 ],['read',false]])->latest()->count(),
				"message" => Mensaje::select('mensaje','created_at')->where([['usuario_id', $usuario->id],['receiver_id',0]])
                                        ->orWhere(function( $query) use( $usuario){ 
                                                $query->where(['usuario_id' => 0, 'receiver_id' => $usuario->id]);
                                        })->latest()->first()
			));
		}
                return $arrayMensajes;
	}
	public function getRecordsMessagesByUser($usuario_id){

		$mensajes = Mensaje::where(['usuario_id'=> $usuario_id, 'receiver_id'=> 0])
		->orWhere(function($query) use( $usuario_id){
			$query->where(['usuario_id' => 0 , 'receiver_id' => $usuario_id]);
		})->orderBy('created_at','asc')->get();

		return $mensajes;
	}
	public function setReadMessageAdmin($usuario_id){
	       DB::table('mensajes')->where([['usuario_id', $usuario_id], ['receiver_id', 0], ['read', false]])->update(array( 'read' => true));
	}
	public function setReadMessageEstudent(){
	       $usuario_id = Auth::user()->id;
	       DB::table('mensajes')->where([['usuario_id',0], ['receiver_id', $usuario_id], ['read',false]])->update(array( 'read' => true));
	}
}
