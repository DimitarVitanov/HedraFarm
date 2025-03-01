<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
class UserController extends Controller
{
    public function fetchUsers()
    {
        $users = User::whereIn('team_id', [1,2])->get()->map(function($user){
            return [
                'id' => $user->id,
                'name' => $user->name,
                'password' => $user->password,
                'email' => $user->email,
                'team_id' => $user->team_id,
                'is_active' => $user->is_active ? true : false,
                'team' => $user->team->name,
            ];
        });
        return response()->json(['success' => true, 'data' => $users, 'message' => 'Users fetched successfully']);
    }

    public function store(Request $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'team_id' => $request->team_id,
            'is_active' => $request->is_active == 'true' ? true : false,
        ]);

        $user = User::latest()->first();

        return response()->json(['success' => true, 'data' => $user, 'message' => 'User created successfully']);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        if(!$user){
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        if(isset($request->name)){
            $user->name = $request->name;
        }
        if(isset($request->email)){
            $user->email = $request->email;
        }
        if(isset($request->password)){
            $user->password = bcrypt($request->password);
        }
        if(isset($request->team_id)){
            $user->team_id = $request->team_id;
        }
        if(isset($request->is_active)){
            $user->is_active = $request->is_active == 'true' ? true : false;
        }
        $user->save();

        return response()->json(['success' => true, 'data' => $user, 'message' => 'User updated successfully']);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->id);
        if(!$user){
            return response()->json(['success' => false, 'message' => 'User not found']);
        }
        $user->delete();

        return response()->json(['success' => true, 'message' => 'User deleted successfully']);
    }
}
