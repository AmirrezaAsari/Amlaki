<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Jobs\User\VerifyUserJob;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function verify(Request $request){
        $validated = $request->validate(['user_id' => 'required']);
        $user = User::find($request->user_id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $result = VerifyUserJob::dispatchSync($user->id);

        return response()->json(['message' => 'User verified successfully', 'data'=> $result], 200);
    }

    public function index() {
        $users = User::latest()->paginate(10);
        return response()->json(['message' => 'Users indexed successfully.','users' => $users], 200);
    }
}
