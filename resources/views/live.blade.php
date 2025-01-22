<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Video Broadcasting</title>
    <!-- Video.js লাইব্রেরি -->
    <link href="https://vjs.zencdn.net/7.14.3/video-js.css" rel="stylesheet" />
</head>
<body>
    <h1>Live Video Broadcast</h1>

    <!-- ভিডিও প্লেয়ার -->
    <video
        id="video-stream"
        class="video-js vjs-default-skin"
        controls
        autoplay
        width="100%"
        height="500"
        data-setup='{ "fluid": true }'
    >
        <!-- Media Server থেকে লাইভ HLS স্ট্রিম URL বসান -->
        <source src="http://127.0.0.1:8000/live/stream.m3u8" type="application/x-mpegURL" />
        <p class="vjs-no-js">
            আপনার ব্রাউজার ভিডিও সমর্থন করে না। <a href="https://videojs.com/html5-video-support/" target="_blank">আরও জানুন</a>.
        </p>
    </video>

    <!-- Video.js লাইব্রেরি -->
    <script src="https://vjs.zencdn.net/7.14.3/video.min.js"></script>
</body>
</html>
