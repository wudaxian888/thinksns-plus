<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ImUser;

class IMController extends Controller
{
    public function create(Request $request, int $user_id)
    {
		
        $ImUser = new ImUser();
        $user = $ImUser->usersPost(['uid' => $user_id]);
        dump($user);
        exit;
        if ($user === false) {
            echo $ImUser->getError();
        } else {
            dump($user);
            exit;
        }
    }

    public function delete(Request $request, int $user_id)
    {
        $ImUser = new Imuser();
        $user = $ImUser->deleteUser();
    }
}