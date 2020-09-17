<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class NonOperatingDay extends Controller
{
    /**
     * Exibe a página de Dias Não Funcionais
     *
     * @return void
     */
    public function nonOperatingDays()
    {
        return view('nonOperatingDays');
    }

    /**
     * Adiciona um dia na tabela como Não Funcional
     *
     * @param Request $request
     * @return json
     */
    public function addNonOperatingDay(Request $request)
    {
        $input = $request->input();

        if (!isset($input['start'])) {
            return response()->json([
                'success' => false,
                'msg'    => 'Algo deu errado, tente novamente mais tarde ou contate-nos!'
            ]);
        }

        unset($input['_token']);
        $function = DB::table('nonOperatingDays')->insert($input);

        if ($function) {
            return response()->json([
                'success' => true,
                'msg'    => 'Dia marcado com sucesso!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg'    => 'Algo deu errado, tente novamente mais tarde ou contate-nos!'
            ]);
        }
    }

    /**
     * Retorna todos os eventos cadastrados em um intervalo de tempo
     *
     * @return json
     */
    public function getNonOperatingDays()
    {
        $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
        $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');

        $query = DB::table('nonOperatingDays')->select('id', 'start')
            ->whereDate('start', '>=', $start)->whereDate('start',   '<=', $end)->get();

        $x = 0;
        foreach ($query as $event) {
            $query[$x]->end = date('Y-m-d', strtotime($event->start) + 3600 * 24);
            $query[$x]->overlap = false;
            $query[$x]->display = 'background';
            $query[$x]->color = 'red';
            $x++;
        }

        return response()->json($query);
    }

    /**
     * Deleta um campo da tabela NonOperatingDays
     *
     * @param int $id
     * @return json
     */
    public function deleteNonOperatingDay($id)
    {
        if (!isset($id) || !is_numeric($id)) {
            return response()->json([
                'success' => false
            ]);
        }

        $function = DB::table('nonOperatingDays')->where('id', $id)->delete();

        if ($function) {
            return response()->json([
                'success' => true,
                'msg'    => 'Dia removido com sucesso!'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg'    => 'Algo deu errado, tente novamente mais tarde ou contate-nos!'
            ]);
        }
    }
}
