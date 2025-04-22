@extends('layouts.layout')

@section('content')
<style>
    #note {
        height: 20vh;
    }

    #note::placeholder {
        position: absolute;
        top: 10px;
        left: 10px;
    }
</style>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i
                            class="fas fa-backward"></i></button>&nbsp;
                    <h1>Add Expenses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Expenses</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="col-md-10 m-auto ">

                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads">
                            <div class="card-body">
                                <div class="row align-items-start">
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Payee Name</label>
                                        <input required type="text" class="form-control" id="payee" name="payee" placeholder="Enter Payee Name">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Expense Type</label>
                                        <select class="form-control" name="expenseType" id="expenseType">
                                            <option value="" selected>Select Expense Type</option>
                                            <option value="Advertiseing">Advertising</option>
                                            <option value="Salary">Salary</option>
                                            <option value="Other">Other</option>
                                        </select>
                                        <!-- <input required type="text"  id="clientName" name="expenseType" placeholder="Enter Expense Type"> -->
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Expense Amount</label>
                                        <input required type="number" class="form-control" id="expenseAmount" name="expenseAmount" placeholder="Enter Expense Amount">
                                    </div>
                                    <div class="form-group col-lg-6 col-sm-12">
                                        <label for="clientName">Expense Date</label>
                                        <input required type="date" class="form-control" id="expenseDate" name="expenseDate" placeholder="Enter Expense Date">
                                    </div>
                                    <div class="form-group col-lg-12 col-sm-12">
                                        <label for="clientName">Expense Notes</label>
                                        <input required type="text" class="form-control" id="note" name="note" placeholder="Enter Expense Notes">
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

    let addLead = () => {
        if ($('#expenseAmount').val() != '' && $('#note').val() != '' && $('#expenseDate').val() != '' && $('#expenseType').val() != '') {
            ajaxCallData(PANELURL + 'office-expences/add', $("#addLeads").serialize(), 'POST')
                .then(function(response) {
                    if (response.success == 1) {
                        $('#addLeads')[0].reset();
                        notifyAlert('Data Added Successfully', 'success', );
                        window.location.href = PANELURL + 'office-expences';
                    }
                })
                .catch(function(err) {
                    console.log(err);
                });
        } else {
            notifyAlert('Expenses cannot be empty', 'danger');
        }
    }
</script>
@endsection