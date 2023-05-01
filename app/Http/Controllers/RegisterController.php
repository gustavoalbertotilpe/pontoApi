<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\RegisterDate;
use App\Models\RegisterHour;

class RegisterController extends Controller
{
    private $loggedUser;

    public function __construct() {
        $this->middleware('auth:api');
        $this->loggedUser = auth()->user();
        date_default_timezone_set('America/Sao_Paulo');
    }

    public function register() {
        $register  = RegisterDate::where('id_user', $this->loggedUser->id)
                     ->whereDate('date_register', date('Y-m-d'))
                     ->count();

        if ($register > 0) {

            $register = RegisterDate::where('id_user', $this->loggedUser->id)
                        ->whereDate('date_register', date('Y-m-d'))
                        ->get(); 

            $registerIdDate = $register[0]->id;   

            $newRegisterHour = new RegisterHour();
            $newRegisterHour->hour = date('H:i:s');
            $newRegisterHour->id_register_date = $registerIdDate;
            $newRegisterHour->save();
               
            return response()->json([
                'message' => 'Ponto registrado com sucesso!',
                'lastRegister' => 'Ultimo registro ás '.date('H:i').' horas do dia '. date('d/m/Y'),
            ], 200);

        } else {
            $newRegisterDate = new RegisterDate();
            $newRegisterDate->date_register = date('Y-m-d');
            $newRegisterDate->id_user       = $this->loggedUser->id;
            $newRegisterDate->save();

            $newRegisterHour = new RegisterHour();
            $newRegisterHour->hour = date('H:i:s');
            $newRegisterHour->id_register_date  = $newRegisterDate->id;
            $newRegisterHour->save();

            return response()->json([
                'message' => 'Ponto registrado com sucesso!',
                'lastRegister' => 'Ultimo registro ás '.date('H:i').' horas do dia '. date('d/m/Y'),
            ], 200);
        }   

        return response()->json([
            'error' => 'Erro ao registrar o ponto'
        ], 200);
    }
}
