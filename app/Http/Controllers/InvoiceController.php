<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;
use Illuminate\Support\Facades\DB;


class InvoiceController extends Controller
{
    public function yoga(Request $request)
    {
        error_reporting(0);
        $leadId = $request->query('id');
        $renewAmount = $request->query('renew_amount') ?? '';

        $customerData = $this->getYoga($leadId)['data'];
        $customer_amount = $renewAmount;

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Yogintra');
        $pdf->SetTitle('Customer Invoice');
        $pdf->SetSubject('');

        // set header and footer fonts
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(20, 35, 20);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set font
        $pdf->SetFont('dejavusans', '', 10);

        // disable default header and footer
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        // add a page
        $pdf->AddPage();

        $paymentType = ($customerData['payment_type'] === 'Partition Payment') ? 'Partition Payment' : 'Full Pay';
        $dueAmount = (float)$customerData['package'] - (float)$customer_amount;
        $otherCharges = (float)$customer_amount * 0.03;

        // create HTML content
        $html = view('invoice.yoga', compact('customerData', 'customer_amount', 'paymentType', 'dueAmount', 'otherCharges'))->render();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, 'I');

        $pdf->Output('invoice_yoga.pdf', 'I');
    }

    public function getYoga($id)
    {
        if ($id) {
            $resp = DB::table('yoga')->find($id);

            if ($resp) {
                $respArray = (array) $resp; // Correct way to convert stdClass to array
                $respArray['paymentDetails'] = $this->getPayments($id);

                return [
                    'success' => 1,
                    'data' => $respArray
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => 'No data found!'
                ];
            }
        } else {
            abort(500, 'Internal Server Error!');
        }
    }


    public function getPayments($leadId)
    {
        return DB::table('paymentdata')->where([
            'status' => 1,
            'leadId' => $leadId,
            'type' => 'event'
        ])->get()->toArray();
    }
}
