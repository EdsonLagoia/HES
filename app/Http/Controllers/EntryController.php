<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Patient;
use App\Models\Origin;
use App\Models\Entry;

use Codedge\Fpdf\Fpdf\Fpdf;

class EntryController extends Controller
{
    protected $fpdf;

    public function __construct() {
        $this->fpdf = new Fpdf;
    }

    public function index(Request $request) {
        $verify = AccessController::verify('entry');
        if($verify)
            return redirect($verify);

        return view('modules.entry.index', [
            'menu' => ModuleController::menu(),
            'success' => $request->cookie('success'),
            'data' => Entry::limit(20)->orderBy('id', 'desc')->get(),
            'origins' => Origin::where('active', 1)->get()
        ]);
    }

    public function loadPatient(Request $request) {
        if($request->search) {
           $cpf = '';

            if(is_numeric($request->search)) {
                $length = mb_strlen($request->search) < 11 ? mb_strlen($request->search) : 11;
                for($i = 0; $i < $length ; $i++) {
                    $cpf .= $request->search[$i];
                    if($i == 2 || $i == 5)
                        $cpf .= '.';
                    elseif($i == 8)
                        $cpf .= '-';
                }
            }

            $patient = Patient::orWhere('name', 'like', '%' . $request->search . '%');
            $patient = $cpf ? $patient->orWhere('cpf', 'like', '%' . $cpf . '%') : $patient;

            $result = $patient->where('id', '!=', 1)->count();
            $search = $patient->where('id', '!=', 1)->limit(3)->get();
        } else {
            $result = 0;
            $search = '';
        }

        return view('modules.entry.search', [
            'result' => $result,
            'search' => $search,
            'origins' => Origin::where('active', 1)->get()
        ]);
    }

    public function store(Request $request, $id) {
        $create = new entry;
        $create->entry   = date('Y-m-d H:i:s');
        $create->user    = session()->get('id_user');
        $create->patient = $request->id;
        $create->origin  = $request->origin;
        $create->type    = $request->type;
        $create->save();

        return redirect('entry')->cookie('success', 'Entrada Registrada com Sucesso!', 0.03);
    }

    public function bpa_adult($id) {
        $pdf = $this->fpdf;
        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(15, 10, 15);
        $data = Entry::findOrfail($id);
        $patient = Patient::findOrFail($data->patient);
        $origin = Origin::findOrFail($data->origin);

        if($data->type != 'adult') {
            if($data->type == 'pediatric')
                return redirect('entry/pediatric/' . $data->id);
            else
                return redirect('entry/obstetric/' . $data->id);
        }

        // Layout
            $pdf->SetXY(10, 15); $pdf->Cell(190, 64, '', 1);
            $pdf->SetXY(10.25, 15.25); $pdf->Cell(189.5, 63.5, '', 1);
            $pdf->SetXY(10.5, 15.5); $pdf->Cell(189, 63, '', 1);
            $pdf->SetXY(10, 78); $pdf->Cell(190, 76, '', 'LBR');
            $pdf->SetXY(10.25, 78.25); $pdf->Cell(189.5, 75.5, '', 'LBR');
            $pdf->SetXY(10.5, 78.5); $pdf->Cell(189, 75, '', 'LBR');
            $pdf->SetXY(10, 153); $pdf->Cell(190, 134, '', 'LBR');
            $pdf->SetXY(10.25, 153.25); $pdf->Cell(189.5, 133.5, '', 'LBR');
            $pdf->SetXY(10.5, 153.5); $pdf->Cell(189, 133, '', 'LBR');

            $pdf->SetXY(10, 34); $pdf->Cell(10, 5, '', 'T');
            $pdf->SetXY(38, 84); $pdf->Cell(25, 10, '', 'LB');
            $pdf->SetXY(63, 84); $pdf->Cell(18, 10, '', 'LB');
            $pdf->SetXY(81, 84); $pdf->Cell(18, 10, '', 'LB');
            $pdf->SetXY(99, 84); $pdf->Cell(25, 10, '', 'LB');
            $pdf->SetXY(124, 84); $pdf->Cell(26, 10, '', 'LB');
            $pdf->SetXY(150, 84); $pdf->Cell(25, 10, '', 'LB');
            $pdf->SetXY(175, 84); $pdf->Cell(25, 10, '', 'LB');
            $pdf->SetXY(40, 94); $pdf->Cell(160, 5, '', 'B');
            $pdf->SetXY(40, 99); $pdf->Cell(160, 5, '', 'B');
            $pdf->SetXY(40, 104); $pdf->Cell(160, 5, '', 'B');
            $pdf->SetXY(40, 109); $pdf->Cell(160, 5, '', 'B');
            $pdf->SetXY(40, 114); $pdf->Cell(160, 5, '', 'B');
            $pdf->SetXY(40, 119); $pdf->Cell(160, 5, '', 'B');
            $pdf->SetXY(20, 124); $pdf->Cell(180, 5, '', 'B');
            $pdf->SetXY(130, 129); $pdf->Cell(35, 25, '', 'LR');
            $pdf->SetXY(20, 134); $pdf->Cell(110, 5, '', 'LB');
            $pdf->SetXY(20, 139); $pdf->Cell(110, 5, '', 'LB');
            $pdf->SetXY(165, 139); $pdf->Cell(35, 5, '', 'B');
            $pdf->SetXY(20, 144); $pdf->Cell(110, 5, '', 'LB');
            $pdf->SetXY(20, 149); $pdf->Cell(110, 5, '', 'L');
            $pdf->SetXY(145, 231); $pdf->Cell(10, 42, '', 'R');

            $pdf->Circle(190, 136.25, 6);
            $pdf->Line(44, 43.5, 45, 39.5);
            $pdf->Line(55, 43.5, 56, 39.5);
            $pdf->Line(82, 53.5, 83, 49.5);
            $pdf->Line(93, 53.5, 94, 49.5);

            $pdf->Line(133, 146, 162, 146);
            $pdf->Line(172, 151, 181.5, 151);
            $pdf->Line(183.5, 151, 193, 151);

            $pdf->Line(177, 159, 186.5, 159);
            $pdf->Line(188.5, 159, 198, 159);
            $pdf->Line(177, 173, 186.5, 173);
            $pdf->Line(188.5, 173, 198, 173);
            $pdf->Line(165, 243, 174.5, 243);
            $pdf->Line(175.5, 243, 185, 243);
            $pdf->Line(165, 250, 174.5, 250);
            $pdf->Line(175.5, 250, 185, 250);
            $pdf->Line(160, 266, 195, 266);


        // Cabeçalho
            $pdf->Image(public_path() . '/img/logo.png', 25, 16, 40, 18);
            $pdf->Image(public_path() . '/img/amapa.png', 115, 16, 80, 18);

            $pdf->SetFont('Times', 'B', 16);
            $pdf->Rotate(90, 10, 79);
            $pdf->SetXY(10, 79); $pdf->MultiCell(45, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'RECEPÇÃO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->Rotate(90, 10, 154);
            $pdf->SetXY(10, 154); $pdf->MultiCell(75, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENFERMEIRO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->Rotate(90, 10, 277);
            $pdf->SetXY(0, 277); $pdf->MultiCell(133, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÉDICO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->SetFont('Times', 'B', 12);

        // Campos
            $pdf->SetXY(20, 34); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FICHA DE ACOLHIMENTO COM CLASSIFICAÇÃO DE RISCO Nº:'), 'LTB');
            $pdf->SetXY(20, 39); $pdf->MultiCell(180, 5, 'DATA:', 'L');
            $pdf->SetXY(132, 39); $pdf->MultiCell(180, 5, 'HORA DA CHEGADA:');
            $pdf->SetXY(177, 39); $pdf->MultiCell(23, 5, ':', 0, 'C');
            $pdf->SetXY(20, 44); $pdf->MultiCell(180, 5, 'NOME:', 'LT');
            $pdf->SetXY(20, 49); $pdf->MultiCell(180, 5, 'DATA DE NASCIMENTO:', 'LT');
            $pdf->SetXY(132, 49); $pdf->MultiCell(180, 5, 'IDADE:');
            $pdf->SetXY(20, 54); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÃE:'), 'LT');
            $pdf->SetXY(20, 59); $pdf->MultiCell(180, 5, 'SUS:', 'LT');
            $pdf->SetXY(132, 59); $pdf->MultiCell(180, 5, 'CPF:');
            $pdf->SetXY(20, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROCEDÊNCIA:'), 'LT');
            $pdf->SetXY(148, 64); $pdf->MultiCell(180, 5, 'CONTATO:');
            $pdf->SetXY(20, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO:'), 'LT');
            $pdf->SetXY(175, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Nº:'));
            $pdf->SetXY(20, 74); $pdf->MultiCell(180, 5, 'BAIRRO:', 'LT');
            $pdf->SetXY(90, 74); $pdf->MultiCell(180, 5, 'CIDADE:');
            $pdf->SetXY(150, 74); $pdf->MultiCell(180, 5, 'ESTADO:');

            $pdf->SetXY(20, 79); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Início da classificação:'), 'LB');
            $pdf->SetXY(120, 79); $pdf->MultiCell(180, 5, 'Alergias:');
            $pdf->SetXY(20, 129); $pdf->MultiCell(110, 5, 'QUEIXAS', 'LB', 'C');
            $pdf->SetXY(165, 129); $pdf->MultiCell(20, 15, 'COR', 0, 'C');

            $pdf->SetXY(20, 154); $pdf->MultiCell(180, 7, 'MOTIVO DO ATENDIMENTO:', 'B');
            $pdf->SetXY(20, 161); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'HISTÓRICO CLÍNICO:'), 'B');
            $pdf->SetXY(20, 168); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'EXAME FÍSICO:'), 'B');
            $pdf->SetXY(20, 175); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PRESCRIÇÃO MÉDICA:'), 0, 'C');
            $pdf->SetXY(20, 266); $pdf->MultiCell(135, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DIAGNÓSTICO:'), 'TB');
            $pdf->SetXY(155, 266); $pdf->MultiCell(45, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÉDICO/CRM'), 'B', 'C');
            $pdf->SetXY(20, 273); $pdf->MultiCell(180, 7, 'DESTINO DO PACIENTE:', 'B');
            $pdf->SetXY(20, 280); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'TÉRMINO DO ATENDIMENTO:'));

            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetXY(20, 84); $pdf->MultiCell(18, 5, 'SINAIS VITAIS', 'LB', 'C');
            $pdf->SetXY(38, 84); $pdf->MultiCell(25, 5, 'PA mmHg', 0, 'C');
            $pdf->SetXY(63, 84); $pdf->MultiCell(18, 5, 'FC bpm', 0, 'C');
            $pdf->SetXY(81, 84); $pdf->MultiCell(18, 5, 'FR irpm', 0, 'C');
            $pdf->SetXY(99, 84); $pdf->MultiCell(25, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'T °C'), 0, 'C');
            $pdf->SetXY(124, 84); $pdf->MultiCell(26, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sat O² %'), 0, 'C');
            $pdf->SetXY(150, 84); $pdf->MultiCell(25, 5, 'Glicemia', 0, 'C');
            $pdf->SetXY(175, 84); $pdf->MultiCell(25, 5, 'Peso', 0, 'C');
            $pdf->SetXY(20, 94); $pdf->MultiCell(20, 11.66, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Lâminas dos grupos sistêmicos'), 'LR', 'C');
            $pdf->SetXY(130, 146); $pdf->MultiCell(35, 5, 'Enfermeiro/COREN', 0, 'C');

            $pdf->SetXY(72, 273); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(     ) alta   (     ) internação  (    ) encaminhado para:'));

            $pdf->SetFont('Times', 'B', 9);
            $pdf->SetXY(158, 154); $pdf->MultiCell(42, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Hora início:'));
            $pdf->SetXY(175, 154); $pdf->MultiCell(25, 7, ':', 0, 'C');
            $pdf->SetXY(160.5, 168); $pdf->MultiCell(39, 7, 'Hora fim:');
            $pdf->SetXY(175, 168); $pdf->MultiCell(25, 7, ':', 0, 'C');
            $pdf->SetXY(155, 231); $pdf->MultiCell(45, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Medicação'), 'T', 'C');
            $pdf->SetXY(155, 238); $pdf->MultiCell(45, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Início: '));
            $pdf->SetXY(165, 238); $pdf->MultiCell(20, 7, ':', 0, 'C');
            $pdf->SetXY(155, 245); $pdf->MultiCell(45, 7, 'Fim: ', 'B');
            $pdf->SetXY(165, 245); $pdf->MultiCell(20, 7, ':', 0, 'C');


            $pdf->SetFont('Times', '', 8.5);
            $pdf->SetXY(48, 94); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações do SNC'), 'LR');
            $pdf->SetXY(48, 99); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Oftalmológicas'), 'LR');
            $pdf->SetXY(48, 104); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Odontológicas'), 'LR');
            $pdf->SetXY(48, 109); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Respiratórias'), 'LR');
            $pdf->SetXY(48, 114); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Cardíacas'), 'LR');
            $pdf->SetXY(48, 119); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Vasculares'), 'LR');
            $pdf->SetXY(48, 124); $pdf->MultiCell(40, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Queixas gastrointestinais'), 'LR');

            $pdf->SetXY(96, 94); $pdf->MultiCell(104, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Intoxicações Exógenas e Exposição a Agente Químico'), 'L');
            $pdf->SetXY(96, 99); $pdf->MultiCell(104, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Mordeduras ou acidentes com animais peçonhentos'), 'L');
            $pdf->SetXY(96, 104); $pdf->MultiCell(58, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Otorrinolaringológicas'), 'LR');
            $pdf->SetXY(96, 109); $pdf->MultiCell(58, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Dor Torácica'), 'LR');
            $pdf->SetXY(96, 114); $pdf->MultiCell(58, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Queimaduras'), 'LR');
            $pdf->SetXY(96, 119); $pdf->MultiCell(58, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Traumas'), 'LR');
            $pdf->SetXY(96, 124); $pdf->MultiCell(58, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações musculoesqueléticas '), 'LR');

            $pdf->SetXY(162, 104); $pdf->MultiCell(38, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações Glicêmicas'), 'L');
            $pdf->SetXY(162, 109); $pdf->MultiCell(38, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações dermatológicas'), 'L');
            $pdf->SetXY(162, 114); $pdf->MultiCell(38, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Queixas Geniturinárias'), 'L');
            $pdf->SetXY(162, 119); $pdf->MultiCell(38, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alterações comportamentais'), 'L');
            $pdf->SetXY(162, 124); $pdf->MultiCell(38, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Situações Especiais'), 'L');

            $pdf->SetFont('Times', 'B', 7);
            $pdf->SetXY(165, 144); $pdf->MultiCell(35, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Término da classificação'), 0, 'C');
            $pdf->SetXY(165, 147); $pdf->MultiCell(35, 5, ':', 0, 'C');

        //Dados
            $pdf->SetFont('Times', '', 10);
            $entry = explode(' ', $data->entry);
            //dd($date);
            $date = explode('-', $entry[0]);
            $time = explode(':', $entry[1]);

            $pdf->SetXY(152, 34); $pdf->MultiCell(11, 5, $data->id);

            $pdf->SetXY(34, 39); $pdf->MultiCell(11, 5, $date[2], 0, 'C');
            $pdf->SetXY(44.5, 39); $pdf->MultiCell(11, 5, $date[1], 0, 'C');
            $pdf->SetXY(56, 39); $pdf->MultiCell(11, 5, $date[0], 0, 'C');
            $pdf->SetXY(177, 39.25); $pdf->MultiCell(11.5, 5, $time[0], 0, 'C');
            $pdf->SetXY(188.5, 39.25); $pdf->MultiCell(11.5, 5, $time[1], 0, 'C');

            if($patient->id == 1){
                $pdf->SetXY(35.5, 44); $pdf->MultiCell(180, 5, $patient->name . date(' d/m/Y H:i:s', strtotime($data->entry)));
            } else {
                $pdf->SetXY(35.5, 44); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->social_name ? $patient->social_name : $patient->name));
            }

            if($patient->birth_date) {
                $bd = explode('-', $patient->birth_date);
                $pdf->SetXY(71, 49); $pdf->MultiCell(11, 5, $bd[2], 0, 'C');
                $pdf->SetXY(82.5, 49); $pdf->MultiCell(11, 5, $bd[1], 0, 'C');
                $pdf->SetXY(93, 49); $pdf->MultiCell(11, 5, $bd[0], 0, 'C');
                $pdf->SetXY(148, 49); $pdf->MultiCell(180, 5, intdiv(time() - strtotime($patient->birth_date), 365 * 86400));
            }

            $pdf->SetXY(32, 54); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->mother));

            $pdf->SetXY(30, 59); $pdf->MultiCell(180, 5, $patient->sus);
            $pdf->SetXY(143, 59); $pdf->MultiCell(180, 5, $patient->cpf);

            $pdf->SetXY(54, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $origin->title));
            $pdf->SetXY(172, 64); $pdf->MultiCell(180, 5, $patient->phone);

            $pdf->SetXY(46, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->street));
            $pdf->SetXY(182, 69); $pdf->MultiCell(180, 5, $patient->number);

            $pdf->SetXY(39, 74); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->district));
            $pdf->SetXY(109, 74); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->city));
            $pdf->SetXY(170, 74); $pdf->MultiCell(180, 5, $patient->state);


        $pdf->setTitle('BPA Adulto', 1);
        $pdf->Output('I', 'BPA Adulto.pdf', 1);

        exit;
    }

    public function bpa_pediatric($id) {
        $pdf = $this->fpdf;

        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(15, 10, 15);
        $data = Entry::findOrfail($id);
        $patient = Patient::findOrFail($data->patient);
        $origin = Origin::findOrFail($data->origin);

        if($data->type != 'pediatric') {
            if($data->type == 'adult')
                return redirect('entry/adult/' . $data->id);
            else
                return redirect('entry/obstetric/' . $data->id);
        }

        // Layout
            $pdf->SetLineWidth(1);
            $pdf->SetXY(10, 15); $pdf->Cell(190, 59, '', 1);
            $pdf->SetXY(10, 73); $pdf->Cell(190, 81, '', 'LBR');
            $pdf->SetXY(10, 153); $pdf->Cell(190, 134, '', 'LBR');

            $pdf->SetLineWidth(0.5);
            $pdf->RoundedRect(157, 80.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 86.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 92.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 98.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 104.75, 6, 4.75, 1);

            $pdf->SetLineWidth(0.2);
            $pdf->SetXY(155, 80); $pdf->Cell(45, 30, '', 'L');
            $pdf->SetXY(155, 130); $pdf->Cell(45, 24, '', 'L');
            $pdf->SetXY(20, 134); $pdf->Cell(135, 5, '', 'B');
            $pdf->SetXY(20, 139); $pdf->Cell(135, 5, '', 'B');
            $pdf->SetXY(20, 144); $pdf->Cell(135, 5, '', 'B');

            $pdf->SetXY(155, 256); $pdf->Cell(45, 24, '', 'LT');

            $pdf->Line(105, 38.5, 106, 34.5);
            $pdf->Line(116, 38.5, 117, 34.5);

            $pdf->Line(82, 48.5, 83, 44.5);
            $pdf->Line(93, 48.5, 94, 44.5);

            $pdf->Line(74, 84, 85.5, 84);
            $pdf->Line(88.5, 84, 100.5, 84);

            $pdf->Line(92, 104, 153, 104);

            $pdf->Line(33, 119, 52, 119);
            $pdf->Line(94, 119, 112, 119);

            $pdf->Line(28, 124, 42.5, 124);
            $pdf->Line(43.5, 124, 44.5, 119.75);
            $pdf->Line(45.5, 124, 60, 124);
            $pdf->Line(89, 124, 105, 124);
            $pdf->Line(152, 124, 165, 124);

            $pdf->Line(158, 146, 197, 146);

            $pdf->Line(158, 273, 197, 273);

        // Cabeçalho
            $pdf->Image(public_path() . '/img/logo.png', 25, 16, 40, 18);
            $pdf->Image(public_path() . '/img/amapa.png', 115, 16, 80, 18);

            $pdf->SetFont('Times', 'B', 16);
            $pdf->Rotate(90, 10, 74);
            $pdf->SetXY(10, 74); $pdf->MultiCell(40, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'RECEPÇÃO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->Rotate(90, 10, 154);
            $pdf->SetXY(10, 154); $pdf->MultiCell(80, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENFERMEIRO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->Rotate(90, 10, 277);
            $pdf->SetXY(0, 277); $pdf->MultiCell(133, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÉDICO'), 1, 'C');
            $pdf->Rotate(0);


        // Campos
            $pdf->SetFont('Times', 'B', 12);
            $pdf->SetXY(20, 34); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FICHA Nº:'), 'LTB');
            $pdf->SetXY(80, 34); $pdf->MultiCell(180, 5, 'DATA:');
            $pdf->SetXY(140, 34); $pdf->MultiCell(180, 5, 'HORA:');
            $pdf->SetXY(155, 34); $pdf->MultiCell(23, 5, ':', 0, 'C');
            $pdf->SetXY(20, 39); $pdf->MultiCell(180, 5, 'NOME:', 'LT');
            $pdf->SetXY(20, 44); $pdf->MultiCell(180, 5, 'DATA DE NASCIMENTO:', 'LT');
            $pdf->SetXY(132, 44); $pdf->MultiCell(180, 5, 'IDADE:');
            $pdf->SetXY(20, 49); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÃE:'), 'LT');
            $pdf->SetXY(20, 54); $pdf->MultiCell(180, 5, 'SUS:', 'LT');
            $pdf->SetXY(132, 54); $pdf->MultiCell(180, 5, 'CPF:');
            $pdf->SetXY(20, 59); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROCEDÊNCIA:'), 'LT');
            $pdf->SetXY(148, 59); $pdf->MultiCell(180, 5, 'CONTATO:');
            $pdf->SetXY(20, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO:'), 'LT');
            $pdf->SetXY(175, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Nº:'));
            $pdf->SetXY(20, 69); $pdf->MultiCell(180, 5, 'BAIRRO:', 'LT');
            $pdf->SetXY(90, 69); $pdf->MultiCell(180, 5, 'CIDADE:');
            $pdf->SetXY(150, 69); $pdf->MultiCell(180, 5, 'ESTADO:');

            $pdf->SetXY(20, 75); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ACOLHIMENTO E CLASSIFICAÇÃO DE RISCO PEDIÁTRICO'), 'B', 'C');
            $pdf->SetXY(165, 80); $pdf->MultiCell(35, 6, 'VERMELHO', 'R');
            $pdf->SetXY(165, 86); $pdf->MultiCell(35, 6, 'LARANJA', 'R');
            $pdf->SetXY(165, 92); $pdf->MultiCell(35, 6, 'AMARELO', 'R');
            $pdf->SetXY(165, 98); $pdf->MultiCell(35, 6, 'VERDE', 'R');
            $pdf->SetXY(165, 104); $pdf->MultiCell(35, 6, 'AZUL', 'R');
            $pdf->SetXY(155, 147); $pdf->MultiCell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENFº RESP.'), 0, 'C');

            $pdf->SetXY(20, 154); $pdf->MultiCell(180, 7, 'MOTIVO DO ATENDIMENTO:', 'B');
            $pdf->SetXY(20, 161); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'HISTÓTICO CLÍNICO:'), 'B');
            $pdf->SetXY(20, 168); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'EXAME FÍSICO:'), 'B');
            $pdf->SetXY(20, 175); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PRESCRIÇÃO MÉDICA:'), 0, 'C');
            $pdf->SetXY(20, 273); $pdf->MultiCell(135, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DIAGNÓSTICO:'), 'TB');
            $pdf->SetXY(155, 273); $pdf->MultiCell(45, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÉDICO/CRM'), 'B', 'C');
            $pdf->SetXY(20, 280); $pdf->MultiCell(180, 7, 'DESTINO DO PACIENTE:', 'B');

            $pdf->SetFont('Times', 'B', 11);
            $pdf->SetXY(20, 80); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'HORA DA CLASSIFICAÇÃO:'));
            $pdf->SetXY(85, 80); $pdf->MultiCell(135, 5, 'h');
            $pdf->SetXY(100, 80); $pdf->MultiCell(135, 5, 'm');

            $pdf->SetXY(20, 85); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Sinais de desitração? (    ) sim (    ) não'));
            $pdf->SetXY(100, 85); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Diarreia? (    ) sim (    ) não'));
            $pdf->SetXY(20, 90); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Vômitos? (    ) não (    ) sim'), 'B');
            $pdf->SetXY(72, 90); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Alergias? (    ) não (    ) sim/qual:'));

            $pdf->SetXY(20, 95); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ANTECEDENTES PEDIÁTRICOS'), 0, 'C');
            $pdf->SetXY(20, 100); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Síndromes?'));
            $pdf->SetXY(42, 100); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(   ) sim (   ) não'));
            $pdf->SetXY(80, 100); $pdf->MultiCell(135, 5, 'Qual?');
            $pdf->SetXY(20, 105); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Diabetes?'), 'B');
            $pdf->SetXY(42, 105); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(   ) sim (   ) não'));

            $pdf->SetXY(20, 110.5); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PARÂMETROS DE AVALIAÇÃO'), 0, 'C');
            $pdf->SetXY(20, 115); $pdf->MultiCell(135, 5, 'PESO:');
            $pdf->SetXY(52, 115); $pdf->MultiCell(135, 5, 'kg');
            $pdf->SetXY(80, 115); $pdf->MultiCell(180, 5, 'Altura:');
            $pdf->SetXY(112, 115); $pdf->MultiCell(180, 5, 'cm');

            $pdf->SetXY(20, 120); $pdf->MultiCell(180, 5, 'PA:');
            $pdf->SetXY(60, 120); $pdf->MultiCell(180, 5, 'mmHg');
            $pdf->SetXY(80, 120); $pdf->MultiCell(180, 5, 'Tax:');
            $pdf->SetXY(105, 120); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '°C'));
            $pdf->SetXY(140, 120); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'SatO²:'));
            $pdf->SetXY(165, 120); $pdf->MultiCell(180, 5, 'irpm');

            $pdf->SetXY(20, 125); $pdf->MultiCell(180, 5, 'FC:', 'B');
            $pdf->SetXY(46, 125); $pdf->MultiCell(180, 5, 'bpm');
            $pdf->SetXY(80, 125); $pdf->MultiCell(180, 5, 'FR:');
            $pdf->SetXY(105, 125); $pdf->MultiCell(180, 5, 'irpm');
            $pdf->SetXY(140, 125); $pdf->MultiCell(180, 5, 'Glicemia:');
            $pdf->SetXY(172, 125); $pdf->MultiCell(180, 5, 'mg/dl');

            $pdf->SetXY(20, 130); $pdf->MultiCell(135, 5, 'QUEIXAS', 0, 'C');

            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetXY(72, 280); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(     ) alta   (     ) internação  (    ) encaminhado para:'));


        //Dados
            $pdf->SetFont('Times', '', 10);
            $entry = explode(' ', $data->entry);
            $date = explode('-', $entry[0]);
            $time = explode(':', $entry[1]);

            $pdf->SetXY(38, 34); $pdf->MultiCell(11, 5, $data->id, 0, 'C');

            $pdf->SetXY(94, 34); $pdf->MultiCell(11, 5, $date[2], 0, 'C');
            $pdf->SetXY(105.5, 34); $pdf->MultiCell(11, 5, $date[1], 0, 'C');
            $pdf->SetXY(116, 34); $pdf->MultiCell(11, 5, $date[0], 0, 'C');

            $pdf->SetXY(155, 34.25); $pdf->MultiCell(11.5, 5, $time[0], 0, 'C');
            $pdf->SetXY(167.5, 34.25); $pdf->MultiCell(11.5, 5, $time[1], 0, 'C');

            if($patient->id == 1){
                $pdf->SetXY(35.5, 39); $pdf->MultiCell(180, 5, $patient->name . date(' d/m/Y H:i:s', strtotime($data->entry)));
            } else {
                $pdf->SetXY(35.5, 39); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->social_name ? $patient->social_name : $patient->name));
            }

            if($patient->birth_date) {
                $bd = explode('-', $patient->birth_date);
                $pdf->SetXY(71, 44); $pdf->MultiCell(11, 5, $bd[2], 0, 'C');
                $pdf->SetXY(82.5, 44); $pdf->MultiCell(11, 5, $bd[1], 0, 'C');
                $pdf->SetXY(93, 44); $pdf->MultiCell(11, 5, $bd[0], 0, 'C');
                $pdf->SetXY(148, 44); $pdf->MultiCell(180, 5, intdiv(time() - strtotime($patient->birth_date), 365 * 86400));
            }

            $pdf->SetXY(32, 49); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->mother));

            $pdf->SetXY(30, 54); $pdf->MultiCell(180, 5, $patient->sus);
            $pdf->SetXY(143, 54); $pdf->MultiCell(180, 5, $patient->cpf);

            $pdf->SetXY(54, 59); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $origin->title));
            $pdf->SetXY(172, 59); $pdf->MultiCell(180, 5, $patient->phone);

            $pdf->SetXY(46, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->street));
            $pdf->SetXY(182, 64); $pdf->MultiCell(180, 5, $patient->number);

            $pdf->SetXY(39, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->district));
            $pdf->SetXY(109, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->city));
            $pdf->SetXY(170, 69); $pdf->MultiCell(180, 5, $patient->state);


        $pdf->setTitle('BPA Pediátrico', 1);
        $pdf->Output('I', 'BPA Pediátrico.pdf', 1);

        exit;
    }

    public function bpa_obstetric($id) {
        $pdf = $this->fpdf;

        $pdf->AddPage();
        $pdf->SetAutoPageBreak(true, 10);
        $pdf->SetMargins(15, 10, 15);
        $data = Entry::findOrfail($id);
        $patient = Patient::findOrFail($data->patient);
        $origin = Origin::findOrFail($data->origin);

        if($data->type != 'obstetric') {
            if($data->type == 'adult')
                return redirect('entry/adult/' . $data->id);
            else
                return redirect('entry/pediatric/' . $data->id);
        }

        // Layout
            $pdf->SetLineWidth(1);
            $pdf->SetXY(10, 15); $pdf->Cell(190, 59, '', 1);
            $pdf->SetXY(10, 73); $pdf->Cell(190, 106, '', 'LBR');
            $pdf->SetXY(10, 178); $pdf->Cell(190, 109, '', 'LBR');

            $pdf->SetLineWidth(0.5);
            $pdf->RoundedRect(157, 80.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 86.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 92.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 98.75, 6, 4.75, 1);
            $pdf->RoundedRect(157, 104.75, 6, 4.75, 1);

            $pdf->SetLineWidth(0.2);
            $pdf->SetXY(155, 80); $pdf->Cell(45, 30, '', 'L');
            $pdf->SetXY(155, 155); $pdf->Cell(45, 24, '', 'L');
            $pdf->SetXY(20, 159); $pdf->Cell(135, 5, '', 'B');
            $pdf->SetXY(20, 164); $pdf->Cell(135, 5, '', 'B');
            $pdf->SetXY(20, 169); $pdf->Cell(135, 5, '', 'B');

            $pdf->SetXY(155, 256); $pdf->Cell(45, 24, '', 'LT');

            $pdf->Line(105, 38.5, 106, 34.5);
            $pdf->Line(116, 38.5, 117, 34.5);

            $pdf->Line(82, 48.5, 83, 44.5);
            $pdf->Line(93, 48.5, 94, 44.5);

            $pdf->Line(74, 84, 85.5, 84);
            $pdf->Line(88.5, 84, 100.5, 84);

            $pdf->Line(32, 94, 41, 94);
            $pdf->Line(42, 94, 43, 90);
            $pdf->Line(43, 94, 52, 94);
            $pdf->Line(53, 94, 54, 90);
            $pdf->Line(54, 94, 64, 94);

            $pdf->Line(76, 94, 85, 94);
            $pdf->Line(86, 94, 87, 90);
            $pdf->Line(87, 94, 96, 94);
            $pdf->Line(97, 94, 98, 90);
            $pdf->Line(98, 94, 108, 94);
            $pdf->Line(115, 94, 126, 94);
            $pdf->Line(134, 94, 144, 94);

            $pdf->Line(145.5, 119, 155, 119);
            $pdf->Line(178, 119, 192, 119);
            $pdf->Line(143, 125, 155, 125);
            $pdf->Line(177, 125, 192, 125);
            $pdf->Line(143, 131, 155, 131);
            $pdf->Line(180, 131, 192, 131);
            $pdf->Line(143, 137, 154.5, 137);
            $pdf->Line(155.5, 137, 156.5, 132);
            $pdf->Line(156.5, 137, 168, 137);
            $pdf->Line(152.5, 143, 168, 143);

            $pdf->Line(71.5, 149.5, 197, 149.5);

            $pdf->Line(158, 171, 197, 171);

            $pdf->Line(158, 273, 197, 273);

        // Cabeçalho
            $pdf->Image(public_path() . '/img/logo.png', 25, 16, 40, 18);
            $pdf->Image(public_path() . '/img/amapa.png', 115, 16, 80, 18);

            $pdf->SetFont('Times', 'B', 16);
            $pdf->Rotate(90, 10, 74);
            $pdf->SetXY(10, 74); $pdf->MultiCell(40, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'RECEPÇÃO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->Rotate(90, 10, 179);
            $pdf->SetXY(10, 179); $pdf->MultiCell(105, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENFERMEIRO'), 1, 'C');
            $pdf->Rotate(0);

            $pdf->Rotate(90, 10, 277);
            $pdf->SetXY(0, 277); $pdf->MultiCell(108, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÉDICO'), 1, 'C');
            $pdf->Rotate(0);

        // Campos
            $pdf->SetFont('Times', 'B', 12);
            $pdf->SetXY(20, 34); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'FICHA Nº:'), 'LTB');
            $pdf->SetXY(80, 34); $pdf->MultiCell(180, 5, 'DATA:');
            $pdf->SetXY(140, 34); $pdf->MultiCell(180, 5, 'HORA:');
            $pdf->SetXY(155, 34); $pdf->MultiCell(23, 5, ':', 0, 'C');
            $pdf->SetXY(20, 39); $pdf->MultiCell(180, 5, 'NOME:', 'LT');
            $pdf->SetXY(20, 44); $pdf->MultiCell(180, 5, 'DATA DE NASCIMENTO:', 'LT');
            $pdf->SetXY(132, 44); $pdf->MultiCell(180, 5, 'IDADE:');
            $pdf->SetXY(20, 49); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÃE:'), 'LT');
            $pdf->SetXY(20, 54); $pdf->MultiCell(180, 5, 'SUS:', 'LT');
            $pdf->SetXY(132, 54); $pdf->MultiCell(180, 5, 'CPF:');
            $pdf->SetXY(20, 59); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PROCEDÊNCIA:'), 'LT');
            $pdf->SetXY(148, 59); $pdf->MultiCell(180, 5, 'CONTATO:');
            $pdf->SetXY(20, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENDEREÇO:'), 'LT');
            $pdf->SetXY(175, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Nº:'));
            $pdf->SetXY(20, 69); $pdf->MultiCell(180, 5, 'BAIRRO:', 'LT');
            $pdf->SetXY(90, 69); $pdf->MultiCell(180, 5, 'CIDADE:');
            $pdf->SetXY(150, 69); $pdf->MultiCell(180, 5, 'ESTADO:');

            $pdf->SetXY(20, 75); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ACOLHIMENTO E CLASSIFICAÇÃO DE RISCO GINECOLÓGICO/OBSTÉTRICO'), 'B', 'C');
            $pdf->SetXY(165, 80); $pdf->MultiCell(35, 6, 'VERMELHO', 'R');
            $pdf->SetXY(165, 86); $pdf->MultiCell(35, 6, 'LARANJA', 'R');
            $pdf->SetXY(165, 92); $pdf->MultiCell(35, 6, 'AMARELO', 'R');
            $pdf->SetXY(165, 98); $pdf->MultiCell(35, 6, 'VERDE', 'R');
            $pdf->SetXY(165, 104); $pdf->MultiCell(35, 6, 'AZUL', 'R');
            $pdf->SetXY(155, 172); $pdf->MultiCell(45, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ENFº RESP.'), 0, 'C');

            $pdf->SetXY(20, 179); $pdf->MultiCell(180, 7, 'MOTIVO DO ATENDIMENTO:', 'B');
            $pdf->SetXY(20, 186); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'HISTÓTICO CLÍNICO:'), 'B');
            $pdf->SetXY(20, 193); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'EXAME FÍSICO:'), 'B');
            $pdf->SetXY(20, 200); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PRESCRIÇÃO MÉDICA:'), 0, 'C');
            $pdf->SetXY(20, 273); $pdf->MultiCell(135, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'DIAGNÓSTICO:'), 'TB');
            $pdf->SetXY(155, 273); $pdf->MultiCell(45, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'MÉDICO/CRM'), 'B', 'C');
            $pdf->SetXY(20, 280); $pdf->MultiCell(180, 7, 'DESTINO DO PACIENTE:', 'B');

            $pdf->SetFont('Times', 'B', 11);
            $pdf->SetXY(20, 80); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'HORA DA CLASSIFICAÇÃO:'));
            $pdf->SetXY(85, 80); $pdf->MultiCell(135, 5, 'h');
            $pdf->SetXY(100, 80); $pdf->MultiCell(135, 5, 'm');

            $pdf->SetXY(20, 85); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'É GESTANTE? (   ) Sim   (   ) Não   (   ) Incerteza   (   ) Puérpera'));
            $pdf->SetXY(20, 90); $pdf->MultiCell(135, 5, 'DUM:');
            $pdf->SetXY(65, 90); $pdf->MultiCell(135, 5, 'DPP:');
            $pdf->SetXY(108, 90); $pdf->MultiCell(135, 5, 'IG:');
            $pdf->SetXY(126, 90); $pdf->MultiCell(135, 5, 'sem');
            $pdf->SetXY(144, 90); $pdf->MultiCell(135, 5, 'dias');
            $pdf->SetXY(20, 95); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Nº DE CONS. NO PRÉ NATAL:'), 'B');
            $pdf->SetXY(89, 95); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PUXOU BARRIGA? (   ) Sim (   ) Não'));

            $pdf->SetXY(20, 100); $pdf->MultiCell(135, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ANTECEDENTES OBSTÉTRICOS:'), 0, 'C');
            $pdf->SetXY(20, 105); $pdf->MultiCell(180, 5, 'G:', 'B');
            $pdf->SetXY(34, 105); $pdf->MultiCell(180, 5, 'P:');
            $pdf->SetXY(48, 105); $pdf->MultiCell(180, 5, 'A:');
            $pdf->SetXY(69, 105); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(   ) Parto Normal   (   ) Parto Cesáreo'));

            $pdf->SetXY(20, 110.5); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PARÂMETROS DE AVALIAÇÃO'), 0, 'C');
            $pdf->SetXY(20, 115); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'CONTRAÇÕES UTERINAS?  (   ) Sim   (   ) Não'));
            $pdf->SetXY(20, 120); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'PERDA DE LÍQUIDO?  (   ) Sim    (   ) Não'));
            $pdf->SetXY(20, 125); $pdf->MultiCell(180, 5, 'ASPECTO: (   ) Claro  (   ) Meconial Fluido  (   ) Meconial Espesso');
            $pdf->SetXY(20, 130); $pdf->MultiCell(180, 5, 'SANGRAMENTO VAGINAL?  (   ) Ausente   (   ) Presente');
            $pdf->SetXY(20, 135); $pdf->MultiCell(180, 5, 'INTENSIDADE:  (   ) Leve  (   ) Moderado  (   ) Intenso');
            $pdf->SetXY(20, 140); $pdf->MultiCell(180, 5, 'MOVIMENTOS FETAIS?   (   ) Ausente     (   ) Presente', 'B');

            $pdf->SetXY(135, 115); $pdf->MultiCell(180, 5, 'BCF:');
            $pdf->SetXY(155, 115); $pdf->MultiCell(180, 5, 'bpm');
            $pdf->SetXY(168, 115); $pdf->MultiCell(180, 5, 'A.U.:');
            $pdf->SetXY(192, 115); $pdf->MultiCell(180, 5, 'cm');

            $pdf->SetXY(135, 121); $pdf->MultiCell(180, 5, 'FC:');
            $pdf->SetXY(155, 121); $pdf->MultiCell(180, 5, 'bpm');
            $pdf->SetXY(168, 121); $pdf->MultiCell(180, 5, 'Tax:');
            $pdf->SetXY(192, 121); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '°C'));

            $pdf->SetXY(135, 127); $pdf->MultiCell(180, 5, 'FR:');
            $pdf->SetXY(155, 127); $pdf->MultiCell(180, 5, 'irpm');
            $pdf->SetXY(168, 127); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'SatO²:'));
            $pdf->SetXY(192, 127); $pdf->MultiCell(180, 5, '%');

            $pdf->SetXY(135, 133); $pdf->MultiCell(180, 5, 'PA:');
            $pdf->SetXY(168, 133); $pdf->MultiCell(180, 5, 'mmHg');
            $pdf->SetXY(135, 139); $pdf->MultiCell(180, 5, 'Glicemia:');
            $pdf->SetXY(168, 139); $pdf->MultiCell(180, 5, 'mg/dl');

            $pdf->SetXY(20, 145); $pdf->MultiCell(180, 5, 'MEDICAMENTOS EM USO:');
            $pdf->SetXY(20, 150); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'ALERGIAS? (   ) Não   (   ) Sim,'), 'B');
            $pdf->SetXY(115, 150); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(   ) DROGAS   (   ) VÍTIMA DE VIOLÊNCIA'));

            $pdf->SetXY(20, 155); $pdf->MultiCell(135, 5, 'QUEIXAS', 0, 'C');

            $pdf->SetFont('Times', 'B', 10);
            $pdf->SetXY(72, 280); $pdf->MultiCell(180, 7, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '(     ) alta   (     ) internação  (    ) encaminhado para:'));


        //Dados
            $pdf->SetFont('Times', '', 10);
            $entry = explode(' ', $data->entry);
            $date = explode('-', $entry[0]);
            $time = explode(':', $entry[1]);

            $pdf->SetXY(38, 34); $pdf->MultiCell(11, 5, $data->id, 0, 'C');

            $pdf->SetXY(94, 34); $pdf->MultiCell(11, 5, $date[2], 0, 'C');
            $pdf->SetXY(105.5, 34); $pdf->MultiCell(11, 5, $date[1], 0, 'C');
            $pdf->SetXY(116, 34); $pdf->MultiCell(11, 5, $date[0], 0, 'C');

            $pdf->SetXY(155, 34.25); $pdf->MultiCell(11.5, 5, $time[0], 0, 'C');
            $pdf->SetXY(167.5, 34.25); $pdf->MultiCell(11.5, 5, $time[1], 0, 'C');

            if($patient->id == 1){
                $pdf->SetXY(35.5, 39); $pdf->MultiCell(180, 5, $patient->name . date(' d/m/Y H:i:s', strtotime($data->entry)));
            } else {
                $pdf->SetXY(35.5, 39); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->social_name ? $patient->social_name : $patient->name));
            }

            if($patient->birth_date) {
                $bd = explode('-', $patient->birth_date);
                $pdf->SetXY(71, 44); $pdf->MultiCell(11, 5, $bd[2], 0, 'C');
                $pdf->SetXY(82.5, 44); $pdf->MultiCell(11, 5, $bd[1], 0, 'C');
                $pdf->SetXY(93, 44); $pdf->MultiCell(11, 5, $bd[0], 0, 'C');
                $pdf->SetXY(148, 44); $pdf->MultiCell(180, 5, intdiv(time() - strtotime($patient->birth_date), 365 * 86400));
            }

            $pdf->SetXY(32, 49); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->mother));

            $pdf->SetXY(30, 54); $pdf->MultiCell(180, 5, $patient->sus);
            $pdf->SetXY(143, 54); $pdf->MultiCell(180, 5, $patient->cpf);

            $pdf->SetXY(54, 59); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $origin->title));
            $pdf->SetXY(172, 59); $pdf->MultiCell(180, 5, $patient->phone);

            $pdf->SetXY(46, 64); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->street));
            $pdf->SetXY(182, 64); $pdf->MultiCell(180, 5, $patient->number);

            $pdf->SetXY(39, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->district));
            $pdf->SetXY(109, 69); $pdf->MultiCell(180, 5, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $patient->city));
            $pdf->SetXY(170, 69); $pdf->MultiCell(180, 5, $patient->state);


        $pdf->setTitle('BPA Obstétrico', 1);
        $pdf->Output('I', 'BPA Obstétrico.pdf', 1);

        exit;
    }
}
