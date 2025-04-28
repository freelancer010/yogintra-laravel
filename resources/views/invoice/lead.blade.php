<table style=" width: 100%; !important">
    <tbody>
        <tr>
            <td class="">
                <div>
                    <img src="{{ public_path('assets/logo.jpg') }}" alt="Company Logo" style="max-width: 100%;">
                </div>
            </td>
            <td>
                <table style="text-align:right; table-layout: fixed; width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <strong style="padding: 1cm !important; text-transform: uppercase;background-color:#ddd">Invoice - </strong><span style="background-color:#ddd">#YI{{ $leadId }}</span>
                                <div style="width:100%">Pay Date : {{ substr($customerData['totalPayDate'], 0, 10) }}
                                    <br />Bil Date : {{ substr($customerData['created_date'], 0, 10) }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<br /><br /><br /><br /><br />

<table style=" width: 100%; !important">
    <tbody>
        <tr>
            <td>
                <strong style="padding: 1cm !important; text-transform: uppercase;">Yogintra</strong>
                <div style="width:100% ; font-size:10px">
                    Shivila Apt D/408 <br />Mumbra Devi Colony Road <br />Diva East Thane Mumbai -400612<br />Phone: <a style="text-decoration:none" href="tel:919867291573">+91-9867 291 573</a>
                    <br />Email: <a style="text-decoration:none" href="mailto:support@yogintra.com">support@yogintra.com</a>
                    <br />Website: <a style="text-decoration:none" href="www.yogintra.com">www.yogintra.com</a>
                </div>
            </td>
            <td style="margin-left:0%"></td>
            <td>
                <table style="text-align:left;,margin-left:20px; table-layout: fixed; width: 100%;">
                    <tbody>
                        <tr>
                            <td>
                                <strong style="padding: 1cm !important; text-transform: uppercase;">Bill To</strong>
                                <div style="font-size:12px;width:100%">{{ $customerData['name'] }}
                                    <br />{{ $customerData['country'] }}, {{ $customerData['state'] }}, {{ $customerData['city'] }}
                                    <br />{{ $customerData['number'] }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>

<br /><br /><br />

<div>
    <table style="table-layout: fixed; width: 86%;" cellpadding="5">
        <thead>
            <tr>
                <th width="40%" align="left" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
                    <strong>Class Type</strong>
                </th>
                <th align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
                    <strong>Package</strong>
                </th>
                <th align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
                    <strong>Rate</strong>
                </th>
                <th align="right" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
                    <strong>Total</strong>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="border-top: 1px solid #eee; width:38%;padding: 5px;">
                    {{ $customerData['class_type'] }}
                </td>
                <td align="center" style=" width:30%; border-top: 1px solid #eee; padding: 5px;">
                    {{ $customerData['package'] }}
                </td>
                <td align="center" style="width:22%; border-top: 1px solid #eee; padding: 5px;">
                    ₹{{ $customerData['quotation'] }}
                </td>
                <td align="right" style="width:25%; border-top: 1px solid #eee; padding: 5px;">
                    ₹{{ (int) $customerData['package'] * (int) $customerData['quotation'] }}
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
                    ₹{{ ((int) $customerData['package'] * (int) $customerData['quotation']) * 0.03 }}
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
                    ₹{{ $customer_amount }}
                </td>
            </tr>
        <tfoot>
            <tr>
                <th width="38%" align="left" style="border-bottom: 1px solid #00000080;  color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">

                </th>
                <th align="center" style="border-bottom: 1px solid #00000080;  color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">

                </th>
                <th align="center" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
                    <strong>Due Amount</strong>
                </th>
                <th align="right" style="border-bottom: 1px solid #00000080; border-right: 1px solid #fff; color:#fff; font-weight:800; background-color:#00000060; padding: 5px;">
                    <strong>₹{{ $due_amount }}</strong>
                </th>
            </tr>
        </tfoot>
        </tbody>
    </table>
</div>

<br />
<br />
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
                <img src="{{ public_path('assets/payment-qr.jpg') }}" alt="Payment QR">
            </td>
        </tr>
    </tbody>
</table>

<br /><br /><br /><br />

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