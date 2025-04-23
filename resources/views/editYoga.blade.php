@extends('layouts.layout')

@section('content')
<style>
    .chosen-container-single .chosen-single {
        padding: 6px !important;
        height: auto !important;
        border: 1px solid #44444450 !important;
    }
</style>
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" id="back-btn"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Edit Yoga Center</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Yoga Center</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads">
                            @csrf
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <input type="hidden" value="" name="eventId" id="eventId">

                                    {{-- Client Name --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Client Name</label>
                                        <input
                                            type="text"
                                            class="form-control editInputBox"
                                            id="clientName"
                                            name="name"
                                            placeholder="Enter Client Name"
                                            required
                                            @if(session('admin_role_id')==3) readonly @endif>
                                    </div>

                                    {{-- Client Number --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientNumber">Client Number</label>
                                        <input
                                            type="text"
                                            class="form-control editInputBox"
                                            id="clientNumber"
                                            name="number"
                                            placeholder="Enter Client Number"
                                            required
                                            oninput="this.value = this.value.replace(/[^0-9.+]/g, '').replace(/(\..*)\./g, '$1');"
                                            @if(session('admin_role_id')==3) readonly @endif>
                                    </div>

                                    {{-- Email --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEmail">Email address</label>
                                        <input
                                            type="email"
                                            class="form-control editInputBox"
                                            id="clientEmail"
                                            name="email"
                                            placeholder="Enter email"
                                            required
                                            @if(session('admin_role_id')==3) readonly @endif>
                                    </div>

                                    {{-- Country --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Country</label>
                                        <input
                                            type="text"
                                            class="form-control editInputBox"
                                            id="country"
                                            name="country"
                                            placeholder="Enter Country name"
                                            required
                                            @if(session('admin_role_id')==3) readonly @endif>
                                    </div>

                                    {{-- State --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>State</label>
                                        <input
                                            type="text"
                                            class="form-control editInputBox"
                                            id="state"
                                            name="state"
                                            placeholder="Enter state name"
                                            required
                                            @if(session('admin_role_id')==3) readonly @endif>
                                    </div>

                                    {{-- City --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>City</label>
                                        <input
                                            type="text"
                                            class="form-control editInputBox"
                                            id="city"
                                            name="city"
                                            placeholder="Enter city name"
                                            required
                                            @if(session('admin_role_id')==3) readonly @endif>
                                    </div>

                                    <input type="hidden" name="class_type" value="Yoga Center">

                                    {{-- Yoga Center Name --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="eventName">Yoga Center Name</label>
                                        <input
                                            type="text"
                                            class="form-control editInputBox customEditInputBox"
                                            id="eventName"
                                            name="eventName"
                                            placeholder="Enter yoga center name"
                                            required>
                                    </div>

                                    {{-- Start & End Dates --}}
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="sdate">Start Date</label>
                                        <input type="datetime-local" class="form-control" id="sdate" name="start_date" required>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="edate">End Date</label>
                                        <input type="datetime-local" class="form-control" id="edate" name="end_date" required>
                                    </div>

                                    {{-- Package --}}
                                    <div class="form-group col-lg-6 col-sm-12" id="packageEd">
                                        <label for="package">Package</label>
                                        <input type="text" onkeyup="calculateP()" class="form-control editInputBox customEditInputBox" id="package" name="package" required>
                                    </div>

                                    {{-- Payment Type --}}
                                    <div class="form-group col-12" id="fullPaymentType">
                                        <label>Type of Payment</label>
                                        <select id="payment_type" name="payment_type" class="form-control editInputBox customEditInputBox customerInputBox">
                                            <option selected>Select Your Payment type</option>
                                            <option value="Full Payment">Full Payment</option>
                                            <option value="Partition Payment">Partition Payment</option>
                                        </select>
                                    </div>

                                    {{-- Full Payment --}}
                                    <div class="form-group col-lg-12 col-sm-12" id="fullPay">
                                        <label for="fullPayType">Full Payment</label>
                                        <div class="row mt-2">
                                            <div class="input-group col-lg-6">
                                                <input type="number" id="fullPayType" name="totalPayAmount" class="form-control m-input editInputBox customEditInputBox customerInputBox" onkeyup="checkPayableAmount()" required>
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="datetime-local" name="totalPayDate" id="totalPay" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                                            </div>
                                        </div>
                                        <span id="exceed" class="text-danger" style="display: none;">Full amount cannot exceed payable amount</span>
                                    </div>

                                    {{-- Partition Payment --}}
                                    <div class="form-group col-12" id="fullPayment">
                                        <label for="fullPayment">Payment Amount</label>
                                        <div id="oldinput"></div>
                                        <div id="newinput"></div>
                                        <button id="rowAdder" type="button" class="btn btn-dark mt-3">
                                            <span class="bi bi-plus-square-dotted"></span> ADD
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer editProfileContainer justify-content-between">
                                <button type="button" class="btn btn-primary w-100" id="save" onclick="editEvent()">Save</button>
                            </div>
                        </form>
                    </div> <!-- card-body -->
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('scripts')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    const PANELURL = "{{ url('/') }}/";
    const is_supper = "{{ session('is_supper') }}";
    const admin_role_id = "{{ session('admin_role_id') }}";
    const param = "{{ request('id') }}";

    $('#exceed').hide();
    $('#eventId').val(param);

    const editLead = (id) => {
        ajaxCallData(PANELURL + 'yoga-bookings/profile', {
                'bookingId': param
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    const response = resp.data;
                    const paymentDetails = resp.data.paymentDetails;
                    // Set input values
                    $('#clientName').val(response.client_name);
                    $('#clientNumber').val(response.client_number);
                    $('#country').val(response.country);
                    $('#state').val(response.state);
                    $('#city').val(response.city);
                    $('#clientEmail').val(response.email);
                    $('#class_type').val(response.class_type);
                    $('#eventName').val(response.event_name);
                    $('#package').val(response.package);
                    $('#sdate').val(response.s_date);
                    $('#edate').val(response.e_date);
                    $('#totalPay').val(response.totalPayDate);
                    $('#fullPayType').val(response.totalPayAmount);

                    if (response.payment_type) {
                        $('#payment_type').val(response.payment_type);
                    }

                    $.each(paymentDetails, function() {
                        const newRow = `
                            <div id="row" class="row mt-2">
                                <div class="input-group col-6">
                                    <div class="input-group-prepend">
                                        <button class="btn btn-danger" id="DeleteRow" type="button">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                    <input required type="number" name="fullPayment[]" value="${this.amount}" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                                </div>
                                <div class="col-6">
                                    <input type="datetime-local" name="fullPaymentDate[]" value="${this.created_date}" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                                </div>
                            </div>`;
                        $('#oldinput').append(newRow);
                    });

                    const payType = $("#payment_type option:selected").text();
                    if (payType === 'Partition Payment') {
                        $('#fullPayment').show();
                        $('#fullPay').hide();
                    } else {
                        $('#fullPay').show();
                        $('#fullPayment').hide();
                    }

                    $('#payment_type').change((e) => {
                        if (e.target.value === 'Partition Payment') {
                            $('#fullPayment').show();
                            $('#fullPay').hide();
                        } else {
                            $('#fullPayment').hide();
                            $('#fullPay').show();
                            $('#fullPayType').val(response.full_payment);
                        }
                    });

                    $('#back-btn').on('click', () => {
                        window.location.href = PANELURL + 'yoga-bookings/profile/' + param;
                    });
                }
            }).catch(console.error);
    };

    const calculateP = () => {
        const packageVal = parseFloat($('#package').val()) || 0;
        const quotation = parseFloat($('#quotation').val()) || 0;
        $('#payableAmount').val(packageVal * quotation);
    };

    editLead(param);

    const editEvent = () => {
        $(".editInputBox").prop('disabled', false);
        ajaxCallData(PANELURL + 'yoga-bookings/add', $("#addLeads").serialize(), 'POST')
            .then(function(response) {
                if (response.success === 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('Your profile data edited successfully', 'success');
                    setTimeout(() => {
                        window.location.href = PANELURL + "yoga-bookings/profile/" + param;
                    }, 1000);
                }
            })
            .catch(console.error);
        $(".editInputBox").prop('disabled', true);
    };

    const disableInput = () => {
        if (is_supper == 1) {
            $(".editInputBox").prop('disabled', false);
        } else {
            $(".customEditInputBox").prop('disabled', false);
        }
    };

    disableInput();

    $("#rowAdder").click(function() {
        const newRow = `
            <div id="row" class="row mt-2">
                <div class="input-group col-6">
                    <div class="input-group-prepend">
                        <button class="btn btn-danger" id="DeleteRow" type="button">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </div>
                    <input required type="number" name="fullPayment[]" placeholder="Enter Payment" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                </div>
                <div class="col-6">
                    <input type="datetime-local" name="fullPaymentDate[]" class="form-control m-input editInputBox customEditInputBox customerInputBox" value="${new Date().toISOString().slice(0,16)}">
                </div>
            </div>`;
        $('#newinput').append(newRow);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).closest("#row").remove();
    });

    const checkPayableAmount = () => {
        const payAmnt = parseInt($('#payableAmount').val()) || 0;
        const fullAmnt = parseInt($('#fullPayType').val()) || 0;

        if (fullAmnt > payAmnt) {
            $('button#save').attr('disabled', true);
            $('#fullPayType').css({
                "border": "3px solid red",
                "color": "red"
            });
            $('#exceed').show().css('color', 'red');
            notifyAlert('Full amount cannot exceed payable amount', 'danger');
            $('html').css('scroll-behavior', 'smooth');
        } else {
            $('#exceed').hide();
            $('button#save').attr('disabled', false);
            $('#fullPayType').css({
                "border": "1px solid #ddd",
                "color": "black"
            });
        }
    };
</script>
@endsection