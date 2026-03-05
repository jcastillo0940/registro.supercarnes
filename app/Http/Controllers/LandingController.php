<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $selectedDate = $request->query('date');

        $events = Event::query()
            ->when($selectedDate, function ($query) use ($selectedDate) {
                $query->where(function ($q) use ($selectedDate) {
                    $q->whereNull('fecha_inicio')
                        ->orWhereDate('fecha_inicio', '<=', $selectedDate);
                })->where(function ($q) use ($selectedDate) {
                    $q->whereNull('fecha_fin')
                        ->orWhereDate('fecha_fin', '>=', $selectedDate);
                });
            })
            ->orderBy('fecha_inicio')
            ->get();

        return view('landing', [
            'initialState' => [
                'selectedDate' => $selectedDate,
                'events' => $events,
            ],
        ]);
    }
}
