@extends('layouts.app')

@section('content')

    <body id="page-top">
        <div id="wrapper">

            @include('layouts.sidebar')

            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">
                    @include('layouts.navbar')

                    <div class="container-fluid">
                        <div class="row">
                            @include('layouts.flash')
                            <div class="col-md-8">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Season List</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-bordered w-100" id="dataTable">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>NIC</th>
                                                        <th>From</th>
                                                        <th>To</th>
                                                        <th>Status</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse ($seasons as $keySeason=> $season)
                                                        <tr>
                                                            <td>{{ $keySeason + 1 }}</td>
                                                            <td>{{ $season->nic }}</td>
                                                            <td>{{ $season->from }}</td>
                                                            <td>{{ $season->to }}</td>
                                                            <td> <span
                                                                    class="badge badge-{{ getStatusColors($season->status) }}">{{ App\Models\Season::$status[$season->status] }}</span>
                                                            </td>
                                                            <td>
                                                                <i record="{{ $season->authcode }}"
                                                                    class="showqr fa fa-eye mx-2 text-primary"></i>
                                                                <i record="{{ $season->id }}"
                                                                    class="doedit far fa-edit mx-2 text-warning"></i><i
                                                                    record="{{ $season->id }}"
                                                                    class="dodelete fas fa-trash-alt mx-2 text-danger"></i>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6">No Records Found</td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <form method="POST" autocomplete="off" action="{{ route('seasons.enroll') }}">
                                    <div class="card shadow mb-4">
                                        <div class="card-header py-3">
                                            <h6 class="m-0 font-weight-bold text-primary">Add / Update Seasons</h6>
                                        </div>
                                        <div class="card-body">
                                            @csrf
                                            <input id="isnew" name="isnew" value="1" type="hidden">
                                            <input id="id" name="id" value="" type="hidden">
                                            <div class="row">
                                                <div class="col-md-12 form-group">
                                                    <label for="nic"><small>NIC</small></label>
                                                    <input autocomplete="false" class="form-control" type="text"
                                                        name="nic" id="nic" value="{{ old('nic') }}">
                                                    @error('nic')
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="location1"><small>Start Location</small></label>
                                                    <select autocomplete="false"  name="location1" id="location1" class="form-control">
                                                        <option value="none" selected>- Select -</option>
                                                        @foreach ($locations as $location)
                                                            <option {{ old('location1') == $location->id ? 'selected' : '' }}
                                                                value="{{ $location->id }}">{{ $location->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('location1')
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="location2"><small>End Location</small></label>
                                                    <select autocomplete="false"  name="location2" id="location2" class="form-control">
                                                        <option value="none" selected>- Select -</option>
                                                        @foreach ($locations as $location)
                                                            <option {{ old('location2') == $location->id ? 'selected' : '' }}
                                                                value="{{ $location->id }}">{{ $location->location }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    @error('start')
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="from"><small>From</small></label>
                                                    <input autocomplete="false" class="form-control" type="date"
                                                        name="from" id="from" value="{{ old('from') }}">
                                                    @error('from')
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="to"><small>To</small></label>
                                                    <input autocomplete="false" class="form-control" type="date" name="to"
                                                        id="to" value="{{ old('to') }}">
                                                    @error('to')
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                                <div class="col-md-12 form-group">
                                                    <label for="end"><small>Status</small></label>
                                                    <select autocomplete="false" class="form-control" name="status"
                                                        id="status">
                                                        <option {{ old('status') == 1 ? 'selected' : '' }} value="1">
                                                            Active
                                                        </option>
                                                        <option {{ old('status') == 2 ? 'selected' : '' }} value="2">
                                                            Inactive
                                                        </option>
                                                    </select>
                                                    @error('status')
                                                        <p class="text-danger"><small>{{ $message }}</small></p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card-footer">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <button type="reset" class="btn btn-danger w-100">Clear</button>
                                                </div>
                                                <div class="col-md-6">
                                                    <button class="btn btn-success w-100">Submit</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
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

        <script>
            $('.doedit').on('click', function() {
                let id = $(this).attr('record');
                showAlert('Are you aure to edit this record', function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('seasons.get') }}",
                        data: {
                            'id': id
                        },
                        success: function(response) {
                            $('#nic').val(response.nic);
                            $('#from').val(response.from);
                            $('#to').val(response.to);
                            $('#location1').val(response.location1);
                            $('#location2').val(response.location2);
                            $('#status').val(response.status);
                            $('#isnew').val(2);
                            $('#id').val(response.id);
                        }
                    });
                });
            });

            $('.dodelete').on('click', function() {
                let id = $(this).attr('record');
                showAlert('Are you aure to delete this record', function() {
                    $.ajax({
                        type: "POST",
                        url: "{{ route('seasons.delete') }}",
                        data: {
                            'id': id
                        },
                        success: function(response) {
                            window.location.reload();
                        }
                    });
                });
            });
        </script>
    </body>
@endsection
