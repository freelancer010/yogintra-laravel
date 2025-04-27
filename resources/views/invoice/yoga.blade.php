<table style="width: 100%;">
    <tbody>
        <tr>
            <td>
                <div>
                    <img src="{{ public_path('assets/logo.jpg') }}" alt="Company Logo" style="max-width: 100%;">
                </div>
            </td>
            <td>
                <table style="text-align:right; table-layout: fixed; width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <strong style="text-transform: uppercase;background-color:#ddd">
                                    Invoice -
                                </strong>
                                <span style="background-color:#ddd">
                                    #YI{{ request()->id }}
                                </span>
                                <div style="width:100%">Pay Date: {{ substr($customerData['totalPayDate'], 0, 10) }}
                                    <br />Bil Date: {{ substr($customerData['created_date'], 0, 10) }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<br><br><br><br><br>

<table style="width: 100%;">
    <tbody>
        <tr>
            <td>
                <strong style="text-transform: uppercase;">Yogintra</strong>
                <div style="font-size:12px">
                    Shivila Apt D/408 <br />Mumbra Devi Colony Road <br />Diva East Thane Mumbai -400612<br />Phone: +91-9867 291 573
                    <br />Email: support@yogintra.com
                    <br />Website: www.yogintra.com
                </div>
            </td>
            <td></td>
            <td>
                <table style="text-align:left; margin-left:20px; table-layout: fixed; width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <strong style="text-transform: uppercase;">Bill To</strong>
                                <div style="font-size:12px;">
                                    {{ $customerData['client_name'] }}<br>
                                    {{ $customerData['country'] }}, {{ $customerData['state'] }}, {{ $customerData['city'] }}<br>
                                    {{ $customerData['client_number'] }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<br><br><br>

<div>
    <table style="table-layout: fixed; width: 100%;" cellpadding="5">
        <thead>
            <tr style="background-color:#00000060; color:#fff; font-weight:800;">
                <th width="26%" align="left" style="padding:5px;">Center Name</th>
                <th width="25%" align="center" style="padding:5px;">Type</th>
                <th width="25%" align="center" style="padding:5px;">Package</th>
                <th align="right" style="padding:5px;">{{ $paymentType }}</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border-top:1px solid #eee;padding:5px;">{{ $customerData['event_name'] }}</td>
                <td align="center" style="border-top:1px solid #eee;padding:5px;">Yoga Center</td>
                <td align="center" style="border-top:1px solid #eee;padding:5px;">₹{{ $customerData['package'] }}</td>
                <td align="right" style="border-top:1px solid #eee;padding:5px;">₹{{ $customer_amount }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td align="center" style="border-top:1px solid #eee;padding:5px;">+Other Charges</td>
                <td align="right" style="border-top:1px solid #eee;padding:5px;">₹{{ $otherCharges }}</td>
            </tr>
            <tr>
                <td colspan="2"></td>
                <td align="center" style="border-top:1px solid #eee;padding:5px;">Paid Amount</td>
                <td align="right" style="border-top:1px solid #eee;padding:5px;">₹{{ $customer_amount }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr style="background-color:#00000060; color:#fff; font-weight:800;">
                <th colspan="2"></th>
                <th align="center" style="padding:5px;">Due Amount</th>
                <th align="right" style="padding:5px;">₹{{ $dueAmount }}</th>
            </tr>
        </tfoot>
    </table>
</div>

<br><br>

<table style="width:100%;">
    <tr>
        <td>
            <table style="width:35%;">
                <tbody>
                    <tr>
                        <td colspan="2">Bank Acc:-</td>
                    </tr>
                    <tr>
                        <td>Name:-</td>
                        <td>YOGINTRA</td>
                    </tr>
                    <tr>
                        <td>Acc:-</td>
                        <td>50200067255848</td>
                    </tr>
                    <tr>
                        <td>Acc Type:-</td>
                        <td>Current</td>
                    </tr>
                    <tr>
                        <td>IFSC:-</td>
                        <td>HDFC0000175</td>
                    </tr>
                    <tr>
                        <td>Branch name:-</td>
                        <td>DOMBIVALI-EAST</td>
                    </tr>
                    <tr>
                        <td>Upi:-</td>
                        <td>9867291573@hdfcbank</td>
                    </tr>
                </tbody>
            </table>
        </td>
        <td>
            <img src="{{ public_path('assets/payment-qr.jpg') }}" alt="Payment QR">
        </td>
    </tr>
</table>

<br><br><br><br>

<table style="width:100%;">
    <tbody>
        <tr>
            <td>
                <strong style="text-transform: uppercase;">Note:</strong> This is a Computer Generated Invoice.
            </td>
        </tr>
    </tbody>
</table>