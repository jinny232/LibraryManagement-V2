<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shelf;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ShelfController extends Controller
{
    /**
     * Store a newly created shelf in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the incoming request data.
        $request->validate([
            'shelf_number' => [
                'required',
                'string',
                'max:20',
            ],
            'row_number' => [
                'required',
                'string',
                'max:20',
            ],
            'sub_col_number' => [
                'required',
                'string',
                'max:20',
            ],
        ]);

        // Create a new shelf with the validated data.
        Shelf::create([
            'shelf_number' => $request->shelf_number,
            'row_number' => $request->row_number,
            'sub_col_number' => $request->sub_col_number,
        ]);

        // Redirect back to the settings page with a success message.
        return redirect()->route('admin.settings.index')->with('shelf_success', 'Shelf added successfully.');
    }

    /**
     * Update the specified shelf in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Shelf  $shelf
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Shelf $shelf)
    {
        $request->validate([
            'shelf_number' => 'required|string|max:255',
            'row_number' => 'required|string|max:255',
            'sub_col_number' => 'required|string|max:255',
        ]);

        $shelf->update([
            'shelf_number' => $request->shelf_number,
            'row_number' => $request->row_number,
            'sub_col_number' => $request->sub_col_number,
        ]);

        return redirect()->route('admin.settings.index')->with('success', 'Shelf updated successfully.');
    }

    /**
     * Remove the specified shelf from storage.
     *
     * @param  \App\Models\Shelf  $shelf
     * @return \Illuminate\Http\Response
     */
    public function destroy(Shelf $shelf)
    {
        $shelf->delete();

        return redirect()->route('admin.settings.index')->with('success', 'Shelf deleted successfully.');
    }
}
