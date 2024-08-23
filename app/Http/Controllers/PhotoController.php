<?php

namespace App\Http\Controllers;

use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PhotoController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'admin') {
            $photos = Photo::with('event')->with('user')->get();
        } else {
            $photos = Photo::with('event')
                ->with('user')
                ->where('user_id', $user->id)
                ->get();
        }
        return response()->json($photos);
    }

    public function store(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'event_id' => 'required|exists:events,id',
        ]);

        $path = $request->file('photo')->store('photos', 'public');

        $photo = new Photo();
        $photo->user_id = $request->user()->id;
        $photo->event_id = $request->event_id;
        $photo->photo_url = $path;
        $photo->status = 'pending';
        $photo->save();

        return response()->json(['message' => 'Photo uploaded successfully!'], 200);
    }

    public function approve($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->status = 'approved';
        $photo->save();

        return response()->json($photo, 200);
    }

    public function reject($id)
    {
        $photo = Photo::findOrFail($id);
        $photo->status = 'rejected';
        $photo->save();

        return response()->json($photo, 200);
    }

    public function destroy($id)
    {
      $event = Photo::findOrFail($id);
      $event->delete();

      return response()->json(null, 204);
    }
}
