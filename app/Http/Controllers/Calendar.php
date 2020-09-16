<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class Calendar extends Controller
{

    /**
     * Adiciona uma nova avaliação na tabela de Eventos
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request)
    {

        $customAttributes = array(
            'name' => 'Nome',
            'start' => 'Data',
            'time' => 'Horários disponíveis',
            'datetime' => 'Datetime'
        );

        $rules = [
            'name' => 'required|string|max:100',
            'start' => 'required',
            'time' => 'required',
            'datetime' => 'required|unique:event,start'
        ];

        $messages = [
            'datetime.required' => "Algo deu errado!",
            'datetime.unique' => "Essa Data e horário já foram reservadas"
        ];

        $validator = Validator::make($request->all(), $rules, $messages, $customAttributes);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator);
        }

        $input = $request->input();

        // criando variável para armazenar o término da 'consulta'
        $end_time = strtotime("+1 hours", strtotime($input['time']));
        $end_time = date('H:i:s', $end_time);

        $input['end'] = $input['start'] . ' ' . $end_time;
        $input['start'] = $input['datetime'];

        //liberando as variáveis para usar a query básica
        unset($input['time']);
        unset($input['_token']);
        unset($input['datetime']);

        $function = DB::table('event')->insert($input);
        if ($function) {
            return back()->with('success', "Adicionado com sucesso!");
        } else {
            return back()->withErrors("Ocorreu um erro ao marcar a avaliação.");
        }
    }

    /**
     * Exibe a página de calendário para o Administrador
     *
     * @return void
     */
    public function list()
    {
        return view('calendarAdmin');
    }

    /**
     * Retona um JSON com consultas marcadas em determinado intervalo. Função utilizada pelo fullcalendar para exibir os eventos de um determinado intervalo.
     *
     * @return json
     */
    public function selectEvents()
    {
        $start = (!empty($_GET["start"])) ? ($_GET["start"]) : ('');
        $end = (!empty($_GET["end"])) ? ($_GET["end"]) : ('');

        $query = DB::table('event')->select('id', 'name AS title', 'start', 'end')
            ->whereDate('start', '>=', $start)->whereDate('end',   '<=', $end)->get();

        return response()->json($query);
    }

    /**
     * Retorna os horários disponíveis de um determinado dia.
     *
     * @param date $date
     * @return json
     */
    public function getEventsInDate($date)
    {
        $query = DB::table('event')->select(DB::raw('TIME(start) as date'))->whereDate('start', $date)->get();

        //echo json_encode($query);
        $startHour = strtotime('08:00:00');
        $endHour = strtotime('17:00:00');
        $options = '';

        if ($query) {
            if (count($query) > 0) {
                while ($startHour <= $endHour) {

                    $x = 0;

                    foreach ($query as $time) {
                        if ($startHour == strtotime($time->date)) {
                            $x = 1;
                            break;
                        }
                    }

                    if ($x === 0) {
                        $options .= '<option value="' . date('H:i:s', $startHour) . '">' . date('H:i', $startHour) . '</option>';
                    }

                    $startHour = strtotime('+1 hours', $startHour);
                }
            } else {
                while ($startHour <= $endHour) {
                    $options .= '<option value="' . date('H:i:s', $startHour) . '">' . date('H:i', $startHour) . '</option>';
                    $startHour = strtotime('+1 hours', $startHour);
                }
            }

            if ($options === '') {
                return response()->json([
                    'success' => false,
                    'json'    => 'Esse dia não possui mais horários disponíveis!'
                ]);
            } else {
                return response()->json([
                    'success' => true,
                    'json'   => $options
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'json'    => 'Algo deu errado, tente novamente mais tarde ou contate-nos!'
            ]);
        }
    }

    // LEMBRAR DE SEPARAR AS FUNÇÕES DAQUI PARA BAIXO EM UM OUTRO CONTROLLER
    public function nonOperatingDays()
    {
        return view('nonOperatingDays');
    }

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
