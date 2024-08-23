<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index() {
      $events = Event::all();
      return response()->json($events);
    }

    public function eventsWithPhotos(){
      $events = Event::with(['photos' => function($query) {
        $query->where('status', 'approved');
      }])->get();

      return response()->json($events);
    }
     
    public function store(Request $request)
    {
      $request->validate([
          'title' => 'required|string|max:255',
          'date' => 'required|date',
          'time' => 'required',
          'location' => 'required'
      ]);

      $event = Event::create($request->all());

      return response()->json($event, 201);
    }

    public function update(Request $request, $id)
    {
      $request->validate([
          'title' => 'required|string|max:255',
          'date' => 'required|date',
          'time' => 'required',
          'location' => 'required'
      ]);

      $event = Event::findOrFail($id);
      $data = $request->all();
      $data['time'] = date('H:i', strtotime($data['time']));
      $event->update($data);

      return response()->json($event, 200);
    }

    public function destroy($id)
    {
      $event = Event::findOrFail($id);
      $event->delete();

      return response()->json(null, 204);
    }
}
