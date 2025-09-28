<?php

namespace App\Http\Controllers;

use App\Models\QrCode;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class QrCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $qrCodes = QrCode::with([
            'invitation.guest', 
            'invitation.weddingEvent.couple', 
            'invitation.weddingEvent.location'
        ])->latest()->paginate(10);
        $title = 'QR Codes';
        
        return view('qr_codes.index', [
            'qrCodes' => $qrCodes,
            'title' => $title,
        ]);
    }

    /**
     * Display the invitation card in a modal.
     */
    public function showInvitationCard($id): JsonResponse
    {
        $qrCode = QrCode::with([
            'invitation.guest', 
            'invitation.weddingEvent.couple', 
            'invitation.weddingEvent.location'
        ])->findOrFail($id);
        
        // Return the modal content as HTML
        $html = view('qr_codes.modal_content', compact('qrCode'))->render();
        
        return response()->json(['html' => $html]);
    }
}
