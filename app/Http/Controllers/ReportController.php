<?php

namespace App\Http\Controllers;

use Dompdf\Dompdf;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\View;
use App\Models\User;
use App\Models\RegisterDate;
use App\Models\RegisterHour;

class ReportController extends Controller
{
    public function generateReport($id = false)
    {
        $data = [];

        $ano = date('Y');
        $mes = date('m');

        if (!$id) {
            return response()->json([
                'message' => 'Qual usuário?',
            ], 200);
        }

        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Usuário não existe',
            ], 200);
        }

        $register_date = RegisterDate::whereYear('date_register', $ano)
                             ->whereMonth('date_register', $mes)
                             ->where('id_user', $id)
                             ->get();
        $newRegister = [];

        foreach($register_date as $register) {
            $register_hour = RegisterHour::where('id_register_date', $register->id)
                                ->orderBy('hour', 'ASC')
                                ->get();
            $hours = [];

            foreach($register_hour as $hour) {
                array_push($hours, $hour->hour);
            }

            $date_register = [
                'date' => $register->date_register,
                'hours' => $hours
            ];

            array_push($newRegister, $date_register);
        }

        $data['title']        = 'Relatorio';
        $data['name']         = $user->name;
        $data['email']        = $user->email;
        $data['hour_entry']   = $user->hour_entry;
        $data['hour_pause']   = $user->hour_pause;
        $data['hour_return']  = $user->hour_return;
        $data['hour_exit']    = $user->hour_exit;
        $data['content']      = $newRegister;

        $pdf = $this->generatePdf($data);

        return $this->createResponse($pdf, 'relatorio.pdf');
    }

    private function generatePdf($data)
    {
        $dompdf = new Dompdf();
        $html = View('report.pdf', $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->render();
        return $dompdf->output();
    }

    private function createResponse($content, $filename)
    {
        $response = new Response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
        
        $response->header('Access-Control-Allow-Origin', '*'); 
        $response->header('Access-Control-Allow-Methods', 'GET, POST'); 
        
        return $response;
    }
}
