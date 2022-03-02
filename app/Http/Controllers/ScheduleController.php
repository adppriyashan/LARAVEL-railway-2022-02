<?php

namespace App\Http\Controllers;

use App\Mail\SheduleChangeMail;
use App\Models\Booking;
use App\Models\Location;
use App\Models\Schedule;
use App\Models\Train;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ScheduleController extends Controller
{
    public function index()
    {

        if(Auth::user()->usertype==2){
            return redirect('/');
        }

        $title = 'Train Schedule Management';
        $locations = Location::get();
        $trains = Train::where('status', 1)->get();
        $schedules = Schedule::where('status', 1)->where('slot', '>', Carbon::now())->with('locationdata')->with('traindata')->get();

        return view('pages.train_schedule', compact(['title', 'locations', 'trains', 'schedules']));
    }

    public function enroll(Request $request)
    {

        $request->validate([
            'train' => 'required|exists:trains,id',
            'location' => 'required|exists:locations,id',
            'slot' => 'required',
            'turn' => 'required|numeric',
            'status' => 'required|in:1,2',
            'isnew' => 'required|in:1,2'
        ]);

        if ($request->isnew == 1) {
            Schedule::create([
                'train' => $request->train,
                'location' => $request->location,
                'turn' => $request->turn,
                'slot' => $request->slot,
                'status' => $request->status
            ]);
        } else {
            $request->validate([
                'id' => 'required|exists:schedules,id'
            ]);
            $data = Schedule::where('id', $request->id)->update([
                'train' => $request->train,
                'turn' => $request->turn,
                'location' => $request->location,
                'slot' => $request->slot,
                'status' => $request->status
            ]);

            $data=Schedule::where('id', $request->id)->first();

            $query1 = Booking::where('turn', $request->turn)->where('status', 1)->with('userdata');

            foreach ($query1->get() as $key => $value) {
                if ($value->start == $request->location) {
                    Mail::to($value['userdata']->email)->send(new SheduleChangeMail($data, 1));
                } else {
                    Mail::to($value['userdata']->email)->send(new SheduleChangeMail($data, 2));
                }
            }
        }
        return redirect()->back()->with(['code' => 1, 'color' => 'success', 'msg' => 'Schedule Successfully ' . (($request->isnew == 1) ? 'Registered' : 'Updated')]);
    }

    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:schedules,id'
        ]);

        $data = Schedule::where('id', $request->id)->first();

        $data['slot'] = Carbon::parse($data->slot)->format('Y-m-d\TH:i');

        return $data;
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:schedules,id'
        ]);

        Schedule::where('id', $request->id)->delete();
    }
}
