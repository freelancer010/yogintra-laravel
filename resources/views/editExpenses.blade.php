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

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-secondary btn-sm" onclick="history.back()"><i class="fas fa-backward"></i></button>&nbsp;
                    <h1>Edit Expenses</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Expenses</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="col-md-10 m-auto">
                <div class="card card-primary card-outline">
                    <div class="card-body">
                        <form id="addLeads">
                            @csrf
                            <div class="row align-items-start">
                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="payee">Payee Name</label>
                                    <input required type="text" class="form-control" id="payee" name="payee" placeholder="Enter Payee Name" value="{{ $expense->payee }}">
                                </div>

                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="expenseType">Expense Type</label>
                                    <select class="form-control" name="expenseType" id="expenseType" required>
                                        <option value="">Select Expense Type</option>
                                        <option value="Advertiseing" {{ $expense->expenseType === 'Advertiseing' ? 'selected' : '' }}>Advertising</option>
                                        <option value="Salary" {{ $expense->expenseType === 'Salary' ? 'selected' : '' }}>Salary</option>
                                        <option value="Other" {{ $expense->expenseType === 'Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                </div>

                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="expenseAmount">Expense Amount</label>
                                    <input required type="number" class="form-control" id="expenseAmount" name="expenseAmount" placeholder="Enter Expense Amount" value="{{ $expense->expenseAmount }}">
                                </div>

                                <div class="form-group col-lg-6 col-sm-12">
                                    <label for="expenseDate">Expense Date</label>
                                    <input required type="date" class="form-control" id="expenseDate" name="expenseDate" value="{{ \Carbon\Carbon::parse($expense->created_date)->format('Y-m-d') }}">
                                </div>

                                <div class="form-group col-lg-12 col-sm-12">
                                    <label for="note">Expense Notes</label>
                                    <input required type="text" class="form-control" id="note" name="note" placeholder="Enter Expense Notes" value="{{ $expense->note }}">
                                </div>
                            </div>

                            <div class="modal-footer justify-content-between">
                                <button type="button" class="btn btn-primary w-100" onclick="submitExpense()">Save</button>
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

    function submitExpense() {
        if ($('#expenseAmount').val() && $('#note').val() && $('#expenseDate').val() && $('#expenseType').val()) {
            $.post(PANELURL + "office-expences/edit/{{ $expense->id }}", $("#addLeads").serialize())
                .done(function(response) {
                    if (response.success === 1) {
                        $('#addLeads')[0].reset();
                        notifyAlert('Data Updated Successfully', 'success');
                        window.location.href = PANELURL + 'office-expences';
                    }
                })
                .fail(function(xhr) {
                    console.log(xhr.responseText);
                    notifyAlert('An error occurred', 'danger');
                });
        } else {
            notifyAlert('All fields are required', 'danger');
        }
    }
</script>
@endsection