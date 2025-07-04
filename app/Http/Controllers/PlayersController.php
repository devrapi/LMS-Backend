<?php

namespace App\Http\Controllers;

use App\Models\Players;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlayersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $players = Players::all();

        return response()->json($players, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'jersey_number' => 'required|integer|min:0',
            'position' => 'required|string|max:255',
            'height' => 'required|integer|min:0',
            'age' => 'required|integer|min:0',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()],422);
        }

        try {
            $imageName = null;

            if($request->hasFile('logo')){
                $imagePath = $request->file('logo')->store('profile_images', 'public');

                $imageName = basename($imagePath);
            }

            $players = Players::create([
                'name' => $request->name,
                'team_id' => $request->team_id,
                'jersey_number' => $request->jersey_number,
                'position' => $request->position,
                'height' => $request->height,
                'age' => $request->age,
                'profile' => $request->$imageName,
            ]);

            return response()->json([
                'message' => 'Players Created Successfully',
                'players' => $players
            ], 200);


        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the players',
                'name' => $request->name,
                'team_id' => $request->team_id,
                'jersey_number' => $request->jersey_number,
                'position' => $request->position,
                'height' => $request->height,
                'age' => $request->age,
                'profile' => $request->$imageName,
                'details' => $e->getMessage(),
            ], 500);
        }
        }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $player = Players::find($id);

        if (!$player) {
            return response()->json(['error' => 'Player not Found'],404);
        }

        return response()->json($player, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'team_id' => 'required|exists:teams,id',
            'jersey_number' => 'required|integer|max:255',
            'position' => 'required|string|max:255',
            'height' => 'required|integer|max:255',
            'age' => 'required|integer|max:255',
            'profile' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validate->fails()) {
            return response()->json(['errors' => $validate->errors()],422);
        }

        if ($request->hasFile('proflie')) {
            // Delete the old logo if it exists
            $players = Players::find($id);
            if ($players && $players->profile) {
                Storage::disk('public')->delete($players->proflie);
            }
            $imagePath = $request->file('profile')->store('profile_images', 'public');
            $imageName = basename($imagePath);
        } else {
            $imagePath = null;
        }

        try {
            $players = Players::findorFail($id);
            $players->update($request->only(['name','jersey_number','position','height','age']));

            if ($imagePath) {
                $players->profile = $imageName;
                $players->save();
            }

            return response()->json(['message' => 'players updated successfully', 'players' => $players], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while updating the players', 'details' => $e->getMessage()], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $players = Players::findOrFail($id);
            $players->delete();
            return response()->json(['message' => 'players deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'An error occurred while deleting the players', 'details' => $e->getMessage()], 500);
        }
    }
}
