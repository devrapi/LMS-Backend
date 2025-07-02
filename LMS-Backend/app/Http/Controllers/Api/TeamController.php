<?php

namespace App\Http\Controllers\Api;

use App\Models\Teams;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

use function Pest\Laravel\json;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teams = Teams::all();

        return response()->json($teams, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'coach' => 'required|string|max:255',
            'brgy' => 'required|string|max:255',
            'division_id' => 'required|exists:divisions,id',
        ]);

        if ($validate->fails()) {
            return response()->json(['error' => $validate->errors()],422);
        }


        try {

            $imageName = null;

            if($request->hasFile('logo')){
                $imagePath = $request->file('logo')->store('logo_images', 'public');

                $imageName = basename($imagePath);
            }

            $team = teams::create([
                'name' => $request->name,
                'logo' => $request->$imageName,
                'coach' => $request->coach,
                'brgy' => $request->brgy,
                'division_id' => $request->division_id,
            ]);


            return response()->json([
                'message' => 'Team Created Successfully',
                'team' => $team
            ], 200);





        } catch (\Exception $e) {
            return response()->json([
                'error' => 'An error occurred while creating the team',
                'team name' => $request->name,
                'division_id' => $request->division_id,
                'logo' => $request->logo,
                'alias' => $request->alias,
                'details' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
