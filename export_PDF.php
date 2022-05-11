<?php
ob_start();
require_once('TCPDF/tcpdf.php');
require_once 'config/config.php';

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        $image_file = K_PATH_IMAGES.'logo.png';
        $this->Image($image_file, 10, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('freeserif', 'B', 18);
		$html = '<div style="text-align:center"><h5>République algérienne démocratique et populaire</h5>
<h5>Ministère de l\'enseignement supérieur et de la recherche scientifique</h5>
<h6>Université de Ghardaïa</h6><img style ="width:64px;height:64px" src="assets/logo.png" alt="logo"><div></div>'; 
        $this->writeHTML($html, true, false, true, false, ''); 
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        // Page number
        $this->Cell(0, 10, date("d-m-Y  H:i:s", time()), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
$username = filter_input(INPUT_GET, 'username');
$db = getDbInstance();
    $db->where('username', '%' . $username . '%', 'like');
  //  $db->where('attribute', '%Password%', 'like');    

$users=$db->get("radcheck");
// create new PDF document
$output = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
//----------------------------------------------------------------------------------------------------

//---------------------------------------------------------------------------------------------------------------------------			

// set document information
$output->SetCreator(PDF_CREATOR);
$output->SetAuthor('Université de Ghardaia');
$output->SetTitle('Convocation');
$output->SetSubject('Concours');
$output->SetKeywords('TCPDF, PDF, example, test, guide');

// set default header data
$output->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);

// set header and footer fonts
$output->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$output->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$output->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$output->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$output->SetHeaderMargin(PDF_MARGIN_HEADER);
$output->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$output->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$output->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $output->setLanguageArray($l);
}

// ---------------------------------------------------------

// set font
//$output->SetFont('Calibri', '', 10);

// add a page
$output->setFont('freeserif',' ',18);
foreach ($users as $user) {
    # code...

$output->AddPage();

// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)

// create some HTML content
/*$html = '<p>
M/Mme : '.$prenom_fr.' '.$nom_fr.' <br>
Né \(e\) le : '.$date_naiss.'<br><br>
diplome délivré par:<div style = "direnction:rtl;">'. $etablissement_dipl.'</div><br>
Spécialité:<div style = "direnction:rtl;"> '. $id_spec.'</div><br><br><br>

Nous vous informons que votre dossier de candidature a bien été reçu.


</p>
';
$output->writeHTMLCell(0,12,20,130,$html, 0, 2, 0, true, '',true);*/
//echo $prenom_fr;
$output->MultiCell(50, 25,'Nom utilisateur : ', 0, 'L', 0, 0, 20, 120, true, 0, false, true, 40, 'M');
$output->MultiCell(150, 25,$user['username'], 0, 'C', 0, 0, 50, 120, true, 0, false, true, 40, 'M');

$output->MultiCell(50, 25,'Mot de passe : ', 0, 'L', 0, 0, 20, 135, true, 0, false, true, 40, 'M');
$output->MultiCell(150, 25,$user['value'], 0, 'C', 0, 0, 50, 135, true, 0, false, true, 40, 'M');

// $style = array(
//     'border' => 2,
//     'vpadding' => 'auto',
//     'hpadding' => 'auto',
//     'fgcolor' => array(0,0,0),
//     'bgcolor' => false, //array(255,255,255)
//     'module_width' => 1, // width of a single module in points
//     'module_height' => 1 // height of a single module in points
// );

// QRCODE,L : QR-CODE Low error correction
//$output->write2DBarcode( 'Candidat : '.$nom_fr.' '.$prenom_fr.' dossier n°:'.$user_id, 'QRCODE,L', 10, 252, 20, 20, $style, 'N');
//$output->Text(20, 25, 'QRCODE L');

}


$output->lastPage();
$output->Output('Convocation.output', 'I');
ob_end_flush();
?>