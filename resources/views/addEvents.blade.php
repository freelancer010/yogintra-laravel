@extends('layouts.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i
                            class="fas fa-backward"></i></button>&nbsp;
                    <h1>Add Events</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Events</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Client Name</label>
                                        <input required type="text" class="form-control" id="clientName" name="name"
                                            placeholder="Enter Client Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientNumber">Client Number</label>
                                        <input required type="number" class="form-control" id="clientNumber"
                                            name="number" placeholder="Enter Client Number">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEmail">Email address</label>
                                        <input required type="email" class="form-control" id="clientEmail"
                                            name="email" placeholder="Enter email">
                                    </div>



                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Country</label>
                                        <select class="form-control countries" id="countryId" name="country">
                                            <option value="selected" selected>Select your Country</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>States</label>
                                        <select class="form-control states" id="stateId" name="state">
                                            <option value="selected" selected>Select your State First</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>City</label>
                                        <select class="form-control cities" id="cityId" name="city">
                                            <option value="selected" selected>Select your City first</option>
                                        </select>
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
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Event Name</label>
                                        <input required type="text" class="form-control" id="eventName" name="eventName" placeholder="Enter Event Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="date">Event Date</label>
                                        <input required type="datetime-local" class="form-control" id="date" name="date"
                                            placeholder="Pickup date">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="package">Package</label>
                                        <input required type="text" class="form-control" id="package" name="package"
                                            placeholder="Enter your package">
                                    </div>

                                    <div class="form-group col-12" id="fullPaymentType">
                                        <label>Type of Payment</label>
                                        <select id="payment_type" name="payment_type" class="form-control editInputBox customEditInputBox customerInputBox">
                                            <option selected value="Full Payment">Full Payment</option>
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
                                                <input type="datetime-local" name="totalPayDate" class="form-control m-input editInputBox customEditInputBox customerInputBox">
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
                        <button type="button" class="btn btn-primary w-100" onclick="addLead()">Save</button>
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

    $('#exceed').css('display', 'none');
    let addLead = () => {
        ajaxCallData(PANELURL + 'event/add', $("#addLeads").serialize(), 'POST')
            .then(function(response) {
                if (response.success == 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('success!', 'Data Added Successfully');
                    window.location.href = PANELURL + 'event';
                }
                getData();
            })
            .catch(function(err) {
                console.log(err);
            });
    }


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
    $('#back-btn').on('click', () => {
        redirect('customer')
    });


    let calculateP = () => {
        let package = $('#package').val();
        let quotation = $('#quotation').val();
        let value = package * quotation;
        $('#payableAmount').val(value);
    };

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
</script>
@endsection