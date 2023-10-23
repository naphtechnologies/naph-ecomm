<?php

namespace App\Http\Controllers;

use App\Models\Inquiry;
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index()
    {
        $inquiries = Inquiry::all();

        return view('backend.inquiry.index', compact('inquiries'));
    }
    public function getInquiriesDetails($id)
    {
        $inquiry = Inquiry::findOrFail($id);
        return view('backend.inquiry.single-inquiry', compact('inquiry'));
    }
}
