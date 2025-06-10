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
                    <button class="btn btn-secondary btn-sm" id="back-btn"><i
                            class="fas fa-backward"></i></button>&nbsp;
                    <h1>Add Customer</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="dashboard">Home</a></li>
                        <li class="breadcrumb-item active">Add Customer</li>
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
                                        <input required type="text" class="form-control editInputBox" id="clientName"
                                            name="name" placeholder="Enter Client Name">
                                        <input type="hidden" value="1" name="attempt1">
                                        <input type="hidden" value="cutomerAdded" name="cutomerAdded">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientNumber">Client Number</label>
                                        <input required type="text" class="form-control editInputBox" id="clientNumber"
                                            name="number" placeholder="Enter Client Number">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientEmail">Email address</label>
                                        <input required type="email" class="form-control editInputBox" id="clientEmail"
                                            name="email" placeholder="Enter email">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Country</label>
                                        {{-- <select class="form-control countries" id="countryId" name="country">
                                            <option value="selected" selected>Select your Country</option>
                                        </select> --}}
                                        <input required type="text" class="form-control" id="country" name="country"
                                            placeholder="Enter country">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>States</label>
                                        <input required type="text" class="form-control" id="state" name="state"
                                            placeholder="Enter state">
                                        {{-- <select class="form-control states" id="stateId" name="state">
                                            <option value="selected" selected>Select your Country First</option>
                                        </select> --}}
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>City</label>
                                        <input required type="text" class="form-control" id="city" name="city"
                                            placeholder="Enter city">
                                        {{-- <select class="form-control cities" id="cityId" name="city">
                                            <option value="selected" selected>Select your state first</option>
                                        </select> --}}
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="date">Created Date</label>
                                        <input required type="datetime-local" class="form-control editInputBox"
                                            id="date" name="date" placeholder="Pickup date">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label>Type of Class</label>
                                        <select id="class_type" name="class"
                                            class="form-control editInputBox customEditInputBox">
                                            <option selected value=''>Select Your Class type</option>
                                            <option value="Home Visit Yoga">Home Visit Yoga</option>
                                            <option value="Private Online Yoga">Private Online Yoga</option>
                                            <option value="Group Online Yoga">Group Online Yoga</option>
                                            <option value="Corporate Yoga">Corporate Yoga</option>
                                            <option value="Retreat">Retreat</option>
                                            <option value="Workshop">Workshop</option>
                                            <option value="TTC">TTC</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientCallFrom">Call From</label>
                                        <input required type="time" class="form-control editInputBox customEditInputBox"
                                            id="clientCallFrom" name="call-from" placeholder="Enter call start time">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientCallTo">Call To</label>
                                        <input required type="time" class="form-control editInputBox customEditInputBox"
                                            id="clientCallTo" name="call-to" placeholder="Enter call end time">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientMessage">Client's Message</label>
                                        <input required type="text" class="form-control editInputBox customEditInputBox"
                                            id="clientMessage" name="client-message" placeholder="Enter your message">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientSource">Lead Source</label>
                                        <input required type="text" class="form-control editInputBox customEditInputBox"
                                            id="clientSource" name="lead-source" placeholder="Enter lead source">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="attendee_Name">
                                        <label for="attendeeName">Attendee Name</label>
                                        <input readonly required type="text"
                                            class="form-control editInputBox customEditInputBox" id="attendeeName"
                                            name="attendeeName" placeholder="Enter attendee name"
                                            value="{{ session('username') }}">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="packageEd">
                                        <label for="package">Package</label>
                                        <input required type="number" onKeyup="calculateP()"
                                            class="form-control editInputBox customEditInputBox" id="package"
                                            name="package" placeholder="Enter your package">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="quotationEd">
                                        <label for="quotation">Quotation</label>
                                        <input required type="number" onKeyup="calculateP()"
                                            class="form-control editInputBox customEditInputBox" id="quotation"
                                            name="quote" placeholder="Enter your message">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="payable">
                                        <label for="payableAmount">Payable Amount</label>
                                        <input readonly required type="number"
                                            class="form-control editInputBox customEditInputBox" id="payableAmount" />
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="demoEd">
                                        <label for="demopay">Trial Pay</label>
                                        <input required type="number"
                                            class="form-control editInputBox customEditInputBox" id="demopay"
                                            name="demoPay" placeholder="Enter Trial Pay">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="demoDate">
                                        <label for="demopay">Trial Date</label>
                                        <input required type="datetime-local"
                                            class="form-control editInputBox customEditInputBox" id="demDate"
                                            name="demDate" placeholder="Enter Trial Date">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="packageEndDate">
                                        <label for="packageEndDate">Package End Date</label>
                                        <input required type="datetime-local"
                                            class="form-control editInputBox customEditInputBox" id="packEndDate"
                                            name="packageEndDate" placeholder="Enter Package End Date">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="fullPatrainerPaymentyment">
                                        <label for="trainerPayment">Trainer's Payment</label>
                                        <input required type="number"
                                            class="form-control editInputBox customEditInputBox customerInputBox"
                                            id="trainerPayment" name="trainerPayment"
                                            placeholder="Enter Trainers Payment">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="fulltrainerPayDate">
                                        <label for="trainerPayDate">Trainer's Payment Date</label>
                                        <input required type="datetime-local"
                                            class="form-control editInputBox customEditInputBox customerInputBox"
                                            id="trainerPayDate" name="trainerPayDate">
                                    </div>

                                    <div class="form-group col-lg-6 col-sm-12" id="trainer">
                                        <label>Select Trainer</label>
                                        <div id="trainer_option"></div>
                                    </div>

                                    <div class="form-group col-6" id="fullPaymentType">
                                        <label>Type of Payment</label>
                                        <select id="payment_type" name="payment_type"
                                            class="form-control editInputBox customEditInputBox customerInputBox">
                                            <option selected value="Full Payment">Full Payment</option>
                                            <option value="Partition Payment">Partition Payment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-12 col-sm-12" id="fullPay">
                                        <label for="fullPayType">Full Payment</label>
                                        <div id="row" class="row mt-2">
                                            <div class="input-group col-lg-6">
                                                <input required type="number" id="fullPayType"
                                                    onkeyup="checkPayableAmount()" name="totalPayAmount"
                                                    placeholder="Enter Payment"
                                                    class="form-control m-input editInputBox customEditInputBox customerInputBox">
                                            </div>
                                            <div class="col-lg-6">
                                                <input type="datetime-local" name="totalPayDate"
                                                    class="form-control m-input editInputBox customEditInputBox customerInputBox">
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
                        <button type="button" class="btn btn-primary w-100" id="save" onclick="addLead()">Save</button>
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
</script>

<script>
    $('#exceed').hide();

        let param = "3";

        let editLead = (id) => {
            let postData = {
                id: id,
                _token: "{{ csrf_token() }}"
            };

            $.post("{{ url('lead') }}", postData)
                .done(function (resp) {
                    if (resp.success === 1) {
                        const response = resp.data;
                        const trainers = resp.trainers;

                        let html = `<select id="select_trainer" name="trainer" class="form-control select_trainer editInputBox">
                                        <option selected>Select Trainer</option>`;
                        $.each(trainers, function () {
                            html += `<option value="${this.id}">${this.name} || M- ${this.number}</option>`;
                        });
                        html += `</select>`;

                        $('#trainer_option').html(html);
                        $('.select_trainer').chosen();

                        const payType = $("#payment_type option:selected").text();
                        const fullPayAmount = $('#fullPayType').val();
                        $('#fullPayType').val(fullPayAmount);

                        togglePaymentType(payType, response.full_payment);

                        $('#payment_type').change(function () {
                            const selected = $("#payment_type option:selected").text();
                            togglePaymentType(selected, response.full_payment);
                        });
                    }
                })
                .fail(function (err) {
                    console.error(err);
                });
        };

        const togglePaymentType = (type, fullPayment) => {
            if (type === 'Partition Payment') {
                $('#fullPayment').show();
                $('#fullPay').hide();
            } else {
                $('#fullPay').show();
                $('#fullPayment').hide();
                $('#fullPayType').val(fullPayment);
            }
        };

        const addLead = () => {
            $.post("{{ url('customer/add') }}", $("#addLeads").serialize())
                .done(function (response) {
                    if (response.success === 1) {
                        $('#addLeads')[0].reset();
                        notifyAlert('success!', 'Data Added Successfully');
                        window.location.href = "{{ url('customer') }}";
                    }
                })
                .fail(function (err) {
                    console.error(err);
                });
        };

        $("#rowAdder").click(function () {
            let now = new Date().toISOString().slice(0, 16); // "yyyy-MM-ddThh:mm"
            let newRowAdd =
                `<div id="row" class="row mt-2">
                    <div class="input-group col-6">
                        <div class="input-group-prepend">
                            <button class="btn btn-danger" id="DeleteRow" type="button">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                        <input required type="number" name="fullPayment[]" placeholder="Enter Payment" class="form-control m-input editInputBox customEditInputBox customerInputBox">
                    </div>
                    <div class="col-6">
                        <input type="datetime-local" name="fullPaymentDate[]" class="form-control m-input editInputBox customEditInputBox customerInputBox" value="${now}">
                    </div>
                </div>`;

            $('#newinput').append(newRowAdd);
        });

        $("body").on("click", "#DeleteRow", function () {
            $(this).closest("#row").remove();
        });

        
        $('#back-btn').click(() => window.location.href = "{{ url('customer') }}");

        // Init on load
        editLead(param);
    const calculateP = () => {
            const packageVal = parseFloat($('#package').val()) || 0;
            const quotation = parseFloat($('#quotation').val()) || 0;
            $('#payableAmount').val(packageVal * quotation);
        };

        const checkPayableAmount = () => {
            const payAmnt = parseInt($('#payableAmount').val()) || 0;
            const fullAmnt = parseInt($('#fullPayType').val()) || 0;

            if (fullAmnt > payAmnt) {
                $('button#save').prop('disabled', true);
                $('#fullPayType').css({ "border": "3px solid red", "color": "red" });
                $('#exceed').show().css('color', 'red');
                notifyAlert('Full amount cannot exceed payable amount', 'danger');
                $('html').css('scroll-behavior', 'smooth');
            } else {
                $('#exceed').hide();
                $('button#save').prop('disabled', false);
                $('#fullPayType').css({ "border": "1px solid #ddd", "color": "black" });
            }
        };
</script>

@endsection