<?php

function signature(){
	if ( isset($_POST['canvas']) ) {
		$canvas = $_POST['canvas'];
		$img = str_replace('data:image/png;base64,', '', $canvas);
		$img = str_replace(' ', '+', $img);
		$fileData = base64_decode($img);
		$image = file_put_contents('signature.png', $fileData);

		if (!file_exists('signature.png')) return false;
		
	}
}



if (isset($_POST['save'])) {
	signature();
}
if (isset($_GET['save'])) {
	header("Content-Description: File Transfer");
	header("Content-Disposition: attachment; filename='" . basename('signature.png') . "'"); 
	readfile('signature.png');
	unlink('signature.png');
}

	

if ( isset($_POST['submit']) ) {
	/*$empty = [];
	foreach ($_POST as $name => $value) {
		if ( empty($value) ) {
			$empty[] = $name;
		}
	}

	if ( count($empty)> 0 ) {
		echo json_encode([
			'error' => true,
			'type' => 'empty',
			'fields' => $empty
		]);
		die();
	}*/

	require('fpdf/fpdf.php');


	$pdf = new FPDF();
	$pdf->AddPage();

	$titles = $_POST['titles'];
	$subjects = $_POST['subjects'];

	for ($i=0; $i < count($titles) ; $i++) { 
		// making the pdf
		$pdf->SetFont('Arial','B',18);
		$pdf->cell(40,10, $titles[$i]);
		$pdf->Multicell(40,10, "");
		// $pdf->SetX(1);

		// $pdf->Multicell(20,10, "");

		$pdf->SetFont('Arial','I',12);
		$pdf->cell(0,10, $subjects[$i]);
		$pdf->Multicell(40,10, "");
	}

	if ( $_POST['canvas'] ) {

		signature();
		$pdf->SetFont('Arial','B',18);
		$pdf->Multicell(40,10, "");
		$pdf->cell(0,10, "Signature:");
		$pdf->Multicell(40,10, "");
		$pdf->cell(0,0, $pdf->Image('signature.png'));
		unlink('signature.png');
	}
		
	
		$pdf->Output(); //downlods pdf


	echo json_encode(['error'=>false]);

}
