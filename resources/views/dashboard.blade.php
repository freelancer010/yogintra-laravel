@extends('layouts.layout')

@section('title', 'Dashboard')
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
      <div class="dashboard-intro"><div><div class="eyebrow">YOUR DAILY OVERVIEW</div><h2>Welcome back{{ session('fullName') ? ', ' . explode(' ', trim(session('fullName')))[0] : '' }}.</h2><p>Your community, connections, and progress in one place.</p></div><div class="intro-mark" aria-hidden="true">y</div></div>
      <div id="dashboard-status" class="dashboard-status" role="status" aria-live="polite">Loading your overview...</div>
      <div class="row justify-content-center">

        @if(session('admin_role_id') == 13)
        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 data-dashboard-count id="yoga">&mdash;</h3>
              <p>Yoga bookings</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="{{ url('yoga-bookings') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @else

        @if(session('admin_role_id') != 4)
        <div class="col-lg-4 col-6">
          <div class="small-box bg-secondary">
            <div class="inner">
              <h3 data-dashboard-count id="leads">&mdash;</h3>
              <p>Leads</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-list"></i></div>
            <a href="{{ url('lead') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 data-dashboard-count id="telecaller">&mdash;</h3>
              <p>Telecalling records</p>
            </div>
            <div class="icon"><i class="fas fa-headset"></i></div>
            <a href="{{ url('telecalling') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 data-dashboard-count id="customer">&mdash;</h3>
              <p>Customers</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ url('customer') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-primary">
            <div class="inner">
              <h3 data-dashboard-count id="trainer">&mdash;</h3>
              <p>Trainers</p><small id="unreadTrainer" class="metric-note"></small>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="{{ url('trainers') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-danger">
            <div class="inner">
              <h3 data-dashboard-count id="events">&mdash;</h3>
              <p>Events</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <a href="{{ url('event') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-info">
            <div class="inner">
              <h3 data-dashboard-count id="yoga">&mdash;</h3>
              <p>Yoga bookings</p>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="{{ url('yoga-bookings') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @else
        <div class="col-lg-4 col-6">
          <div class="small-box bg-warning">
            <div class="inner">
              <h3 data-dashboard-count id="recruits">&mdash;</h3>
              <p>Recruits</p><small id="unreadRecruits" class="metric-note"></small>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ url('recruiter') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>

        <div class="col-lg-4 col-6">
          <div class="small-box bg-success">
            <div class="inner">
              <h3 data-dashboard-count id="trainer">&mdash;</h3>
              <p>Trainers</p><small id="unreadTrainer" class="metric-note"></small>
            </div>
            <div class="icon"><i class="fas fa-chart-line"></i></div>
            <a href="{{ url('trainers') }}" class="small-box-footer">View details <i class="fas fa-arrow-circle-right"></i></a>
          </div>
        </div>
        @endif
        @endif

        @if(in_array(session('admin_role_id'), [1, 2, 3]))
        <div class="col-lg-6">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Community overview</h3><span class="chart-period">All time &middot; current record statuses</span>
            </div>
            <div class="card-body">
              <div class="chart-summary">
                <div class="compact-chart"><canvas id="allChart" role="img" aria-label="Distribution of leads, telecalling records, customers, and rejected records"></canvas><p class="chart-empty" id="community-empty" hidden>No records yet</p></div>
                <dl class="chart-breakdown" id="community-breakdown"><div><dt>Records</dt><dd>&mdash;</dd></div></dl>
              </div><p class="chart-footnote">Current status distribution, not a historical conversion rate.</p>
            </div>
          </div>
        </div>
        @endif

        @if(in_array(session('admin_role_id'), [1, 2, 5]))
        <div class="col-lg-6">
          <div class="card card-info">
            <div class="card-header">
              <h3 class="card-title">Financial overview</h3><span class="chart-period">All time &middot; recorded amounts</span>
            </div>
            <div class="card-body">
              <div class="chart-summary">
                <div class="compact-chart"><canvas id="myChart" role="img" aria-label="Comparison of recorded credit and debit amounts"></canvas><p class="chart-empty" id="finance-empty" hidden>No amounts yet</p></div>
                <dl class="chart-breakdown" id="finance-breakdown"><div><dt>Amounts</dt><dd>&mdash;</dd></div></dl>
              </div><p class="chart-footnote">All-time customer, event and yoga receipts; recorded trainer payments and office expenses. Includes undated amounts. Excludes deleted/voided records and transferred lead records. Net balance = credit minus debit.</p>
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
<script src="{{ asset('assets/js/dashboard.js') }}?v=3" data-counts-url="{{ url('counts') }}" defer></script>
@endsection
