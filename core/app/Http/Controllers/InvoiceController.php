<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Generate and download the invoice for a reservation.
     */
    public function download(Reservation $reservation)
    {
        $user = auth()->user();

        // Security: Check if the user owns the reservation or is admin or is the business owner
        $isOwner = $reservation->user_id === $user->id;
        $isAdmin = $user->role === 'admin';
        $isVendor = $user->role === 'business' && $reservation->business_id === $user->ownedBusiness?->id;

        if (! $isOwner && ! $isAdmin && ! $isVendor) {
            abort(403);
        }

        // Create PDF
        $reservation->load(['business', 'user', 'menus']);
        $pdf = Pdf::loadView('pdf.invoice', compact('reservation'));

        return $pdf->download("RezerveEt-Dekont-{$reservation->id}.pdf");
    }

    /**
     * Stream the invoice for preview.
     */
    public function stream(Reservation $reservation)
    {
        $user = auth()->user();

        // Security: Check if the user owns the reservation or is admin or is the business owner
        $isOwner = $reservation->user_id === $user->id;
        $isAdmin = $user->role === 'admin';
        $isVendor = $user->role === 'business' && $reservation->business_id === $user->ownedBusiness?->id;

        if (! $isOwner && ! $isAdmin && ! $isVendor) {
            abort(403);
        }

        $reservation->load(['business', 'user', 'menus']);

        $pdf = Pdf::loadView('pdf.invoice', compact('reservation'));

        return $pdf->stream();
    }
}
