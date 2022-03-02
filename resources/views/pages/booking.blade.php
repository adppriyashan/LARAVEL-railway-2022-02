@extends('layouts.app')

@section('content')

    <body id="page-top">
        <div id="wrapper">

            @include('layouts.sidebar')

            <div id="content-wrapper" class="d-flex flex-column">

                <div id="content">
                    @include('layouts.navbar')

                    <div class="container-fluid">
                        <div class="row justify-content-center">
                            @include('layouts.flash')
                            <div class="col-md-7">
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
                                                    @foreach ($bookings as $book)
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
                            <div class="col-md-5">
                                <div class="card shadow mb-4">
                                    <div class="card-header py-3">
                                        <h6 class="m-0 font-weight-bold text-primary">Create New Booking</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <label for=""><small>Journey Date</small></label>
                                                <input required class="form-control checktrain" id="date" name="date"
                                                    type="date">
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label for="start"><small>Start Location</small></label>
                                                <select required autocomplete="false" name="start" id="start"
                                                    class="form-control checktrain">
                                                    <option value="none" selected>- Select -</option>
                                                    @foreach ($locations as $location)
                                                        <option {{ old('start') == $location->id ? 'selected' : '' }}
                                                            value="{{ $location->id }}">{{ $location->location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('start')
                                                    <p class="text-danger"><small>{{ $message }}</small></p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label for="end"><small>End Location</small></label>
                                                <select required autocomplete="false" name="end" id="end"
                                                    class="form-control checktrain">
                                                    <option value="none" selected>- Select -</option>
                                                    @foreach ($locations as $location)
                                                        <option {{ old('end') == $location->id ? 'selected' : '' }}
                                                            value="{{ $location->id }}">{{ $location->location }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                @error('end')
                                                    <p class="text-danger"><small>{{ $message }}</small></p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label for="train"><small>Available Trains</small></label>
                                                <select required disabled autocomplete="false" name="train" id="train"
                                                    class="form-control">
                                                    <option value="none" selected>- Select -</option>
                                                </select>
                                                @error('start')
                                                    <p class="text-danger"><small>{{ $message }}</small></p>
                                                @enderror
                                            </div>
                                        </div>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label for=""><small>Train Seats</small></label>
                                                <div id="seatsdivmain">
                                                    <span class="alert alert-danger w-100"><small>Please select above
                                                            details first</small></span>
                                                </div>
                                                <br>
                                                <div class="row justify-content-center mt-3">
                                                    <div class="class1 infoseat"><small>1st Class</small></div>
                                                    <div class="class2 infoseat"><small>2nd Class</small></div>
                                                    <div class="class3 infoseat"><small>3rd Class</small></div>
                                                    <div class="class3 infoseat windowedseat"><small>Windowed</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-3 justify-content-center">
                                            <div class="col-md-12">
                                                <button id="submitbtn" class="btn btn-primary w-100">Confirm
                                                    Booking</button>
                                            </div>
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

        <script>
            var dataRetrived = [];

            var date = $('#date');
            var start = $('#start');
            var end = $('#end');
            var train = $('#train');
            var seatsdiv = $('#seatsdivmain');
            var grandtotal = 0.0;

            $('.checktrain').on('change', function() {

                if (date.val() && start.val() != 'none' && end.val() != 'none') {
                    $.ajax({
                        type: "GET",
                        url: "{{ route('trains.get.available') }}",
                        data: {
                            'date': date.val(),
                            'start': start.val(),
                            'end': end.val()
                        },
                        success: function(data) {
                            train.html('');
                            train.append($('<option selected disabled value="none">-Select-</option>'));
                            if (data.length > 0) {
                                dataRetrived = data;
                                let indexRec = 0;
                                dataRetrived.forEach((record) => {
                                    record.schedules.forEach((record2) => {
                                        train.append($(
                                            '<option starttime="' + record2[0]
                                            .slot + '" endtime="' + record2[1]
                                            .slot + '" turn="' +
                                            record2[0].turn +
                                            '" train="' + record.train.id +
                                            '" indexvalue="' + indexRec +
                                            '" value="' + record.train.id + '">' +
                                            record.train.alias + '(' + record2[0]
                                            .slot + ' - ' + record2[1].slot + ')' +
                                            '</option>'));
                                    });
                                    indexRec++;
                                });
                                train.removeAttr('disabled');
                            } else {
                                train.append($('<option selected disabled value="none">-Select-</option>'));
                            }
                        }
                    });
                } else {
                    train.attr('disabled', '');
                }
            });

            train.on('change', function(e) {
                grandtotal = 0.0;
                let trainVal = $('option:selected', this).attr('indexvalue');
                let trainId = $(this).attr('train');
                let turnno = $('option:selected', this).attr('turn');
                let starttime = $('option:selected', this).attr('starttime');
                let endtime = $('option:selected', this).attr('endtime');
                if (trainVal && trainVal != 'none') {
                    let trainData = dataRetrived[trainVal].train;
                    let scheduleData = dataRetrived[trainVal].schedules;
                    let firstClass = trainData.firstclass;
                    let secondClass = trainData.secondclass;
                    let thirdClass = trainData.thirdclass;
                    let firstpay = 0.0;
                    let secondpay = 0.0;
                    let thirdpay = 0.0;

                    let seatIndexes = 0;

                    $.ajax({
                        type: "GET",
                        url: "/bookings/getTicketPrices/" + train.val() + "/" + start.val() + "/" + end.val(),
                        success: function(data) {
                            firstpay = data[0];
                            secondpay = data[1];
                            thirdpay = data[2];
                            if (firstClass > 0 || secondClass > 0 || thirdClass > 0) {

                                $.ajax({
                                    type: "GET",
                                    url: "/bookings/getBookedSeats/" + turnno + "/" + starttime +
                                        "/" + endtime,
                                    success: function(bookedSeats) {
                                        seatsdiv.html('');
                                        //first class
                                        let tcreset = 1;
                                        let content = '';

                                        if (firstpay > 0) {
                                            tcreset = 1;

                                            for (var fc = 0; fc < Number(firstClass); fc++) {

                                                if (tcreset == 1) {
                                                    content += '<div class="seathorizontal">';
                                                }

                                                content +=
                                                    '<div class="pl-1"><input ' + ((jQuery
                                                            .inArray(seatIndexes,
                                                                bookedSeats) !== -1) ?
                                                        'disabled' : '') +
                                                    ' type="checkbox" amount="' + firstpay +
                                                    '" class="inputSeat" id="seat' +
                                                    seatIndexes + '" name="seats[]" value="' +
                                                    seatIndexes +
                                                    '" /><label class="inputSeatLabel class1 ' +
                                                    ((tcreset == 1 ||
                                                        tcreset == 4) ? 'windowedseat' : '') +
                                                    '" for="seat' +
                                                    seatIndexes +
                                                    '"><small>' + ((jQuery.inArray(seatIndexes,
                                                        bookedSeats) !== -1) ? '-' : (
                                                        seatIndexes)) +
                                                    '</small></label></div>';

                                                tcreset++;
                                                if (tcreset == 5) {
                                                    content += '</div>';
                                                    tcreset = 1
                                                } else {
                                                    if (fc + 1 == Number(firstClass)) {
                                                        content += '</div>';
                                                    }
                                                }
                                                seatIndexes++;
                                            };
                                        }

                                        //second class
                                        if (secondpay > 0) {
                                            tcreset = 1;
                                            for (var sc = 0; sc < Number(secondClass); sc++) {

                                                if (tcreset == 1) {
                                                    content += '<div class="seathorizontal">';
                                                }

                                                content +=
                                                    '<div class="pl-1"><input ' + ((jQuery
                                                            .inArray(seatIndexes,
                                                                bookedSeats) !== -1) ?
                                                        'disabled' : '') +
                                                    ' amount="' +
                                                    secondpay +
                                                    '" type="checkbox" class="inputSeat" id="seat' +
                                                    seatIndexes + '" name="seats[]"  value="' +
                                                    seatIndexes +
                                                    '"/><label class="inputSeatLabel class2 ' +
                                                    ((tcreset == 1 ||
                                                        tcreset == 4) ? 'windowedseat' : '') +
                                                    ' " for="seat' +
                                                    seatIndexes +
                                                    '"><small>' + ((jQuery.inArray(seatIndexes,
                                                        bookedSeats) !== -1) ? '-' : (
                                                        seatIndexes)) +
                                                    '</small></label></div>';

                                                tcreset++;
                                                if (tcreset == 5) {
                                                    content += '</div>';
                                                    tcreset = 1
                                                } else {
                                                    if (sc + 1 == Number(secondClass)) {
                                                        content += '</div>';
                                                    }
                                                }
                                                seatIndexes++;
                                            };
                                        }

                                        //third class
                                        if (thirdpay > 0) {
                                            tcreset = 1;
                                            for (var tc = 0; tc < Number(thirdClass); tc++) {

                                                if (tcreset == 1) {
                                                    content += '<div class="seathorizontal">';
                                                }

                                                content +=
                                                    '<div class="pl-1"><input ' + ((jQuery
                                                            .inArray(seatIndexes,
                                                                bookedSeats) !== -1) ?
                                                        'disabled' : '') +
                                                    ' amount="' +
                                                    thirdpay +
                                                    '" type="checkbox" class="inputSeat" id="seat' +
                                                    seatIndexes + '" name="seats[]"  value="' +
                                                    seatIndexes +
                                                    '"/><label class="inputSeatLabel class3 ' +
                                                    ((tcreset == 1 ||
                                                        tcreset == 4) ? 'windowedseat' : '') +
                                                    '" for="seat' +
                                                    seatIndexes +
                                                    '"><small>' + ((jQuery.inArray(seatIndexes,
                                                        bookedSeats) !== -1) ? '-' : (
                                                        seatIndexes)) +
                                                    '</small></label></div>';

                                                tcreset++;
                                                if (tcreset == 5) {
                                                    content += '</div>';
                                                    tcreset = 1
                                                } else {
                                                    if (tc + 1 == Number(thirdClass)) {
                                                        content += '</div>';
                                                    }
                                                }
                                                seatIndexes++;
                                            };
                                        }

                                        seatsdiv.append($(content));
                                    }
                                });

                            }
                        }
                    });
                }
            });

            var bookedSeats = [];


            $('#submitbtn').on('click', function(e) {
                bookedSeats = [];
                $(':checkbox:checked').each(function(i) {
                    bookedSeats.push($(this).val());
                    grandtotal += Number($(this).attr('amount'));
                });

                if (bookedSeats.length > 0 && date.val() && start.val() && start.val() != 'none' && end.val() && end
                    .val() != 'none' && grandtotal > 0) {
                    $('#paybtn').html('Confirm Payment (' + grandtotal + ')');
                    $('#paymodal').modal('show');
                } else {
                    alert('Please select above options');
                }
            });

            $('#paybtn').on('click', function(e) {

                let username = $('#username');
                let cardNumber = $('#cardNumber');
                let cardMonth = $('#cardMonth');
                let cardYear = $('#cardYear');
                let cvv = $('#cvv');

                if (username.val() && cardNumber.val() && cardMonth.val() && cardYear.val() && cvv.val()) {
                    let formdata = {
                        'date': date.val(),
                        'starttime': $('option:selected', train).attr('starttime'),
                        'endtime': $('option:selected', train).attr('endtime'),
                        'start': start.val(),
                        'end': end.val(),
                        'seats': bookedSeats,
                        'amount': grandtotal,
                        'turn': $('option:selected', train).attr('turn')
                    }

                    $.ajax({
                        type: "GET",
                        url: "{{ route('bookings.enroll') }}",
                        data: formdata,
                        success: function(data) {
                            if (data == 2) {
                                alert('Payment failed');
                            } else {
                                showAlert('Booking received. Thank You!', function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                } else {
                    alert('Pleae fill payment details before processing the payment');
                }


            });
        </script>


    </body>
@endsection
