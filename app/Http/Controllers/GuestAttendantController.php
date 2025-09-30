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
        $guestAttendants = GuestAttendant::with(['guest', 'weddingEvent'])->latest()->paginate(10);
        $title = 'Guest Attendants';

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