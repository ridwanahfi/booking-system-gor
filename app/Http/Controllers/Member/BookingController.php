<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\Label;
use App\Models\User;
use App\Models\Member;
use App\Models\TransactionBooking;
use App\Models\TransactionInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    public function index()
    {
        $label = Label::all();
        $member = Member::where('member_id', Auth::user()->id)->first(); // Cari member berdasarkan ID user

        if (isset($member)){ // Validasi apakah member ada atau tidak
            $data = TransactionBooking::where('member_id', $member->id)->paginate(10);

            if (isset($data)){ // Validasi apakah data booking ada atau tidak
                return view('member.booking.index', compact('data', 'label'));
            } else {
                return view('member.booking.index', compact('label'));
            }
        } else {
            return view('member.booking.index', compact('label'));
        }
    }

    public function create()
    {
        $label = Label::all();
        $event = Event::where('status', 'ACTIVE')->get();
        $category = EventCategory::where('status', 'ACTIVE')->get();
        $member = Member::where('status', 'ACTIVE')->where('member_id', Auth::user()->id)->get(); // Cari member berdasarkan ID user
        return view('member.booking.create', compact('event', 'member', 'label', 'category'));
    }

    public function store(Request $request)
    {
        $event = Event::find($request->event_id);
        $member = Member::find($request->member_id);
        $quota = TransactionBooking::where('event_id', $event->id)->sum('event_id');

        if ($quota < $event->count_limit) {
            $booking = new TransactionBooking;
            $booking->code = 'BK' . date('YmdHis');
            $booking->date = date('Y-m-d');
            $booking->note = $request->input('note');
            $booking->category = $request->input('category');
            $booking->event_id = $event->id;
            $booking->event_category_id = $request->input('event_category_id');
            $booking->member_id = $member->id;
            $booking->created_by = strtoupper(Auth::user()->username);
            $booking->save();

            $this->generateInvoice($booking->id);

            return redirect()->route('booking.index')->with('success', 'Booking success, invoice has been generated');
        } else {
            return redirect()->route('booking.index')->with('error', 'Booking failed, quota is full');
        }
    }

    public function generateInvoice($id)
    {
        $booking = TransactionBooking::with('event')->find($id);

        if (!$booking) {
            return; // Tambahkan logika error handling di sini jika diperlukan
        }

        $data = $booking->event; // Mengakses relasi event

        TransactionInvoice::create([
            'code' => 'INV' . date('YmdHis'),
            'method' => 'TRANSFER', // Perhatikan penulisan method yang benar
            'description' => 'Invoice Booking Event ' . $data->title, // Mengakses nama event dari relasi
            'amount' => $data->price, // Mengakses harga event dari relasi
            'fee' => $data->cost, // Mengakses biaya admin dari relasi
            'date' => date('Y-m-d'),
            'category' => 'UNPAID',
            'booking_id' => $booking->id,
            'user_id' => Auth::user()->id,
            'created_by' => strtoupper(Auth::user()->username),
        ]);
    }

    public function showInvoice($id)
    {
        $label = Label::all();
        $booking = TransactionBooking::find($id);
        if (!$booking) {
            return redirect()->route('bucket-2.index')->with('error', 'Booking not found');
        } else {
            $data = TransactionInvoice::with('booking')->where('booking_id', $booking->id)->first();
            return view('member.booking.invoice', compact('data', 'label'));
        }
    }

    public function getEventById($id)
    {
        // Cari event berdasarkan ID
        $event = Event::find($id);

        // Jika event tidak ditemukan, kembalikan respon error
        if (!$event) {
            return response()->json(['error' => 'Event not found'], 404);
        }

        // Hitung kuota berdasarkan jumlah total transaksi
        $quota = TransactionBooking::where('event_id', $event->id)->sum('event_id');

        // Kembalikan data event beserta kuota dalam format JSON
        return response()->json([
            'event' => $event,
            'quota' => $quota,
        ]);
    }

    public function getMemberById($id)
    {
        // Cari member berdasarkan ID
        $member = Member::find($id);

        // Jika member tidak ditemukan, kembalikan respon error
        if (!$member) {
            return response()->json(['error' => 'Member not found'], 404);
        }

        // Kembalikan data member dalam format JSON
        return response()->json([
            'member' => $member,
        ]);
    }
}
