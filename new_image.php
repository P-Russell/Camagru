<?php include "public/templates/header.php"; ?>
<?php
if (!isset($_SESSION['username']))
    header("Location: index.php");
function get_client_images($client_username)
{
    require "config/database.php";
    try
    {
        $connection = new PDO($dsn, $username, $password, $options);
        $sql = "SELECT * FROM images WHERE username = :username 
        ORDER BY created DESC LIMIT 10";
        $statement = $connection->prepare($sql);
        $statement->execute(['username' => $client_username]);
        $results = $statement->fetchAll();
        $connection = null;
        return ($results);
    }
    catch(PDOException $error)
    {
        echo $sql . "<br>" . $error->getMessage();
    }
}
?>
<div class="side">
<h3>User Previous Images:</h3>
<p>Clicking the images will delete them</p>
<?php
    $images = get_client_images($_SESSION['username']);
    foreach ($images as $image) {
        echo '
        <img src=' . substr($image['image_name'], 2) .' onclick="delete_user_image(this)">
        ';
    }
?>
<script>
    function delete_user_image(item) 
    {
        if (confirm("Are you sure you want to delete " + item.src) == true) {
            var form = document.createElement("form");
            form.setAttribute("method", "post");
            form.setAttribute("action", "delete_existing.php");
            var hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "image");
            hiddenField.setAttribute("value", item.src);
            form.appendChild(hiddenField);
            document.body.appendChild(form);
            form.submit();
        }
    }
</script>

</div>

<h2> Create a new image composite </h2>
<h3>1. Select images from the list below </h3>
<div>
<?php
include "common.php";
$dirname = "public/images/stock/";
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
        navigator.mediaDevices.getUserMedia({ audio: false, video: true })
            .then(function(stream) {
                video = document.querySelector('video');
                video.srcObject = stream;
                webcamStream = stream;
            })
            .catch(function(err) {
                console.log("The following error occured: " + err);
            });
        }
    function makeComp() {
        var done = document.getElementById('notyet');
        if (done != null) {
            done.setAttribute('id', 'yet');
        }
        var canvas = document.getElementById("preview");
        var ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    }
    function postComp(){
        var check = document.getElementById('notyet');
        if (check == null) {
            var stock = document.getElementsByName("selected");
            var quant = stock.length;
            if (quant != 0) {
                canvas = document.getElementById("preview");
                var data = canvas.toDataURL();
                var form = document.createElement("form");
                form.setAttribute("method", "post");
                form.setAttribute("action", "image_to_data.php");
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
<script>
function validateMyForm() {
    var stock = document.getElementsByName("selected");
    var quant = stock.length;
    if (quant != 0) {
        var form = document.getElementById("upload_form")
        for(var i = 1; i < quant + 1; i++)  {
            hiddenField = document.createElement("input");
            hiddenField.setAttribute("type", "hidden");
            hiddenField.setAttribute("name", "stock_image" + i);
            hiddenField.setAttribute("value", stock[i - 1].children[0].src);
            form.appendChild(hiddenField);
        }
        return true;
    }
    else {
        var status = document.getElementById('status2');
        status.innerHTML = "You need to select an image to blend";
        return false;
    }
}
</script>
<h3>Webcam not working / non existent"</h3>
<p id="status2" style = "color: red"><p>
<form action="upload.php" method="post" enctype="multipart/form-data" onsubmit="return validateMyForm();" id="upload_form">
    Select image to upload:
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" value="Upload Image" name="submit">
</form>
<?php include "public/templates/footer.php"; ?>