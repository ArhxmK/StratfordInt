<?php
require_once('../vendor/tecnickcom/tcpdf/tcpdf.php');

class MyTCPDF extends TCPDF {
    // Page header
    public function Header() {
        // Custom Logo
        $image_file = __DIR__.'/../img/logo.png'; // Corrected path to your custom logo
        if (file_exists($image_file)) {
            $this->Image($image_file, 15, 10, 15, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        } else {
            // Print the image path for debugging
            $this->SetFont('helvetica', 'I', 10);
            $this->Cell(0, 15, 'Image not found: ' . $image_file, 0, false, 'C', 0, '', 0, false, 'M', 'M');
        }
        
        // Set font
        $this->SetFont('helvetica', 'B', 20);
        
        // Title with adjusted position
        $this->Cell(0, 15, 'Admission Record', 0, 1, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(-10); // Adjust position
        
        // Subtitle
        $this->SetFont('helvetica', '', 10);
        $this->Ln();
        $this->Cell(0, 10, 'Generated on: ' . date('Y-m-d'), 0, 1, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        
        // Set font
        $this->SetFont('helvetica', 'I', 8);
        
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
?>
