<?php

namespace App\Http\Controllers;

use App\Models\APIStructure;
use App\Models\BookingSeat;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\Train;
use App\Models\TrainTickets;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class TrainController extends Controller
{
    public function index()
    {

        if(Auth::user()->usertype==2){
            return redirect('/');
        }

        $title = 'Train Management';
        $locations = Location::get();
        $trains = Train::whereIn('status', [1, 2])->with('startdata')->with('enddata')->get();

        return view('pages.trains', compact(['title', 'locations', 'trains']));
    }

    public function getAvailableAPI(Request $request)
    {
        $date = $request->date;
        $startLocation = $request->start;
        $endLocation = $request->end;

        $data = [];

        foreach (Train::where('status', 1)->get() as $keyTrain => $valueTrain) {
            $scheduleData = [];
            $startLocationRec = null;
            foreach (Schedule::where('train', $valueTrain->id)->where('slot','>',Carbon::now()->timezone('Asia/Colombo'))->whereDate('slot', $date)->orderBy('slot', 'ASC')->get() as $keySchedule => $valueSchedule) {
                if ($valueSchedule->location == $startLocation && $startLocationRec == null) {
                    $startLocationRec = $valueSchedule;
                }

                if ($startLocationRec != null && $valueSchedule->location == $endLocation && $startLocationRec->turn == $valueSchedule->turn) {
                    $scheduleData[] = $startLocationRec;
                    $scheduleData[] = $valueSchedule;
                    $startLocationRec = null;
                }
            }
            if (count($scheduleData) > 0) {
                $bookeddata = [];
                foreach (BookingSeat::select('seat')->where('status', 1)->where('endtime', '>', Carbon::now())->where('turn',$scheduleData[0]->turn)->where('starttime', $scheduleData[0]->slot)->where('endtime', $scheduleData[1]->slot)->get() as $key => $value) {
                    $bookeddata[] = $value->seat;
                }
                $class1 = TrainTickets::where('class', 1)->where('train', $valueTrain->id)->where('start', $startLocation)->where('end', $endLocation)->first();
                $class2 = TrainTickets::where('class', 2)->where('train', $valueTrain->id)->where('start', $startLocation)->where('end', $endLocation)->first();
                $class3 = TrainTickets::where('class', 3)->where('train', $valueTrain->id)->where('start', $startLocation)->where('end', $endLocation)->first();
                $valueTrain['class1price']=(($class1) ? $class1->price : 0);
                $valueTrain['class2price']=(($class2) ? $class2->price : 0);
                $valueTrain['class3price']=(($class3) ? $class2->price : 0);
                $valueTrain['booked']=$bookeddata;
                $valueTrain['schedules']=$scheduleData;
                $data[] = $valueTrain;
            }
        }
        return APIStructure::getResponse($data, [], 200);
    }

    public function getAvailable(Request $request)
    {
        $date = $request->date;
        $startLocation = $request->start;
        $endLocation = $request->end;

        error_log($date);


        $data = [];

        foreach (Train::where('status', 1)->get() as $keyTrain => $valueTrain) {
            $scheduleData = [];
            $startLocationRec = null;
            // foreach (Schedule::where('train', $valueTrain->id)->where('slot','>',Carbon::now())->whereDate('slot', $date)->orderBy('slot', 'ASC')->get() as $keySchedule => $valueSchedule) {
            foreach (Schedule::where('train', $valueTrain->id)->where('slot','>',Carbon::now()->timezone('Asia/Colombo'))->whereDate('slot', $date)->orderBy('slot', 'ASC')->get() as $keySchedule => $valueSchedule) {
                if ($valueSchedule->location == $startLocation && $startLocationRec == null) {
                    $startLocationRec = $valueSchedule;
                }

                if ($startLocationRec != null && $valueSchedule->location == $endLocation && $startLocationRec->turn == $valueSchedule->turn) {
                    $scheduleData[] = [$startLocationRec, $valueSchedule];
                    $startLocationRec = null;
                }
            }
            if (count($scheduleData) > 0) {
                $data[] = ['train' => $valueTrain, 'schedules' => $scheduleData];
            }
        }

        return $data;
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'alias' => 'required|string',
            'start' => 'required|exists:locations,id',
            'end' => 'required|exists:locations,id|different:start',
            'status' => 'required|in:1,2',
            'perbox' => 'required|numeric',
            'windowed' => 'required|numeric',
            'nonwindowed' => 'required|numeric',
            'class1' => 'required|numeric|min:0',
            'class2' => 'required|numeric|min:0',
            'class3' => 'required|numeric|min:0',
            'isnew' => 'required|in:1,2'
        ]);

        if ($request->perbox != ($request->class1 + $request->class2 + $request->class3)) {
            ValidationException::withMessages([
                'perbox' => ['Class seats counts unstable with fulll count of seats'],
            ]);
        }

        if ($request->isnew == 1) {
            Train::create([
                'start' => $request->start,
                'end' => $request->end,
                'alias' => $request->alias,
                'status' => $request->status,
                'seatsperbox' => $request->perbox,
                'windowed' => $request->windowed,
                'nonwindowed' => $request->nonwindowed,
                'firstclass' => $request->class1,
                'secondclass' => $request->class2,
                'thirdclass' => $request->class3
            ]);
        } else {

            $request->validate([
                'id' => 'required|exists:trains,id'
            ]);

            Train::where('id', $request->id)->update([
                'start' => $request->start,
                'end' => $request->end,
                'alias' => $request->alias,
                'status' => $request->status,
                'seatsperbox' => $request->perbox,
                'windowed' => $request->windowed,
                'nonwindowed' => $request->nonwindowed,
                'firstclass' => $request->class1,
                'secondclass' => $request->class2,
                'thirdclass' => $request->class3
            ]);
        }

        return redirect()->back()->with(['code' => 1, 'color' => 'success', 'msg' => 'Train Successfully ' . (($request->isnew == 1) ? 'Registered' : 'Updated')]);
    }

    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trains,id'
        ]);

        return Train::where('id', $request->id)->first();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:trains,id'
        ]);

        Train::where('id', $request->id)->delete();
    }
}
