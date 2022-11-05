<?php
namespace cgc\platform\libs;
use FPDF;

class ExportDemand extends FPDF {

    public function generate(array $data)
    {

        $place = $data['localisation'];
        $place == 'escs' ? $place = "à l'école superieur de commerce" : "à l'extérieur du campus";
        $type_reservation = $data['type_event'];
        if($type_reservation == 'stand') $type_reservation =  'un stand'; else $type_reservation= 'la salle';
        $reservation = $data['salle'];
        if($data['type_event']== 'stand') $reservation = '';
    
         $pdf = new FPDF();
        $pdf->AddPage();
        $pdf->Image("app/public/escs_main.jpg", 85, 4, 47,35);
        $pdf->SetFont('Arial','B',22);
        $pdf->Cell(200,110,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', 'Demande de réservation'),0,1,'C');
        $pdf->SetFont('Arial','I',11);
        $pdf->Text(20,100,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE', "Chère administration"));
        $pdf->Text(20,115,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Je vous présente le président du ".$data['nom']));
        $pdf->Text(20,130,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"j'ai l'honneur de vous informer que je souhaite réserver ".$type_reservation.' '.$reservation ." située à"));
        $pdf->Text(20,145,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',$place." le ".$data['date']." heure du début ".$data['hd']." à heure de la fin ".$data['hf']));
        $pdf->Text(20,160,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Dans le but de faire ".$data['type_event']." pour notre ".$data['club_type']));

        $pdf->SetFont('Arial','B',12);
        $pdf->Text(20,207,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Matériels:"));

        $data_show = $data['datashow'];
        $hautparleur = $data['hautparleur'];
        $stands = $data['stands'];
        $data_show  == 0 ?  $data_show = 'D' : $data_show = 'F';
        $hautparleur  == 0 ?  $hautparleur = 'D' : $hautparleur = 'F';
        $stands  == 0 ?  $stands = 'D' : $stands = 'F';
        $pdf->Rect(20,220,5,5,$data_show);
        $pdf->SetFont('Arial','I',11);
        $pdf->Text(30,224,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Datashow"));
        $pdf->Rect(20,230,5,5,$hautparleur);
        $pdf->Text(30,234,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Hautparleur"));
        $pdf->Rect(20,240,5,5,$stands);
        $pdf->Text(30,244,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Stands"));
        $pdf->Text(30,264,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Signature du président"));
        $pdf->Text(130,264,iconv('UTF-8', 'ISO-8859-1//TRANSLIT//IGNORE',"Signature du adminstration"));

        $pdf->Output('I','filename2.pdf',false);
        ob_end_flush();
    }

}


?>
