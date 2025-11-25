<?php
require_once('vendor/tecnickcom/tcpdf/tcpdf.php');

function generate_pdf($title, $headers, $data, $reportType) {
    // create new PDF document
    $pdf = new MYPDF('P', 'mm', 'A4', true, 'UTF-8', false);

    // set document information
    $pdf->SetCreator('TCPDF');
    $pdf->SetAuthor('Student Attendance System');
    $pdf->SetTitle($title);
    $pdf->SetSubject($title);

    // set default header data
    $pdf->SetHeaderData('', 0, $title, 'Generated on: ' . date('Y-m-d H:i:s'));

    // set header and footer fonts
    $pdf->setHeaderFont(Array('helvetica', '', 10));
    $pdf->setFooterFont(Array('helvetica', '', 8));

    // set default monospaced font
    $pdf->SetDefaultMonospacedFont('courier');

    // set margins
    $pdf->SetMargins(15, 27, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    // set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 25);

    // set image scale factor
    $pdf->setImageScale(1.25);

    // ---------------------------------------------------------

    // add a page
    $pdf->AddPage();

    // set font
    $pdf->SetFont('helvetica', '', 10);

    // column titles
    $header = $headers;

    // data loading
    $data = $data;

    // print colored table
    $pdf->ColoredTable($header, $data, $reportType);

    // ---------------------------------------------------------

    // close and output PDF document
    $pdf->Output($title . '.pdf', 'I');
}

class MYPDF extends TCPDF {
    // Colored table
    public function ColoredTable($header, $data, $reportType) {
        // Colors, line width and bold font
        $this->SetFillColor(15, 76, 117);
        $this->SetTextColor(255);
        $this->SetDrawColor(15, 76, 117);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B');
        // Header
        $w = array();
        $num_headers = count($header);
        if ($reportType == 'overall_system_report') {
            $w = array(90, 90);
        } else {
            for($i = 0; $i < $num_headers; ++$i) {
                $w[] = (180/$num_headers);
            }
        }
        for($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('');
        // Data
        $fill = 0;
        foreach($data as $row) {
            $row = (array) $row;
            $rowHeight = 0;
            $i = 0;
            foreach($row as $col) {
                if(isset($w[$i])){
                    $rowHeight = max($rowHeight, $this->getStringHeight($w[$i], $col));
                }
                $i++;
            }

            $i = 0;
            foreach($row as $col){
                if(isset($w[$i])){
                    $this->MultiCell($w[$i], $rowHeight, $col, 'LR', 'L', $fill, 0, '', '', true, 0, false, true, $rowHeight, 'M');
                }
                $i++;
            }
            $this->Ln();
            $fill=!$fill;
        }
        $this->Cell(array_sum($w), 0, '', 'T');
    }
}
