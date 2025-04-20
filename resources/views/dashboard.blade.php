@extends('layouts.layout')

@section('content')
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <div class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
          <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </div>
      </div>
    </div>
  </div>

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row justify-content-center">

        @if(session('admin_role_id') == 13)
        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="yoga"></h3>
              <p id="y"></p>
            </div>
            <div class="icon"><i class="ion ion-stats-bars"></i></div>
            <a href="{{ url('yoga-bookings') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @else

        @if(session('admin_role_id') != 4)
        <div class="col-lg-4 col-6">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3 id="leads"></h3>
              <p>Leads</p>
            </div>
            <div class="icon"><i class="ion ion-bag"></i></div>
            <a href="{{ url('lead') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="telecaller"></h3>
              <p>Telecallers</p>
            </div>
            <div class="icon"><i class="ion ion-pie-graph"></i></div>
            <a href="{{ url('telecalling') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="customer"></h3>
              <p>Customers</p>
            </div>
            <div class="icon"><i class="ion ion-person-add"></i></div>
            <a href="{{ url('customer') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3 id="trainer"></h3>
              <p id="unreadTrainer"></p>
            </div>
            <div class="icon"><i class="ion ion-stats-bars"></i></div>
            <a href="{{ url('trainers') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3 id="events"></h3>
              <p id="e"></p>
            </div>
            <div class="icon"><i class="ion ion-calendar"></i></div>
            <a href="{{ url('event') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 id="yoga"></h3>
              <p id="y"></p>
            </div>
            <div class="icon"><i class="ion ion-stats-bars"></i></div>
            <a href="{{ url('yoga-bookings') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @else
        <div class="col-lg-4 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 id="recruits"></h3>
              <p id="unreadRecruits"></p>
            </div>
            <div class="icon"><i class="ion ion-person-add"></i></div>
            <a href="{{ url('recruiter') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 id="trainer"></h3>
              <p id="unreadTrainer"></p>
            </div>
            <div class="icon"><i class="ion ion-stats-bars"></i></div>
            <a href="{{ url('trainers') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @endif
        @endif

        @if(in_array(session('admin_role_id'), [1, 2, 3]))
        <div class="col-lg-6">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">All Data Chart</h3>
            </div>
            <div class="card-body">
              <canvas id="allChart" style="height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        @endif

        @if(in_array(session('admin_role_id'), [1, 2, 5]))
        <div class="col-lg-6">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Accounts Chart</h3>
            </div>
            <div class="card-body">
              <canvas id="myChart" style="height: 250px; max-width: 100%;"></canvas>
            </div>
          </div>
        </div>
        @endif

      </div>
    </div>
  </section>
</div>
@endsection

@section('scripts')
<script>
  var allDataCounts = '';

  const ctx = document.getElementById('myChart');
  const atx = document.getElementById('allChart');

  fetch("{{ url('counts') }}")
    .then(res => res.json())
    .then(resp => {
      $('#leads').text(resp.lead);
      $('#unreadLeads').text('Leads (' + resp.unreadLeads + ' Unread)');
      $('#trainer').text(resp.trainer);
      $('#unreadTrainer').text('Trainers');
      $('#recruits').text(resp.recruits);
      $('#unreadRecruits').text('Recruits (' + resp.unreadRecruits + ' Unread)');
      $('#customer').text(resp.customer);
      $('#telecaller').text(resp.telecaller);
      $('#events').text(resp.events);
      $('#e').text('Events');
      $('#yoga').text(resp.yoga);
      $('#y').text('Yoga Centers');

      new Chart(atx, {
        type: 'doughnut',
        data: {
          labels: ['Leads', 'Telecallers', 'Customers', 'Rejected'],
          datasets: [{
            data: [resp.lead, resp.telecaller, resp.customer, resp.rejected],
            backgroundColor: ["#6c757d", "#ffc107", "#28a745", "#dc3545"],
            borderWidth: 1
          }]
        }
      });

      new Chart(ctx, {
        type: 'pie',
        data: {
          labels: ['Credit Amount', 'Debit Amount', 'Net Profit'],
          datasets: [{
            data: [resp.totalCredit, resp.totalDebit, (resp.totalCredit - resp.totalDebit)],
            backgroundColor: ["#ffc107", "#dc3545", "#28a745"],
            borderWidth: 1
          }]
        }
      });
    });

  setTimeout(() => {
    let leadsCount = $('#leads').text();
    if (leadsCount === '') {
      window.location = "{{ url('logout') }}";
    }
  }, 1500);
</script>
@endsection