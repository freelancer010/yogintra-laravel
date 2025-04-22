@extends('layouts.layout')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Add Yoga Center</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Events</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-12">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads">
                            <div class="card-body">
                                <div class="row">
                                    <!-- Client Info -->
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Client Name</label>
                                        <input required type="text" class="form-control" name="name" placeholder="Enter Client Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientNumber">Client Number</label>
                                        <input required type="number" class="form-control" name="number" placeholder="Enter Client Number">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEmail">Email Address</label>
                                        <input required type="email" class="form-control" name="email" placeholder="Enter email">
                                    </div>

                                    <!-- Location -->
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Country</label>
                                        <select class="form-control countries" name="country">
                                            <option value="" selected>Select your Country</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>State</label>
                                        <select class="form-control states" name="state">
                                            <option value="" selected>Select your State</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>City</label>
                                        <select class="form-control cities" name="city">
                                            <option value="" selected>Select your City</option>
                                        </select>
                                    </div>

                                    <input type="hidden" name="class_type" value="Yoga Center">

                                    <!-- Event Info -->
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Yoga Center</label>
                                        <input required type="text" class="form-control" name="eventName" placeholder="Enter Center Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Start Date</label>
                                        <input required type="datetime-local" class="form-control" name="start_date">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>End Date</label>
                                        <input required type="datetime-local" class="form-control" name="end_date">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Package</label>
                                        <input required type="text" class="form-control" id="package" name="package" placeholder="Enter your package">
                                    </div>

                                    <!-- Payment Type -->
                                    <div class="form-group col-12">
                                        <label>Type of Payment</label>
                                        <select id="payment_type" name="payment_type" class="form-control">
                                            <option selected value="Full Payment">Full Payment</option>
                                            <option value="Partition Payment">Partition Payment</option>
                                        </select>
                                    </div>

                                    <!-- Full Payment -->
                                    <div class="form-group col-lg-12" id="fullPay">
                                        <label>Full Payment</label>
                                        <div class="row mt-2">
                                            <div class="col-lg-6">
                                                <input required type="number" id="fullPayType" name="totalPayAmount" class="form-control" placeholder="Enter Payment" onkeyup="checkPayableAmount()">
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="datetime-local" name="totalPayDate" class="form-control">
                                            </div>
                                        </div>
                                        <span id="exceed" style="display:none; color:red;">Full amount cannot exceed payable amount</span>
                                    </div>

                                    <!-- Partition Payment -->
                                    <div class="form-group col-12" id="fullPayment" style="display:none;">
                                        <label>Payment Amount</label>
                                        <div id="newinput"></div>
                                        <button id="rowAdder" type="button" class="btn btn-dark mt-3">
                                            <i class="bi bi-plus-square-dotted"></i> ADD
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer justify-content-between">
                                <button type="button" id="save" class="btn btn-primary w-100" onclick="addLead()">Save</button>
                            </div>
                        </form>
                    </div>
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

    const addLead = () => {
        $.post(PANELURL + 'yoga-bookings/add', $('#addLeads').serialize())
            .done(response => {
                if (response.success === 1) {
                    $('#addLeads')[0].reset();
                    notifyAlert('success!', 'Data Added Successfully');
                    window.location.href = PANELURL + 'yoga-bookings';
                }
            })
            .fail(err => {
                console.error(err);
            });
    }

    // Toggle Payment UI
    $('#payment_type').change(function() {
        if ($(this).val() === 'Partition Payment') {
            $('#fullPayment').show();
            $('#fullPay').hide();
        } else {
            $('#fullPayment').hide();
            $('#fullPay').show();
        }
    });

    // Add dynamic row
    $('#rowAdder').click(function() {
        const newRow = `
            <div class="row mt-2 payment-row">
                <div class="col-6 d-flex">
                    <button class="btn btn-danger mr-2 delete-row" type="button"><i class="bi bi-trash"></i> Delete</button>
                    <input required type="number" name="fullPayment[]" class="form-control" placeholder="Enter Payment">
                </div>
                <div class="col-6">
                    <input type="datetime-local" name="fullPaymentDate[]" class="form-control">
                </div>
            </div>
        `;
        $('#newinput').append(newRow);
    });

    // Delete dynamic row
    $(document).on('click', '.delete-row', function() {
        $(this).closest('.payment-row').remove();
    });

    // Payable amount check
    const checkPayableAmount = () => {
        const payableAmount = parseInt($('#payableAmount').val()) || 0;
        const fullPayment = parseInt($('#fullPayType').val()) || 0;
        console.log('payableAmount', payableAmount);
        console.log('fullPayment', fullPayment);

        if (fullPayment > payableAmount) {
            $('#save').prop('disabled', true);
            $('#fullPayType').css({
                "border": "3px solid red",
                "color": "red"
            });
            $('#exceed').show();
        } else {
            $('#save').prop('disabled', false);
            $('#fullPayType').css({
                "border": "1px solid #ddd",
                "color": "black"
            });
            $('#exceed').hide();
        }
    }
</script>
@endsection