<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Category;
use App\Models\Shelf;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        $categories = Category::withCount('books')->paginate(10);

        $shelves = Shelf::withCount('books')->orderBy('shelf_number')->orderBy('row_number')->orderBy('sub_col_number')->paginate(10);

        return view('admin.settings.index', compact('settings', 'categories', 'shelves'));
    }

    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully.');
    }
}
