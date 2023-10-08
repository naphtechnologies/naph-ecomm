<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use Illuminate\Http\Request;

class ListingController extends Controller
{
    public function index()
    {
        $listings = Listing::all();
        // return $products;
        return view('backend.listing.index')->with('listings', $listings);
    }

    public function create()
    {
        return view('backend.listing.create');
    }
}
