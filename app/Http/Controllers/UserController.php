<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created users.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
            'phone'     => 'nullable|string',
            'location'  => 'nullable|string',
            'about_me'  => 'nullable|string',
            'role'  => 'required|string',

        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);

    return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified users.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show');
    }

    /**
     * Update the specified users.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name'      => 'sometimes|string|max:255',
            'email'     => 'sometimes|email|unique:users,email,' . $id,
            'password'  => 'sometimes|min:6',
            'phone'     => 'nullable|string',
            'location'  => 'nullable|string',
            'about_me'  => 'nullable|string',
            'role'  => 'required|string',

        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

    return redirect()->route('users.index')->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified users.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully']);
    }
}
