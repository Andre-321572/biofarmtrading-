<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function index()
    {
        return view('contact');
    }

    public function send(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        // In a real app, you would send an email here.
        // For now, we'll log the message and redirect with success.
        Log::info('New Contact Message', $request->all());

        return redirect()->back()->with('success', 'Votre message a été envoyé avec succès ! Nous vous recontacterons bientôt.');
    }
}
