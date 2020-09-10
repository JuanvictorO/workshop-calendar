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

        $end_time = strtotime("+1 hours", strtotime($input['time']));
        $end_time = date('H:i:s', $end_time);

        $input['end'] = $input['start'] . ' ' . $end_time;
        $input['start'] = $input['datetime'];

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
    public function select()
    {
        $query = DB::table('event')->select('id', 'name AS title', 'start', 'end')->get();
        $query = json_encode($query);
        return view('calendar', ['events' => $query]);
    }

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
}
