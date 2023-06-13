<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ticket;
use App\Models\Penumpang;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function edit($id)
    {
        // Retrieve the ticket by its ID
        $ticket = Ticket::find($id);
    
        // Pass the ticket to the view for editing
        return view('ticket', compact('ticket'));
    }
    
    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'nama_penumpang' => 'required|array',
            'nama_penumpang.*' => 'required',
            'kategori' => 'required',
            'tujuan_awal' => 'required',
            'tujuan' => 'required',
            'maskapai' => 'required',
            'jumlah_penumpang' => 'required|numeric|min:1',
            'tanggal_pergi' => 'required|date',
            'tanggal_pulang' => 'required|date',
            'kelas_penerbangan' => 'required',
            'metode_pembayaran' => 'required',
        ]);

        // Get the authenticated user's ID
        $userId = Auth::id();

// Store the ticket data
$ticket = new Ticket();
$ticket->kategori_id = $request->kategori;
$ticket->tujuan_awal_id = $request->tujuan_awal;
$ticket->tujuan_id = $request->tujuan;
$ticket->maskapai_id = $request->maskapai;
$ticket->kelas_id = $request->kelas_penerbangan;
$ticket->metode_pembayaran_id = $request->metode_pembayaran;
$ticket->user_id = $userId;
$ticket->save();

// Store the passenger details
foreach ($request->nama_penumpang as $key => $nama_penumpang) {
    $penumpang = new Penumpang();
    $penumpang->penumpang_name = $nama_penumpang;
    $penumpang->jumlah_penumpang = $request->jumlah_penumpang;
    $penumpang->tanggal_pergi = $request->tanggal_pergi;
    $penumpang->tanggal_pulang = $request->tanggal_pulang;
    $penumpang->save();

    // Associate the passenger with the ticket
    $ticket->penumpangs()->attach($penumpang->id);
}

// Get the latest passenger ID
$latestPenumpangId = Penumpang::latest('penumpang_id')->first()->penumpang_id;

// Update the penumpang_id in tbl_ticket
$ticket->update(['penumpang_id' => $latestPenumpangId]);

        return redirect()->back()->with('success', 'Form submitted successfully!');
    }

    public function showTicket()
    {
        // Fetch the ticket from the database
        $ticket = Ticket::first();

        // Pass the ticket data to the view
        return view('ticket', compact('ticket'));
    }

    public function destroy($id)
{
    $ticket = Ticket::find($id);
    $ticket->delete();

    return redirect()->back()->with('success', 'Ticket deleted successfully.');
}

}

