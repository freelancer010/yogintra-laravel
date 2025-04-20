<?php

class Invoice extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		error_reporting(0);
		require_once(APPPATH . 'helpers/tcpdf/tcpdf.php');

		$leadId = $_GET['id'];
		$customerData = $this->getLeadById($leadId)['data'];
		$renewAmount = $_GET['renew_amount'] ?? $customerData['full_payment'];

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Yogintra');
		$pdf->SetTitle('customer Invoice');
		$pdf->SetSubject('');

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(20, 35, 20);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('dejavusans', '', 10);

		// Call before the addPage() method
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		// add a page
		$pdf->AddPage();
		
//      Ankan -- Commented for renew amount 0
// 		$customer_amount = !empty($renewAmount) ? $renewAmount : $customerData['full_payment'];

		$customer_amount = $renewAmount;

		$due_amount = ((int) $customerData['package'] * (int) $customerData['quotation']) - (int) $customer_amount;
		// create some HTML content
		$html = '<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td class="">
								<div>
									<img src="' . base_url('assets/') . 'logo.jpg" alt="Company Logo" style="max-width: 100%;">
								</div>
							</td>
							<td> 
								<table style="text-align:right; table-layout: fixed; width: 100%;">
									<tbody>
										<tr>
											<td>
												<strong style="padding: 1cm !important; text-transform: uppercase;background-color:#ddd">Invoice - </strong><span style="background-color:#ddd">#YI' . $_GET['id'] . '</span>
												<div style="width:100%">Pay Date : ' . substr($customerData['totalPayDate'], 0, 10) . '
												<br/>Bil Date : ' . substr($customerData['created_date'], 0, 10) . '</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<strong style="padding: 1cm !important; text-transform: uppercase;">Yogintra</strong>
								<div style="width:100% ; font-size:10px">
									Shivila Apt D/408 <br/>Mumbra Devi Colony Road <br/>Diva East Thane Mumbai -400612<br/>Phone: <a style="text-decoration:none" href="tel:919867291573">+91-9867 291 573</a>
									<br/>Email: <a style="text-decoration:none" href="mailto:support@yogintra.com">support@yogintra.com</a>
									<br/>Website: <a style="text-decoration:none" href="www.yogintra.com">www.yogintra.com</a>
								</div>
							</td>
							<td style="margin-left:0%"></td>
							<td> 
								<table style="text-align:left;,margin-left:20px; table-layout: fixed; width: 100%;">
									<tbody>
										<tr>
											<td>
												<strong style="padding: 1cm !important; text-transform: uppercase;">Bill To</strong>
												<div style="font-size:12px;width:100%">' . $customerData['name'] . '
												<br/>' . $customerData['country'] . ', ' . $customerData['state'] . ', ' . $customerData['city'] . '
												<br/>' . $customerData['number'] . '
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<div>
					<table style="table-layout: fixed; width: 86%;" cellpadding="5">
						<thead>
							<tr>
								<th   width="40%" align="left" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
									<strong>Class Type</strong>
								</th>
								<th  align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
									<strong>Package</strong>
								</th>
								<th  align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
									<strong>Rate</strong>
								</th>
								<th  align="right" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
									<strong>Total</strong>
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="border-top: 1px solid #eee; width:38%;padding: 5px;">
								' . $customerData['class_type'] . '
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								' . $customerData['package'] . '
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								₹' . $customerData['quotation'] . '
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
								₹' . (int) $customerData['package'] * (int) $customerData['quotation'] . '
								</td>
							</tr>
							
							<tr>
								<td style="border-top: 1px solid #eee; width:38%;padding: 5px;">
								
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								+Other Charges
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
								₹' . ((int) $customerData['package'] * (int) $customerData['quotation']) * 0.03 . '
								</td>
							</tr>

							<tr>
								<td style="border-top: 1px solid #eee; width:38%;padding: 5px;">
								
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								Paid Amount
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
								₹' . $customer_amount . '
								</td>
							</tr>
							<tfoot>
								<tr>
									<th width="38%" align="left" style="border-bottom: 1px solid #00000080;  color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										
									</th>
									<th  align="center" style="border-bottom: 1px solid #00000080;  color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										
									</th>
									<th  align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										<strong>Due Amount</strong>
									</th>
									<th  align="right" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
									<strong>₹' . $due_amount . '</strong>
									</th>
								</tr>
							</tfoot>	
						</tbody>
					</table>
				</div>
				
				<br/>
				<br/>
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<table style="width: 35%; !important">
									<tbody>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td>
												Bank Acc:-
											</td>
										</tr>
										<tr>
											<td>
												Name:-
											</td>
											<td>
												YOGINTRA
											</td>
										</tr>
										<tr>
											<td>
												Acc:-
											</td>
											<td>
												50200067255848
											</td>
										</tr>
										<tr>
											<td>
												Acc Type:-
											</td>
											<td>
												Current
											</td>
										</tr>
										<tr>
											<td>
												IFSC:-
											</td>
											<td>
												HDFC0000175
											</td>
										</tr>
										<tr>
											<td>
												Branch name:-
											</td>
											<td>
												DOMBIVALI-EAST
											</td>
										</tr>
										<tr>
											<td>
												Upi:-
											</td>
											<td>
												9867291573@hdfcbank
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td>
								<img src="' . base_url('assets/') . 'payment-qr.jpg" alt="Payment QR">
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<br/>

				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<strong style="padding: 1cm !important; text-transform: uppercase;">Note: </strong>
								This is a Computer Generated Invoice.
							</td>
						</tr>
					</tbody>
				</table>';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, 'I');


		//Close and output PDF document
		$pdf->Output('example_006.pdf', 'I');

		//============================================================+
		// END OF FILE
		//============================================================+

	}

	public function getBookingProfile($id)
	{
		if (@$id) {
			$resp = $this->db->where('id', $id)->get('events')->row_array();
			$resp['paymentDetails'] = $this->getPayments($id);
			if (count($resp) > 0) {
				$response['success'] = 1;
				$response['data'] = $resp;
			} else {
				$response['success'] = 0;
				$response['message'] = 'No data found!';
			}
			return $response;
		} else {
			echo "Internal Server Error !";
		}
	}

	public function getYoga($id)
	{
		if (@$id) {
			$resp = $this->db->where('id', $id)->get('yoga')->row_array();
			$resp['paymentDetails'] = $this->getPayments($id);
			if (count($resp) > 0) {
				$response['success'] = 1;
				$response['data'] = $resp;
			} else {
				$response['success'] = 0;
				$response['message'] = 'No data found!';
			}
			return $response;
		} else {
			echo "Internal Server Error !";
		}
	}

	public function getPayments($leadId)
	{
		$where = ['status' => 1, 'leadId' => $leadId, 'type' => 'event'];
		$response = $this->db->where($where)->get('paymentdata')->result_array();
		return $response;
	}

	function event()
	{
		require_once(APPPATH . 'helpers/tcpdf/tcpdf.php');

		$leadId = $_GET['id'];
		$customerData = $this->getBookingProfile($leadId)['data'];

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Yogintra');
		$pdf->SetTitle('customer Invoice');
		$pdf->SetSubject('');

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(20, 35, 20);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('dejavusans', '', 10);

		// Call before the addPage() method
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		// add a page
		$pdf->AddPage();

		// create some HTML content
		$html = '
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td class="">
								<div>
									<img src="' . base_url('assets/') . 'logo.jpg" alt="Company Logo" style="max-width: 100%;">
								</div>
							</td>
							<td> 
								<table style="text-align:right; table-layout: fixed; width: 100%;">
									<tbody>
										<tr>
											<td>
												<strong style="padding: 1cm !important; text-transform: uppercase;background-color:#ddd">
													Invoice - 
												</strong>
												<span style="background-color:#ddd">
													#YI' . $_GET['id'] . '
												</span>
												<div style="width:100%">Pay Date : ' . substr($customerData['totalPayDate'], 0, 10) . '
													<br/>Bil Date : ' . substr($customerData['created_date'], 0, 10) . '
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<strong style="padding: 1cm !important; text-transform: uppercase;">Yogintra</strong>
								<div style="width:100% ; font-size:12px">
									Shivila Apt D/408 <br/>Mumbra Devi Colony Road <br/>Diva East Thane Mumbai -400612<br/>Phone: <a style="text-decoration:none" href="tel:919867291573">+91-9867 291 573</a>
									<br/>Email: <a style="text-decoration:none" href="mailto:support@yogintra.com">support@yogintra.com</a>
									<br/>Website: <a style="text-decoration:none" href="www.yogintra.com">www.yogintra.com</a>
								</div>
							</td>
							<td style="margin-left:0%"></td>
							<td> 
								<table style="text-align:left;,margin-left:20px; table-layout: fixed; width: 100%;">
									<tbody>
										<tr>
											<td>
												<strong style="padding: 1cm !important; text-transform: uppercase;">Bill To</strong>
												<div style="font-size:12px;width:100%">' . $customerData['client_name'] . '
												<br/>' . $customerData['country'] . ', ' . $customerData['state'] . ', ' . $customerData['city'] . '
												<br/>' . $customerData['client_number'] . '
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<div>
					<table style="table-layout: fixed; width: 100%;" cellpadding="5">
						<thead>
							<tr>
								<th width="26%" align="left" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; color:#fff; 
									font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>Event Name</strong>
								</th>
								<th width="25%" align="center" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; color:#fff; 
									font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>Class Type</strong>
								</th>
								<th width="25%" align="center" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; 
									color:#fff; 
									font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>Package</strong>
								</th>
								<th align="right" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; 
									color:#fff; font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>' .
			(
				$customerData['payment_type'] == 'Partition Payment' ?
				"Partition Payment"
				: 'Full Pay'
			)
			. '</strong>
											
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="border-top: 1px solid #eee; width:32%;padding: 5px;">
									' . $customerData['event_name'] . '
								</td>
								<td style="border-top: 1px solid #eee; width:16%;padding: 5px;">
									' . $customerData['class_type'] . '
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
									₹' . $customerData['package'] . '
								</td>
								<td align="right" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
									₹' . $customerData['totalPayAmount'] . '
								</td>
							</tr>
							
							<tr>
								<td style="border-top: 1px solid #eee; width:24%;padding: 5px;">
								
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								+Other Charges
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
									₹' . ($customerData['totalPayAmount'] * 0.03) . '
								</td>
							</tr>

							<tr>
								<td style="border-top: 1px solid #eee; width:24%;padding: 5px;">
								
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								Paid Amount
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
									₹' . ($customerData['totalPayAmount']) . '
								</td>
							</tr>
							<tfoot>
								<tr>
									<th width="24%" align="left" 
										style="border-bottom: 1px solid #00000080;  
										color:#fff; font-weight:800; 
										background-color:#00000060; 
										padding: 5px;">										
									</th>
									<th  align="center" style="border-bottom: 1px solid #00000080;  color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										
									</th>
									<th  align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										<strong>Due Amount</strong>
									</th>
									<th  align="right" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										<strong>₹' . ($customerData['package'] - $customerData['totalPayAmount']) . '</strong>
									</th>
								</tr>
							</tfoot>	
						</tbody>
					</table>
				</div>
				
				<br/>
				<br/>
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<table style="width: 35%; !important">
									<tbody>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td>
												Bank Acc:-
											</td>
										</tr>
										<tr>
											<td>
												Name:-
											</td>
											<td>
												YOGINTRA
											</td>
										</tr>
										<tr>
											<td>
												Acc:-
											</td>
											<td>
												50200067255848
											</td>
										</tr>
										<tr>
											<td>
												Acc Type:-
											</td>
											<td>
												Current
											</td>
										</tr>
										<tr>
											<td>
												IFSC:-
											</td>
											<td>
												HDFC0000175
											</td>
										</tr>
										<tr>
											<td>
												Branch name:-
											</td>
											<td>
												DOMBIVALI-EAST
											</td>
										</tr>
										<tr>
											<td>
												Upi:-
											</td>
											<td>
												9867291573@hdfcbank
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td>
								<img src="' . base_url('assets/') . 'payment-qr.jpg" alt="Payment QR">
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<br/>

				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<strong style="padding: 1cm !important; text-transform: uppercase;">Note: </strong>
								This is a Computer Generated Invoice.
							</td>
						</tr>
					</tbody>
				</table>
		';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, 'I');


		//Close and output PDF document
		$pdf->Output('example_006.pdf', 'I');

		//============================================================+
		// END OF FILE
		//============================================================+

	}

	function yoga()
	{
		require_once(APPPATH . 'helpers/tcpdf/tcpdf.php');

		$leadId = $_GET['id'];
		$renewAmount = $_GET['renew_amount'] ?? '';

		$customerData = $this->getYoga($leadId)['data'];
		$customer_amount = $renewAmount;

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Yogintra');
		$pdf->SetTitle('customer Invoice');
		$pdf->SetSubject('');

		// set header and footer fonts
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(20, 35, 20);
		$pdf->SetHeaderMargin(0);
		$pdf->SetFooterMargin(0);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__) . '/lang/eng.php')) {
			require_once(dirname(__FILE__) . '/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

		// set font
		$pdf->SetFont('dejavusans', '', 10);

		// Call before the addPage() method
		$pdf->SetPrintHeader(false);
		$pdf->SetPrintFooter(false);

		// add a page
		$pdf->AddPage();

		// create some HTML content
		$html = '
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td class="">
								<div>
									<img src="' . base_url('assets/') . 'logo.jpg" alt="Company Logo" style="max-width: 100%;">
								</div>
							</td>
							<td> 
								<table style="text-align:right; table-layout: fixed; width: 100%;">
									<tbody>
										<tr>
											<td>
												<strong style="padding: 1cm !important; text-transform: uppercase;background-color:#ddd">
													Invoice - 
												</strong>
												<span style="background-color:#ddd">
													#YI' . $_GET['id'] . '
												</span>
												<div style="width:100%">Pay Date : ' . substr($customerData['totalPayDate'], 0, 10) . '
													<br/>Bil Date : ' . substr($customerData['created_date'], 0, 10) . '
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<br/>
				<br/>
				
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<strong style="padding: 1cm !important; text-transform: uppercase;">Yogintra</strong>
								<div style="width:100% ; font-size:12px">
									Shivila Apt D/408 <br/>Mumbra Devi Colony Road <br/>Diva East Thane Mumbai -400612<br/>Phone: <a style="text-decoration:none" href="tel:919867291573">+91-9867 291 573</a>
									<br/>Email: <a style="text-decoration:none" href="mailto:support@yogintra.com">support@yogintra.com</a>
									<br/>Website: <a style="text-decoration:none" href="www.yogintra.com">www.yogintra.com</a>
								</div>
							</td>
							<td style="margin-left:0%"></td>
							<td> 
								<table style="text-align:left;,margin-left:20px; table-layout: fixed; width: 100%;">
									<tbody>
										<tr>
											<td>
												<strong style="padding: 1cm !important; text-transform: uppercase;">Bill To</strong>
												<div style="font-size:12px;width:100%">' . $customerData['client_name'] . '
												<br/>' . $customerData['country'] . ', ' . $customerData['state'] . ', ' . $customerData['city'] . '
												<br/>' . $customerData['client_number'] . '
												</div>
											</td>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<div>
					<table style="table-layout: fixed; width: 100%;" cellpadding="5">
						<thead>
							<tr>
								<th width="26%" align="left" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; color:#fff; 
									font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>Center Name</strong>
								</th>
								<th width="25%" align="center" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; color:#fff; 
									font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>Type</strong>
								</th>
								<th width="25%" align="center" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; 
									color:#fff; 
									font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>Package</strong>
								</th>
								<th align="right" 
									style="border-bottom: 1px solid #00000080; 
									border-right: 1px solid #fff; 
									color:#fff; font-weight:800; 
									background-color:#00000060; 
									padding: 5px;"
								>
									<strong>' .
			(
				$customerData['payment_type'] == 'Partition Payment' ?
				"Partition Payment"
				: 'Full Pay'
			)
			. '</strong>
											
								</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td style="border-top: 1px solid #eee; width:32%;padding: 5px;">
									' . $customerData['event_name'] . '
								</td>
								<td style="border-top: 1px solid #eee; width:16%;padding: 5px;">
									Yoga Center
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
									₹' . $customerData['package'] . '
								</td>
								<td align="right" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
									₹' . $customer_amount . '
								</td>
							</tr>
							
							<tr>
								<td style="border-top: 1px solid #eee; width:24%;padding: 5px;">
								
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								+Other Charges
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
									₹' . ($customer_amount * 0.03) . '
								</td>
							</tr>

							<tr>
								<td style="border-top: 1px solid #eee; width:24%;padding: 5px;">
								
								</td>
								<td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
								
								</td>
								<td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
								Paid Amount
								</td>
								<td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
									₹' . ($customer_amount) . '
								</td>
							</tr>
							<tfoot>
								<tr>
									<th width="24%" align="left" 
										style="border-bottom: 1px solid #00000080;  
										color:#fff; font-weight:800; 
										background-color:#00000060; 
										padding: 5px;">										
									</th>
									<th  align="center" style="border-bottom: 1px solid #00000080;  color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										
									</th>
									<th  align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										<strong>Due Amount</strong>
									</th>
									<th  align="right" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
										<strong>₹' . ($customerData['package'] - $customer_amount) . '</strong>
									</th>
								</tr>
							</tfoot>	
						</tbody>
					</table>
				</div>
				
				<br/>
				<br/>
				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<table style="width: 35%; !important">
									<tbody>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td></td>
										</tr>
										<tr>
											<td>
												Bank Acc:-
											</td>
										</tr>
										<tr>
											<td>
												Name:-
											</td>
											<td>
												YOGINTRA
											</td>
										</tr>
										<tr>
											<td>
												Acc:-
											</td>
											<td>
												50200067255848
											</td>
										</tr>
										<tr>
											<td>
												Acc Type:-
											</td>
											<td>
												Current
											</td>
										</tr>
										<tr>
											<td>
												IFSC:-
											</td>
											<td>
												HDFC0000175
											</td>
										</tr>
										<tr>
											<td>
												Branch name:-
											</td>
											<td>
												DOMBIVALI-EAST
											</td>
										</tr>
										<tr>
											<td>
												Upi:-
											</td>
											<td>
												9867291573@hdfcbank
											</td>
										</tr>
										
									</tbody>
								</table>
							</td>
							<td>
								<img src="' . base_url('assets/') . 'payment-qr.jpg" alt="Payment QR">
							</td>
						</tr>
					</tbody>
				</table>
				<br/>
				<br/>
				<br/>
				<br/>

				<table style=" width: 100%; !important">
					<tbody>
						<tr>
							<td>
								<strong style="padding: 1cm !important; text-transform: uppercase;">Note: </strong>
								This is a Computer Generated Invoice.
							</td>
						</tr>
					</tbody>
				</table>
		';

		// output the HTML content
		$pdf->writeHTML($html, true, false, true, false, 'I');


		//Close and output PDF document
		$pdf->Output('example_006.pdf', 'I');

		//============================================================+
		// END OF FILE
		//============================================================+

	}

	public function getLeadById($leadId)
	{
		$id = $leadId;
		$resp = $this->db->where('id', $id)->get('leads')->row_array();
		if (count($resp) > 0) {
			$response = [
				'data' => $resp
			];
		} else {
			$response = [
				'success' => 0,
				'message' => 'No data found!'
			];
		}
		return ($response);
	}
}
