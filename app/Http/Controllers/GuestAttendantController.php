<?php

namespace App\Http\Controllers;

use App\Models\GuestAttendant;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class GuestAttendantController extends Controller
{
    protected function getRoutePrefix(): string
    {
        return Auth::user()->role === 'client' ? 'my-guest-attendants' : 'guest-attendants';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $title = 'Guest Attendants';
        $query=GuestAttendant::join('guests','guest_attendants.guest_id','=','guests.id')
        ->join('wedding_events','guest_attendants.wedding_event_id','=','wedding_events.id')
        ->join('couples','wedding_events.couple_id','=','couples.id')
        ->select('guest_attendants.*','guests.name as guest_name','couples.bride_name','couples.groom_name');
        if (auth()->user()->isClient()) {
            $query->where('couples.client_id', auth()->user()->client_id);
        }
        $guestAttendants=$query->latest()->paginate(10);
        return view('guest-attendants.index', [
            'guestAttendants' => $guestAttendants,
            'title' => $title,
            'deleteRoute' => $this->getRoutePrefix().'.destroy',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): RedirectResponse
    {
        $guestAttendant = GuestAttendant::findOrFail($id);
        $guestAttendant->delete();

        return redirect()->route($this->getRoutePrefix().'.index')
            ->with('success', 'Guest attendant deleted successfully.');
    }
}