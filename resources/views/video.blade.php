<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Live Video Broadcasting</title>
</head>
<body>
    <h1>Live Video Broadcast</h1>
    <video id="video-stream" autoplay playsinline controls></video>

    <script src="https://js.pusher.com/7.0/pusher.min.js"></script>
   
    <script src="https://cdn.jsdelivr.net/npm/laravel-echo/dist/echo.iife.js"></script>
    <script>
        // Pusher এবং Laravel Echo ইন্সট্যান্স তৈরি
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: '{{ env("PUSHER_APP_KEY") }}',
            cluster: '{{ env("PUSHER_APP_CLUSTER") }}',
            encrypted: true
        });

        const videoElement = document.getElementById('video-stream');

        async function startVideoStream() {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: true });
                videoElement.srcObject = stream;

                const peer = new RTCPeerConnection({
                    iceServers: [{ urls: 'stun:stun.l.google.com:19302' }]
                });

                stream.getTracks().forEach(track => peer.addTrack(track, stream));

                peer.onicecandidate = (event) => {
                    if (event.candidate) {
                        fetch('/send-signal', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ type: 'candidate', candidate: event.candidate })
                        });
                    }
                };

                peer.onnegotiationneeded = async () => {
                    const offer = await peer.createOffer();
                    await peer.setLocalDescription(offer);
                    fetch('/send-signal', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ type: 'offer', offer: offer })
                    });
                };

                // Laravel Echo চ্যানেল থেকে সিগন্যাল ডেটা গ্রহণ
                Echo.join('video-stream')
                    .listen('VideoBroadcasting', (event) => {
                        const { type, data } = event.signalData;
                        if (type === 'offer') {
                            peer.setRemoteDescription(new RTCSessionDescription(data.offer));
                        } else if (type === 'answer') {
                            peer.setRemoteDescription(new RTCSessionDescription(data.answer));
                        } else if (type === 'candidate') {
                            peer.addIceCandidate(new RTCIceCandidate(data.candidate));
                        }
                    });

            } catch (error) {
                console.error('Error accessing media devices.', error);
            }
        }

        startVideoStream();
    </script>
</body>
</html>
