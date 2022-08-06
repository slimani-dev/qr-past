<?php

namespace App\Http\Controllers;

use App\Events\PastUpdated;
use App\Models\Past;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PastController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        $past = Past::create();

        return redirect()->route('pasts.show', $past);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Past $past
     * @return Response
     */
    public function show(Past $past): Response
    {
        $url = route('pasts.show', $past);
        return Inertia::render('Past', compact('past', 'url'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Past $past
     * @return Response
     */
    public function update(Request $request, Past $past): Response
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $past->content = $request->get('content');
        $past->save();

        event(new PastUpdated($past));
        $url = route('pasts.show', $past);
        return Inertia::render('Past', compact('past', 'url'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
