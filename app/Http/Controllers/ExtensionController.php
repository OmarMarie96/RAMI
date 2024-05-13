<?php

namespace App\Http\Controllers;

use App\Models\Cdr;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ExtensionController extends Controller
{
    public function index($slug)
    {
        $users = User::where('slug', $slug)->get();

        if (count($users) == 0)
            return abort(404);

        $numbers = Cdr::whereBetween('time_stamp', [Carbon::now()->startOfDay()->timestamp, Carbon::now()->endOfDay()->timestamp])
            ->groupBy('number')
            ->selectRaw('SUM(duration) as duration, number')
            ->orderBy('duration', 'DESC')
            ->get();

        return view('extension', compact('users', 'slug', 'numbers'));
    }

    public function send($slug, Request $request)
    {
        $user = User::where('slug', $slug)
            ->orderby('extension_count')
            ->first();

        $timeout = 0;
        if ($user) {
            $timeout = random_int($user->time_out_from, $user->time_out_to);
            $prefix = $user->prefix;
            $number = $request->number;
            $command = "asterisk -rx 'channel originate SIP/00@test-calls extension $prefix$number@genarated'";
            echo shell_exec($command);
            $user->update(['extension_count' => $user->extension_count + 1]);

            if ($request->remaining == 0)
                User::where('slug', $slug)->update(['extension_count' => 0]);
        }

        return response(['timeout' => $timeout], 200);
    }

    public function edit(Request $request)
    {
        $user_id = Str::replace($request->type . '_', '', $request->user_id);

        User::where('id', $user_id)->update([$request->type => $request->value]);

        return response(null, 200);
    }
}
