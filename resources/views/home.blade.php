@extends('layouts.app')

@section('content')

    <body id="page-top">
        <div id="wrapper">

           @include('layouts.sidebar')

            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">
                    @include('layouts.navbar')

                    <!-- End of Topbar -->

                    <!-- Begin Page Content -->
                    <div class="container-fluid">

                        <!-- Page Heading -->
                        <div class="d-sm-flex align-items-center justify-content-between mb-4">
                            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
                        </div>

                        <!-- Content Row -->
                        <div class="row">

                            <!-- Earnings (Monthly) Card Example -->
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card border-left-primary shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                    Available Trains</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $traincount }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fas fa-calendar fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-6 col-md-6 mb-4">
                                <div class="card border-left-success shadow h-100 py-2">
                                    <div class="card-body">
                                        <div class="row no-gutters align-items-center">
                                            <div class="col mr-2">
                                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                    Bookings</div>
                                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $bookingcount }}</div>
                                            </div>
                                            <div class="col-auto">
                                                <i class="fa fa-bolt fa-2x text-gray-300"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Booking List</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table">
                                                <thead>
                                                    <tr>
                                                        <th>Turn</th>
                                                        <th>Date</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Ticket Count</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($availablebookings as $book)
                                                        <tr>
                                                            <td>{{ $book->turn }}</td>
                                                            <td>{{ $book->date }}</td>
                                                            <td>{{ $book['startdata']->location }}</td>
                                                            <td>{{ $book['enddata']->location }}</td>
                                                            <td>{{ count($book['seatsdata']) }}</td>
                                                        </tr>
                                                    @endforeach

                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>
                @include('layouts.footer')

            </div>

        </div>

        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        @include('layouts.scripts')
    </body>
@endsection
