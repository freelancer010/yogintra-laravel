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
                    <h1>Edit Profile</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Edit Profile</li>
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
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Client Name</label>

                                        <input type="hidden" value="" name="eventId" id="eventId">

                                        <input @if(session('admin_role_id')==3) readonly @endif required type="text" class="form-control editInputBox" id="clientName" name="name" placeholder="Enter Client Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientNumber">Client Number</label>
                                        <input @if(session('admin_role_id')==3) readonly @endif required type="text"
                                            oninput="this.value = this.value.replace(/[^0-9.+]/g, '').replace(/(\..*)\./g, '$1');"
                                            class="form-control editInputBox" id="clientNumber"
                                            name="number" placeholder="Enter Client Number">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEmail">Email address</label>
                                        <input @if(session('admin_role_id')==3) readonly @endif required type="email" class="form-control editInputBox" id="clientEmail"
                                            name="email" placeholder="Enter email">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Country</label>
                                        <input @if(session('admin_role_id')==3) readonly @endif required type="text" class="form-control editInputBox" id="country"
                                            name="country" placeholder="Enter Country name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>State</label>
                                        <input @if(session('admin_role_id')==3) readonly @endif required type="text" class="form-control editInputBox" id="state"
                                            name="state" placeholder="Enter state name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>City</label>
                                        <input @if(session('admin_role_id')==3) readonly @endif required type="text" class="form-control editInputBox" id="city"
                                            name="city" placeholder="Enter city name">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Type of Event</label>
                                        <select id="class_type" name="class" class="form-control">
                                            <option selected value=''>Select Your Event type</option>
                                            <option value="Retreat">Retreat</option>
                                            <option value="Workshop">Workshop</option>
                                            <option value="TTC">TTC</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="attendee_Name">
                                        <label for="eventName">Event Name</label>
                                        <input required type="text" class="form-control editInputBox customEditInputBox" id="eventName"
                                            name="eventName" placeholder="Enter event name">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="attendee_Name">
                                        <label for="date">Event Date</label>
                                        <input required type="datetime-local" class="form-control editInputBox customEditInputBox" id="date"
                                            name="date" placeholder="Enter attendee name" value="{{ (session('username') != 'superadmin') ? session('fullName') : '' }}">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="packageEd">
                                        <label for="package">Package</label>
                                        <input required type="text" onKeyup="calculateP()" class="form-control editInputBox customEditInputBox" id="package" name="package"
                                            placeholder="Enter your package">
                                    </div>

                                    <div class="form-group col-12" id="fullPaymentType">
                                        <label>Type of Payment</label>
                                        <select id="payment_type" name="payment_type" class="form-control editInputBox customEditInputBox customerInputBox">
                                            <option selected>Select Your Payment type</option>
                                            <option value="Full Payment">Full Payment</option>
                                            <option value="Partition Payment">Partition Payment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-12 col-sm-12" id="fullPay">
                                        <label for="fullPayType">Full Payment</label>
                                        <div id="row" class="row mt-2">
                                            <div class="input-group col-lg-6">
                                                <input required type="number" id="fullPayType" onkeyup="checkPayableAmount()" name="totalPayAmount" placeholder="Enter Payment" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="datetime-local" name="totalPayDate" id="totalPay" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                                            </div>
                                        </div>
                                        <span id="exceed">Full amount cannot exceed payable amount</span>
                                    </div>


                                    <div class="form-group col-12" id="fullPayment">
                                        <label for="fullPayment">Payment Amount</label>
                                        <div id="oldinput"></div>


                                        <div id="newinput"></div>
                                        <button id="rowAdder" type="button"
                                            class="btn btn-dark  editInputBox customEditInputBox customerInputBox mt-3">
                                            <span class="bi bi-plus-square-dotted">
                                            </span> ADD
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer editProfileContainer justify-content-between">
                        <button type="button" class="btn btn-primary w-100" id="save" onclick="editEvent()">Save</button>
                    </div>
                    </form>
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

    let editLead = (id) => {
        let postData = {
            'id': id,
        }
        ajaxCallData(PANELURL + 'event/profile', {
                'bookingId': param
            }, 'POST')
            .then(function(resp) {
                if (resp.success == 1) {
                    const response = resp.data;
                    const paymentDetails = resp.data.paymentDetails;

                    //putting values
                    $('#clientName').val(response.client_name);
                    $('#clientNumber').val(response.client_number);
                    $('#country').val(response.country);
                    $('#state').val(response.state);
                    $('#city').val(response.city);
                    $('#clientEmail').val(response.email);
                    $('#class_type').val(response.class_type);
                    $('#eventName').val(response.event_name);
                    $('#package').val(response.package);
                    $('#date').val(response.created_date);
                    $('#totalPay').val(response.totalPayDate);
                    $('#fullPayType').val(response.totalPayAmount);
                    response.payment_type ? $('#payment_type').val(response.payment_type) : '';

                    $.each(paymentDetails, function() {
                        newRowAdd =
                            `<div id="row" class="row mt-2">
                            <div class="input-group col-6">
                                <div class="input-group-prepend">
                                    <button class="btn btn-danger" id="DeleteRow" type="button">
                                        <i class="bi bi-trash"></i>
                                        Delete
                                    </button>
                                </div>
                                <input required type="number" name="fullPayment[]" value="${this.amount}" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                            </div>
                            <div class="col-6">
                                <input type="datetime-local" name="fullPaymentDate[]"  value="${this.created_date}" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                            </div>
                        </div>`;
                        $('#oldinput').append(newRowAdd);
                    });

                    let payType = $("#payment_type option:selected").text();
                    let fullPayAmount = $('#fullPayType').val();
                    $('#fullPayType').val(fullPayAmount);

                    if (payType == 'Partition Payment') {
                        $('#fullPayment').css('display', 'block');
                        $('#fullPay').css('display', 'none');
                    } else {
                        $('#fullPay').css('display', 'block');
                        $('#fullPayment').css('display', 'none');
                    }

                    $('#payment_type').change((e) => {
                        if (e.target.value == 'Partition Payment') {
                            $('#fullPayment').css('display', 'block');
                            $('#fullPay').css('display', 'none');
                        } else {
                            $('#fullPayment').css('display', 'none');
                            $('#fullPay').css('display', 'block');
                            $('#fullPayType').val(response.full_payment);
                        }
                    });

                    $('#back-btn').on('click', () => {
                        window.location.href = PANELURL + 'event/profile/' + param;
                    });

                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };

    const calculateP = () => {
        const packageVal = parseFloat($('#package').val()) || 0;
        const quotation = parseFloat($('#quotation').val()) || 0;
        $('#payableAmount').val(packageVal * quotation);
    };

    editLead(param);

    let editEvent = () => {
        $(".editInputBox").prop('disabled', false);
        ajaxCallData(PANELURL + 'event/add', $("#addLeads").serialize(), 'POST')
            .then(function(response) {
                if (response.success == 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('Your profile data edited successfully', 'success');
                    setTimeout(() => {
                        window.location.href = PANELURL + "event/profile/" + param;
                    }, 1000);
                }
            })
            .catch(function(err) {
                console.log(err);
            });
        $(".editInputBox").prop('disabled', true);
    }

    const disableInput = () => {
        if (is_supper == 1) {
            $(".editInputBox").prop('disabled', false);
        } else {
            $(".customEditInputBox").prop('disabled', false);
        }
    };

    disableInput();

    $("#rowAdder").click(function() {
        newRowAdd =
            `<div id="row" class="row mt-2">
            <div class="input-group col-6">
                <div class="input-group-prepend">
                    <button class="btn btn-danger" id="DeleteRow" type="button">
                        <i class="bi bi-trash"></i>
                        Delete
                    </button>
                </div>
                <input required type="number" name="fullPayment[]" placeholder="Enter Payment" class="form-control m-input editInputBox customEditInputBox customerInputBox">
            </div>
            <div class="col-6">
                <input type="datetime-local" name="fullPaymentDate[]" class="form-control m-input editInputBox customEditInputBox customerInputBox" value="${ new Date()}">
            </div>
        </div>`;

        $('#newinput').append(newRowAdd);
    });

    $("body").on("click", "#DeleteRow", function() {
        $(this).parents("#row").remove();
    })

    let checkPayableAmount = () => {
        let payAmnt = parseInt($('#payableAmount').val());
        let fullAmnt = parseInt($('#fullPayType').val());

        if (fullAmnt > payAmnt) {
            console.log(payAmnt + '  ' + fullAmnt);
            $('button#save').attr('disabled', true);
            $('#fullPayType').css({
                "border": "3px solid red",
                "color": "red"
            });
            $('#exceed').css('display', 'block');
            $('#exceed').css('color', 'red');
            notifyAlert('Full amount cannot exceed payable amount', 'danger');
            $('html').css('scroll-behavior', 'smooth');
        } else {
            $('#exceed').css('display', 'none');
            $('button#save').attr('disabled', false);
            $('#fullPayType').css({
                "border": "1px solid #ddd",
                "color": "black"
            })
        }
    }
</script>
@endsection