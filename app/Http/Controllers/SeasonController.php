<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Season;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SeasonController extends Controller
{
    public function index()
    {


        if (Auth::user()->usertype == 2) {
            return redirect('/');
        }

        $title = 'Season Management';
        $locations = Location::get();
        $seasons = Season::whereIn('status', [1, 2])->get();

        return view('pages.seasons', compact(['title', 'seasons', 'locations']));
    }

    public function print(Request $request)
    {
        return view('prints.season')->with('season', Season::where('authcode', $request->id)->first());
    }

    public function enroll(Request $request)
    {
        $request->validate([
            'nic' => 'nullable',
            'from' => 'required',
            'to' => 'required',
            'location1' => 'required|exists:locations,id',
            'location2' => 'required|exists:locations,id',
            'status' => 'required|in:1,2',
            'isnew' => 'required|in:1,2'
        ]);

        if ($request->isnew == 1) {
            $saved = Season::create([
                'nic' => $request->nic,
                'from' => $request->from,
                'to' => $request->to,
                'location1' => $request->location1,
                'location2' => $request->location2,
                'status' => $request->status
            ]);

            $authcode = 'S-' . $saved->id . Carbon::now()->format('ymdhis');

            $saved->update(['authcode' => $authcode]);
        } else {
            $request->validate([
                'id' => 'required|exists:seasons,id'
            ]);
            Season::where('id', $request->id)->update([
                'nic' => $request->nic,
                'from' => $request->from,
                'to' => $request->to,
                'location1' => $request->location1,
                'location2' => $request->location2,
                'status' => $request->status
            ]);
        }

        return redirect()->back()->with(['code' => 1, 'color' => 'success', 'msg' => 'Season Successfully ' . (($request->isnew == 1) ? 'Registered' : 'Updated')]);
    }

    public function get(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:seasons,id'
        ]);

        return Season::where('id', $request->id)->first();
    }

    public function delete(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:seasons,id'
        ]);

        Season::where('id', $request->id)->delete();
    }
}
