<?php

namespace App\Http\Controllers;

use App\Mail\BookingMail;
use App\Models\Booking;
use App\Models\APIStructure;
use App\Models\BookingSeat;
use App\Models\Location;
use App\Models\Season;
use App\Models\Train;
use App\Models\TrainTickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class BookingController extends Controller
{
    public function index()
    {
        $title = 'Create New Booking';
        $locations = Location::get();
        $trains = Train::whereIn('status', [1, 2])->with('startdata')->with('enddata')->get();
        $bookingsQuery = Booking::where('status', 1);

        if (Auth::user()->usertype == 2) {
            $bookingsQuery->where('user', Auth::user()->id);
        }

        $bookings = $bookingsQuery->with('seatsdata')->with('startdata')->with('enddata')->orderBy('id', 'DESC')->get();

        return view('pages.booking', compact(['title', 'locations', 'trains', 'bookings']));
    }

    public function viewPass($seatid)
    {
        $data = BookingSeat::where('id', $seatid)->first();
        $code = $seatid . '/' . $data->turn . '/' . $data->start . '/' . $data->end;
        return view('prints.qr', compact(['code']));
    }

    public function enrollAPI(Request $request)
    {
        $booking = Booking::create([
            'user' => Auth::user()->id, 'date' => $request->date, 'price' => $request->amount, 'turn' => $request->turn, 'start' => $request->start, 'end' => $request->end
        ]);

        foreach (json_decode($request->seats) as $keySeat => $valueSeat) {
            BookingSeat::create([
                'booking' => $booking->id,
                'turn' => $request->turn,
                'seat' => $valueSeat-1,
                'start' => $request->start,
                'end' => $request->end,
                'starttime' => $request->starttime,
                'endtime' => $request->endtime
            ]);
        }

        $bookingdata = Booking::where('id', $booking->id)->with('seatsdata')->first();

        Mail::to(Auth::user()->email)->send(new BookingMail($bookingdata));

        return APIStructure::getResponse(Booking::where('user', Auth::user()->id)->get(), [], 200);
    }

    public function enroll(Request $request)
    {
        $booking = Booking::create([
            'user' => Auth::user()->id, 'date' => $request->date, 'price' => $request->amount, 'turn' => $request->turn, 'start' => $request->start, 'end' => $request->end
        ]);

        foreach ($request->seats as $keySeat => $valueSeat) {
            BookingSeat::create([
                'booking' => $booking->id,
                'turn' => $request->turn,
                'seat' => $valueSeat,
                'start' => $request->start,
                'end' => $request->end,
                'starttime' => $request->starttime,
                'endtime' => $request->endtime
            ]);
        }

        $bookingdata = Booking::where('id', $booking->id)->with('seatsdata')->first();

        Mail::to(Auth::user()->email)->send(new BookingMail($bookingdata));

        return 1;
    }

    public function getTicketPrices($train, $location1, $location2)
    {
        $class1 = TrainTickets::where('class', 1)->where('train', $train)->where('start', $location1)->where('end', $location2)->first();
        $class2 = TrainTickets::where('class', 2)->where('train', $train)->where('start', $location1)->where('end', $location2)->first();
        $class3 = TrainTickets::where('class', 3)->where('train', $train)->where('start', $location1)->where('end', $location2)->first();
        return [
            (($class1) ? $class1->price : 0),
            (($class2) ? $class2->price : 0),
            (($class3) ? $class3->price : 0)
        ];
    }

    public function isValidSeason($authcode)
    {
        if (Season::where('from', '<=', Carbon::now())->where('to', '>=', Carbon::now())->where('authcode', $authcode)->first()) {
            return 1;
        } else {
            return 3;
        }
    }

    public function checkAttend($seatid, $turnno, $start, $end, $station)
    {
        $seat = BookingSeat::where('id', $seatid)->first();
        if ($seat) {
            if ($seat->status == 4 || $seat->status == 3) {
                return 3;
            } else {
                if ($seat->status == 1 && $seat->start == $station) {
                    $seat->update([
                        'status' => 2
                    ]);
                    return 1;
                } else if ($seat->status == 2 && $seat->end == $station) {
                    $seat->update([
                        'status' => 3
                    ]);
                    return 2;
                } else {
                    return 3;
                }
            }
        } else {
            return 3; //show error
        }
    }

    public function getBookedSeats($turn, $start, $end)
    {
        $data = [];
        foreach (BookingSeat::select('seat')->where('status', 1)->where('endtime', '>', Carbon::now())->where('turn', $turn)->where('starttime', $start)->where('endtime', $end)->get() as $key => $value) {
            $data[] = $value->seat;
        }
        return $data;
    }
}
