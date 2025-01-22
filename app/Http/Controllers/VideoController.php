<?php

namespace App\Http\Controllers;

use App\Events\VideoBroadcasting;
use Illuminate\Http\Request;

class VideoController extends Controller
{

    public function video(){
        return view('video');
    }
    public function sendSignal(Request $request)
    {
        $signalData = $request->all();
        broadcast(new VideoBroadcasting($signalData));
        return response()->json(['status' => 'Signal sent']);
    }

    public function showStream()
    {
        // আপনার লাইভ স্ট্রিমের URL
        $streamUrl = 'https://your-stream-server.com/live/stream.m3u8'; // এখানে আপনার স্ট্রিম URL বসান
        return view('live', compact('streamUrl'));
    }
}
