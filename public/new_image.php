<?php include "templates/header.php"; ?>
<div class="side">
<?php

?>
</div>
<?php
include "../common.php";
session_start();
if (!empty($_SESSION['username']))
{
	?>
	<script>
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

<h2> Create a new image composite </h2>
<h3>1. Select images from the list below </h3>
<div>
<?php
$dirname = "images/stock/";
$images = glob($dirname."*.png");

foreach($images as $image) {

    echo '
    <div class="stock_gallery" onclick="selected(this)">
        <img src=' . $image . ' width="160" height="121">
    </div>
    ';
}

?>
</div>

<script>
    function selected(item) {
        if (item.getAttribute('name') == 'selected') {
            item.style = "";
            item.setAttribute("name", "");
        }
        else {
            item.style = "border: 2px solid green";
            item.setAttribute("name", "selected");
        }
    }
</script>

<h3>2. Take a new image with your webcam</h3>
<p id="status" style = "color: red"><p>
<p>
    <button onclick="startWebcam()">Start webcam</button>
    <button onclick="makeComp()">Make a composition for preview</button>
    <button id="notyet" onclick="postComp()">Save it?</button>
</p>
<video onclick="image(this);" width=640 height=484 id="video" controls autoplay></video>
<p>Preview:</p>
<canvas  id="preview" width="640" height="484"></canvas>
<script>
    navigator.getUserMedia = ( navigator.getUserMedia ||
            navigator.webkitGetUserMedia ||
            navigator.mozGetUserMedia ||
            navigator.msGetUserMedia);
    
    function startWebcam() {
        if (navigator.getUserMedia) {
            navigator.getUserMedia ({video: true,audio: false},
                            function(localMediaStream) {
                                video = document.querySelector('video');
                                video.src = window.URL.createObjectURL(localMediaStream);
                                webcamStream = localMediaStream;
                                },
                            function(err) {
                                console.log("The following error occured: " + err);
                                });
            } 
            else {
                console.log("getUserMedia not supported");
            }
        }
    function makeComp() {
        var done = document.getElementById('notyet');
        if (done != null) {
            done.setAttribute('id', 'yet');
        }
        var stock = document.getElementsByName("selected");
        var num = stock.length;
        canvas = document.getElementById("preview");
        ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
/*        var i;
        for (i = 0; i < num; i++) {
            var image = new Image();
            image.src = stock[i].children[0].src;
            ctx.drawImage(image, 0, 0, canvas.width, canvas.height)
        }*/
    }
    function postComp(){
        var check = document.getElementById('notyet');
        if (check == null) {
            var stock = document.getElementsByName("selected");
            var quant = stock.length
            if (quant != 0) {
                canvas = document.getElementById("preview");
                var data = canvas.toDataURL();
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "../image_to_data.php");
                var hiddenField = document.createElement("input");
                hiddenField.setAttribute("type", "hidden");
                hiddenField.setAttribute("name", "image");
                hiddenField.setAttribute("value", data);
                form.appendChild(hiddenField);
                for(var i = 1; i < quant + 1; i++)  {
                    hiddenField = document.createElement("input");
                    hiddenField.setAttribute("type", "hidden");
                    hiddenField.setAttribute("name", "stock_image" + i);
                    hiddenField.setAttribute("value", stock[i - 1].children[0].src);
                    form.appendChild(hiddenField);
                }
                document.body.appendChild(form);
                form.submit();
            }
            else {
                var status = document.getElementById('status');
                status.innerHTML = "You need to select an image to blend";
            }
        }
        else {
            var status = document.getElementById('status');
            status.innerHTML = "You need to take a photo first";
        }
    }
</script>

<?php include "templates/footer.php"; ?>