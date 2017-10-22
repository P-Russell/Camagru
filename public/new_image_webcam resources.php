<?php include "templates/header.php"; ?>
<?php
session_start();
if (!empty($_SESSION['username']))
{
	?>
	<script>
	console.log("Hello there");
	var new_li = document.createElement('li');
	new_li.className = 'menu_li';
	new_li.innerHTML = '<a href="log_out.php">Log Out</a>';
	var new_li2 = document.createElement('li');
	new_li2.className = 'menu_li';
	new_li2.innerHTML = '<a href="new_image.php">New Image</a>';
	var menu = document.getElementById('menu');
	menu.appendChild(new_li2);
	menu.appendChild(new_li);
	</script>
    <?php
}
?>
    <body onload="init();">
    <h2>Take a snapshot of the current video stream</h2>
    Click on the Start WebCam button.
    <p>
        <button onclick="startWebcam();">Start WebCam</button>
        <button onclick="stopWebcam();">Stop WebCam</button>
        <button onclick="snapshot();">Take Snapshot</button>
    </p>
    <video onclick="snapshot(this);" width=640 height=484 id="video" controls autoplay></video>
    <p>
        Screenshots : <p>
        <canvas  id="myCanvas" width="640" height="484"></canvas>
    </body>
    <script>
        //--------------------
        // GET USER MEDIA CODE
        //--------------------
        navigator.getUserMedia = ( navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);

        var video;
        var webcamStream;

        function startWebcam() {
            if (navigator.getUserMedia) {
                navigator.getUserMedia (

                    // constraints
                    {
                        video: true,
                        audio: false
                    },

                    // successCallback
                    function(localMediaStream) {
                        video = document.querySelector('video');
                        video.src = window.URL.createObjectURL(localMediaStream);
                        webcamStream = localMediaStream;
                    },

                    // errorCallback
                    function(err) {
                        console.log("The following error occured: " + err);
                    }
                );
            } else {
                console.log("getUserMedia not supported");
            }
        }

        function stopWebcam() {
            webcamStream.stop();
        }
        //---------------------
        // TAKE A SNAPSHOT CODE
        //---------------------
        var canvas, ctx;

        function init() {
            // Get the canvas and obtain a context for
            // drawing in it
            canvas = document.getElementById("myCanvas");
            ctx = canvas.getContext('2d');
        }

        function snapshot() {
            // Draws current image from the video element into the canvas
            ctx.drawImage(video, 0,0, canvas.width, canvas.height);
        }

    </script>
<?php include "templates/footer.php"; ?>