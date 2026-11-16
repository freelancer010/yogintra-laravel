@extends('layouts.layout')

@section('content')
<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Ledger</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">Ledger</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header yogintra align-items-center d-flex justify-content-between">
                            <div class="row align-items-center ml-auto" style="margin-bottom:-2px">
                                <div class="filter d-flex justify-content-center align-items-center">
                                    <div class="d-flex mr-1 align-items-center">
                                        <button type="button" class="btn btn-sm btn-success mr-3 " onclick=filter()>
                                            Generate&nbsp;&nbsp;<i class="fas fa-arrow-right"></i>
                                        </button>
                                        <!-- <button type="button" class="btn btn-danger mr-3" onclick=reset()>reset</button> -->
                                    </div>
                                    <div class="d-flex mr-1 align-items-center">
                                        <!-- <label for="fromDate" class="exampleInputEmail1 mr-1 text-muted ">From</label> -->
                                        <input style="height: 32px;" type="date" class="form-control mr-3" id="fromDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <label for="toDate" class="exampleInputEmail1 mt-1 mr-3 text-muted">To</label>
                                        <input style="height: 32px;" type="date" class="form-control mr-1" id="toDate"
                                            max="<?php echo date('Y-m-d'); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <p id="financial-note" class="text-muted" role="status">Date filters use payment dates. Receipts and debits are separate entries.</p>
                            <select id="class_type" name="class" class="form-control editInputBox col-lg-4 m-auto">
                                <option value="all" selected>All categories</option><option value="Expense">Office expenses</option><option value="Uncategorized">Uncategorized</option>

                                <option value="Home Visit Yoga">Home Visit Yoga</option>
                                <option value="Private Online Yoga">Private Online Yoga</option>
                                <option value="Group Online Yoga">Group Online Yoga</option>
                                <option value="Corporate Yoga">Corporate Yoga</option>
                                <option value="Retreat">Retreat</option>
                                <option value="Workshop">Workshop</option>
                                <option value="TTC">TTC</option>
                                <option value="Yoga Center">Yoga Center</option>
                            </select>
                            <table id="example1" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Client Name</th>
                                        <th>Recorded credit</th>
                                        <th style="width:180px !important;">Date</th>
                                        <th>Trainer Name</th>
                                        <th>Recorded debit</th>
                                        <th style="width:180px !important;">Date</th>
                                        <th>Payment note</th>
                                    </tr>
                                </thead>
                                <tfoot><tr><th>Total</th><th id="totalCredit">0.00</th><th></th><th></th><th id="totalDebit">0.00</th><th></th><th></th></tr></tfoot>
                            </table>
                        </div>
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


    const amountRenderer = (value, type) => type === 'display' || type === 'filter' ? Number(value || 0).toFixed(2) : Number(value || 0);
    const financialNote = (resp, start, end) => {
        const base = 'Recorded customer, event and yoga receipts; trainer payments and office expenses. Deleted/voided and transferred lead records are excluded.';
        return resp.undatedCount > 0
            ? base + ` ${resp.undatedCount} entries have no reliable payment date (credit INR ${Number(resp.undatedCredit).toFixed(2)}, debit INR ${Number(resp.undatedDebit).toFixed(2)}). ` + (start || end ? 'They are excluded from this date range.' : 'They are included in all-time totals.')
            : base + ' Date filters use payment dates.';
    };

    const PANELURL = "{{ url('/') }}/";

    let filter = () => {
        let toDate = $("#toDate").val();
        let fromDate = $("#fromDate").val();
        getData($('#class_type').val() || 'all', fromDate, toDate);
    }

    let reset = () => {
        let toDate = $("#toDate").val('');
        let fromDate = $("#fromDate").val('');
        getData();
    }

    let getData = (class_type = 'all', startDate = '', endDate = '') => {
        var apiUrl = PANELURL + 'ledger';
        ajaxCallData(apiUrl, {
                'class_type': class_type,
                startDate: startDate,
                endDate: endDate
            }, 'POST')
            .then(function(resp) {
                $("#totalCredit").text(Number(resp.totalCredit || 0).toFixed(2));
                $("#totalDebit").text(Number(resp.totalDebit || 0).toFixed(2));
                $("#financial-note").text(financialNote(resp, startDate, endDate));
                if (resp.success == 1) {
                    response = resp.data;
                    let cols = [{
                            data: "name"
                        },
                        {
                            data: "full_payment", render: amountRenderer
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.full_payment == 0 ? '' : (row.created_date ? row.created_date.slice(0, 10) : 'Undated');
                            }
                        },
                        {
                            data: "trainerName"
                        },
                        {
                            data: "payTotrainer", render: amountRenderer
                        },
                        {
                            data: null,
                            render: function(data, type, row) {
                                return row.payTotrainer == 0 ? '' : (row.trainerPayDate ? row.trainerPayDate.slice(0, 10) : 'Undated');
                            }
                        },
                        { data: "note" }
                    ]
                    createDataTable("example1", response, cols, 2);
                } else {
                    createDataTable("example1", '', '');
                }
            })
            .catch(function(err) {
                console.log(err);
            });
    };
    getData();

    $('#class_type').on('change', function() {
        getData(this.value, $('#fromDate').val(), $('#toDate').val());
    });
</script>
@endsection