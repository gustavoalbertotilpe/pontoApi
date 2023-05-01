<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\RegisterDate;
use App\Models\RegisterHour;

class UserController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
    }

    public function create(Request $request) {
      
        if ($this->loggedUser->isAdmin === '0') {
            return response()->json(['error'=>'Não autorizado', 401]);
            die;
        }
        
        $name           = $request->input('name');
        $email          = $request->input('email');
        $password       = $request->input('password');
        $hour_entry     = $request->input('hour_entry');
        $hour_pause     = $request->input('hour_pause');
        $hour_return    = $request->input('hour_return');
        $hour_exit      = $request->input('hour_exit');
        $isAdmin        = $request->input('isAdmin');

        if ($name && $email && $password) {

            $emailExists = User::where('email', $email)->count();

            if ($emailExists > 0) {
                return response()->json([
                    'error' => 'E-mail já cadastrado!',
                ], );
            }

            $hash = password_hash($password, PASSWORD_DEFAULT);

            $newUser = new User();

            $newUser->name         = $name;
            $newUser->email        = $email;
            $newUser->password     = $hash;
            $newUser->hour_entry   = $hour_entry;
            $newUser->hour_pause   = $hour_pause;
            $newUser->hour_return  = $hour_return;
            $newUser->hour_exit    = $hour_exit;
            $newUser->isAdmin      = $isAdmin;

            $newUser->save();

            return response()->json([
                'message' => 'Usuário cadastrado com sucesso!',
            ], 201);

        } else {
            return response()->json([
                'error' => 'Campo incompleto',
            ], 200);
        }

        return response()->json([
            'error' => ''
        ], 201);
    }

    public function update(Request $request, $id = false) {
        if ($this->loggedUser->isAdmin === '0') {
            return response()->json(['error'=>'Não autorizado', 401]);
            die;
        }

        $name           = $request->input('name');
        $email          = $request->input('email');
        $password       = $request->input('password');
        $hour_entry     = $request->input('hour_entry');
        $hour_pause     = $request->input('hour_pause');
        $hour_return    = $request->input('hour_return');
        $hour_exit      = $request->input('hour_exit');
        $isAdmin        = $request->input('isAdmin');

        $user = User::find($id);

        if ($name) {
            if ($password) {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $user->password = $hash;
            }

            if ($email != $user->email) {
                $emailExists = User::where('email', $email)->exists();

                if ($emailExists) {
                    return response()->json([
                        'error' => 'E-mail já esta cadastrado no sistema!',
                    ], 200);
                }
            }

            $user->name         = $name;
            $user->email        = $email;
            $user->hour_entry   = $hour_entry;
            $user->hour_pause   = $hour_pause;
            $user->hour_return  = $hour_return;
            $user->hour_exit    = $hour_exit;
            $user->isAdmin      = $isAdmin;

            $user->save();

            return response()->json([
                'message' => 'Usuário salvo com sucesso!',
            ], 200);
        } 

        return response()->json([
            'error' => '',
        ], 200);
    }

    public function read(Request $request, $id = false) {
        if ($this->loggedUser->isAdmin === '0') {
            return response()->json(['error'=>'Não autorizado', 401]);
            die;
        }

        if ($id) {
            $user = User::find($id);

            return response()->json([
                'user' => $user
            ], 200);
        }

        $q = $request->query('q');

        $users = User::whereNotIn("id", [$this->loggedUser->id])->where('name','like',"%${q}%")->orderBy('name', 'asc')->paginate(15);

        return response()->json([
            'users' => $users
        ], 200);
    }

    public function delete(Request $request, $id = false) {
        if ($this->loggedUser->isAdmin === '0') {
            return response()->json(['error'=>'Não autorizado', 401]);
            die;
        }

        $user = User::find($id);

        $register  = RegisterDate::where('id_user', $user->id)->get();

        if ($register) {

            foreach($register as $item) {
                $registerHours = RegisterHour::where('id_register_date', $item->id);
                $registerHours->delete();
                $item->delete();
            }
        }

        $user->delete();

        return response()->json([
            'message' => 'Usuário deletado com sucesso', 
        ], 200);
    }
}
