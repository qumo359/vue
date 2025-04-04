<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UsersController extends BaseController
{
    public function index()
    {
        $users = \App\Models\User::all();
        return Inertia::render('Admin/Users/Index', compact('users'));
    }

    public function edit(User $user)
    {
        return Inertia::render('Admin/Users/Edit', compact('user'));
    }

    public function update(Request $request, $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user,
        ]);

        $user = User::find($user);
        $user->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->input('password'))]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Данные пользователя успешно обновлены.');
    }

    public function destroy($id)
    {
        $user = new User(['id' => $id]);
        $result = User::destroy($id);
        if ($result) {
            return redirect()->route('admin.users.index')
                ->with(['deleted_id' => $id, 'success' => "Пользователь {$user} успешно удален."]);
        } else {
            return back()->withErrors(['msg' => 'Пользователь не найден.']);
        }
    }
}
