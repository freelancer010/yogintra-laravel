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

    public function getBookingProfile($id)
    {
        if ($id) {
            $resp = DB::table('events')->where('id', $id)->first();

            if ($resp) {
                $resp = (array) $resp; // convert stdClass to array
                $resp['paymentDetails'] = $this->getPayments($id);

                return [
                    'success' => 1,
                    'data' => $resp
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => 'No data found!'
                ];
            }
        } else {
            abort(500, "Internal Server Error!");
        }
    }

    // Generate PDF Invoice
    public function event(Request $request)
    {
        $leadId = $request->query('id');

        $bookingProfile = $this->getBookingProfile($leadId);
        if ($bookingProfile['success'] == 0) {
            abort(404, 'Booking not found!');
        }
        $customerData = $bookingProfile['data'];

        // Setup TCPDF
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Yogintra');
        $pdf->SetTitle('Customer Invoice');
        $pdf->SetSubject('');
        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
        $pdf->SetMargins(20, 35, 20);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
        $pdf->SetFont('dejavusans', '', 10);
        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);
        $pdf->AddPage();

        $baseUrl = asset('assets/');

        // Build the invoice HTML
        $html = view('invoice.event', compact('customerData', 'baseUrl'))->render();

        $pdf->writeHTML($html, true, false, true, false, 'I');
        $pdf->Output('invoice.pdf', 'I');
    }

    public function getLeadById($id)
    {
        if ($id) {
            $resp = DB::table('leads')->where('id', $id)->first();

            if ($resp) {
                return [
                    'success' => 1,
                    'data' => (array) $resp
                ];
            } else {
                return [
                    'success' => 0,
                    'message' => 'No data found!'
                ];
            }
        } else {
            abort(400, "Bad Request: ID is required.");
        }
    }

    public function index(Request $request)
    {
        error_reporting(0);

        $leadId = $request->query('id');
        $renewAmount = $request->query('renew_amount');

        $customerData = $this->getLeadById($leadId)['data'];

        if (!$renewAmount) {
            $renewAmount = $customerData['full_payment'];
        }

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Yogintra');
        $pdf->SetTitle('Customer Invoice');
        $pdf->SetSubject('');

        $pdf->setHeaderFont([PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN]);
        $pdf->setFooterFont([PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA]);

        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        $pdf->SetMargins(20, 35, 20);
        $pdf->SetHeaderMargin(0);
        $pdf->SetFooterMargin(0);

        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->SetFont('dejavusans', '', 10);

        $pdf->SetPrintHeader(false);
        $pdf->SetPrintFooter(false);

        $pdf->AddPage();

        $customer_amount = $renewAmount;
        $due_amount = ((int) $customerData['package'] * (int) $customerData['quotation']) - (int) $customer_amount;

        $html = view('invoice.lead', compact('customerData', 'leadId', 'due_amount', 'customer_amount'))->render();

        // output the HTML content
        $pdf->writeHTML($html, true, false, true, false, 'I');

        // Close and output PDF document
        $pdf->Output('customer_invoice.pdf', 'I');
    }
}
