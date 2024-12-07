<?php
// Include the main TCPDF library (search for installation path).
require_once('TCPDF-main/tcpdf.php');

// Extend TCPDF with custom functions
class MYPDF extends TCPDF {

    public function LoadData($startDate, $endDate, $salonId) {
        include __DIR__ . '/db_connect.php'; // Use absolute path
        
        if (!$conn) {
            error_log("Connection failed!", 3, "errors.log");
            exit;
        }

        // SQL query to join book and services
        $select = "SELECT b.bookID, b.ownerID, b.petid, b.salonid, 
                          GROUP_CONCAT(s.servicename SEPARATOR ', ') AS servicenames, 
                          b.date, b.time, b.paymentmethod, b.is_cancelled, 
                          b.cancel_date, b.status, b.paymentprice 
                   FROM book b 
                   JOIN services s ON FIND_IN_SET(s.serviceid, b.serviceid) > 0 
                   WHERE b.date BETWEEN ? AND ? AND b.salonid = ? 
                   GROUP BY b.bookID";
        
        $stmt = $conn->prepare($select);
        $stmt->bind_param("ssi", $startDate, $endDate, $salonId);
        $stmt->execute();
        $result = $stmt->get_result();

        $data = [];
        while ($row = $result->fetch_assoc()) {
            $data[] = $row; // Push each row into the data array
        }
        return $data; // Return the data array
    }

    // Colored table
    public function ColoredTable($header, $data) {
        // Colors, line width and bold font
        $this->SetFillColor(255, 0, 0);
        $this->SetTextColor(255);
        $this->SetDrawColor(128, 0, 0);
        $this->SetLineWidth(0.3);
        $this->SetFont('', 'B', 10); // Set a larger font size for the header
    
        // Header
        $w = array(15, 18, 18, 25, 60, 25, 25, 30, 25, 25, 18, 26); // Adjusted widths
        $num_headers = count($header);
        for ($i = 0; $i < $num_headers; ++$i) {
            $this->Cell($w[$i], 7, $header[$i], 1, 0, 'C', 1);
        }
        $this->Ln();
    
        // Color and font restoration
        $this->SetFillColor(224, 235, 255);
        $this->SetTextColor(0);
        $this->SetFont('', '', 8); // Set smaller font size for the table data
    
        // Data
        $fill = 0;
        foreach ($data as $row) {
            $this->Cell($w[0], 6, htmlspecialchars($row['bookID']), 'LR', 0, 'L', $fill);
            $this->Cell($w[1], 6, htmlspecialchars($row['ownerID']), 'LR', 0, 'L', $fill);
            $this->Cell($w[2], 6, htmlspecialchars($row['petid']), 'LR', 0, 'L', $fill);
            $this->Cell($w[3], 6, htmlspecialchars($row['salonid']), 'LR', 0, 'L', $fill);
            $this->Cell($w[4], 6, htmlspecialchars($row['servicenames']), 'LR', 0, 'L', $fill); // Displaying concatenated service names
            $this->Cell($w[5], 6, htmlspecialchars($row['date']), 'LR', 0, 'L', $fill);
            $this->Cell($w[6], 6, htmlspecialchars($row['time']), 'LR', 0, 'L', $fill);
            $this->Cell($w[7], 6, htmlspecialchars($row['paymentmethod']), 'LR', 0, 'L', $fill);
            $this->Cell($w[8], 6, htmlspecialchars($row['is_cancelled']), 'LR', 0, 'L', $fill);
            $this->Cell($w[9], 6, htmlspecialchars($row['cancel_date']), 'LR', 0, 'L', $fill); // Removed extra space
            $this->Cell($w[10], 6, htmlspecialchars($row['status']), 'LR', 0, 'L', $fill);
            $this->Cell($w[11], 6, htmlspecialchars($row['paymentprice']), 'LR', 0, 'L', $fill);
            $this->Ln();
            $fill = !$fill; // Toggle fill color
        }
        $this->Cell(array_sum($w), 0, '', 'T'); // Bottom border
    }
}

// Create new PDF document with landscape orientation
$pdf = new MYPDF('L', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

// Set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Your Name');
$pdf->SetTitle('Service Report');
$pdf->SetSubject('Service Report');
$pdf->SetKeywords('TCPDF, PDF, report, services');

// Set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// Set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// Set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// Set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// Set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// Add a page
$pdf->AddPage();

// Get data from the database
$startDate = $_GET['startDate'];
$endDate = $_GET['endDate'];
$salonId = $_GET['salonid'];
$data = $pdf->LoadData($startDate, $endDate, $salonId);

// Define the header
$header = array('Book ID', 'Owner ID', 'Pet ID', 'Salon ID', 'Service Names', 'Date', 'Time', 'Payment Method', 'Is Cancelled', 'Cancel Date', 'Status', 'Payment Price');

// Print colored table
$pdf->ColoredTable($header, $data);

// Close and output PDF document
$pdf->Output('service_report.pdf', 'I');
?>