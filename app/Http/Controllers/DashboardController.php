<?php

namespace App\Http\Controllers;

use App\Models\IncomingLetter;
use App\Models\OutgoingLetter;
use App\Models\OutgoingLetterDisposition;
use App\Models\User;
use App\Models\Village;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $data = [
            'total_users' => User::count(),
            'total_villages' => Village::count(),
            'total_incoming_letters' => IncomingLetter::count(),
            'total_outgoing_letters' => OutgoingLetter::count(),
        ];
        
        // Get unread dispositions for the current user
        $data['unread_dispositions'] = OutgoingLetterDisposition::with(['outgoingLetter', 'fromUser'])
            ->where('to_user_id', $user->id)
            ->whereNull('read_at')
            ->latest()
            ->take(5)
            ->get();
        
        // Get total count of unread dispositions
        $data['unread_dispositions_count'] = OutgoingLetterDisposition::where('to_user_id', $user->id)
            ->whereNull('read_at')
            ->count();
        
        if ($user->role->name === 'Pembuat Surat') {
            $data['draft_letters'] = IncomingLetter::where('created_by', $user->id)
                ->whereIn('status', [OutgoingLetter::STATUS_DRAFT])
                ->count();
            $data['rejected_letters'] = IncomingLetter::whereIn('status', [
                    OutgoingLetter::STATUS_REJECTED_SEKDES,
                    OutgoingLetter::STATUS_REJECTED_KADES,
                    'rejected'
                ])
                ->where('created_by', $user->id)
                ->count();
        } elseif ($user->role->name === 'Memparaf Surat') {
            $data['pending_approval'] = IncomingLetter::where('status', OutgoingLetter::STATUS_PENDING_SEKDES)
                ->count();
        } elseif ($user->role->name === 'Menandatangani Surat') {
            $data['pending_approval'] = IncomingLetter::where('status', OutgoingLetter::STATUS_PENDING_KADES)
                ->count();
        } elseif ($user->role->name === 'Bagian Umum') {
            $data['pending_numbering'] = IncomingLetter::where('status', OutgoingLetter::STATUS_PENDING_PROCESS)
                ->count();
        }
        
        return view('dashboard', compact('data'));
    }
}
