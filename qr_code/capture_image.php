<?php
include "database.php";
session_start();
$qr_code = $_GET['qr_code'];
$eid=$_GET['emp_id'];
if($eid==" "){
  
  header("Location:qr_login.php");
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Camera Capture and Upload</title>
  <link rel="stylesheet" href="./admin/assets/css/bootstrap.min.css">
  <style>
    body {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      /*height: 100vh;*/
      margin: 0px;
      background-color: #f0f0f0;
      font-family: Arial, sans-serif;
    }

    #video-container {
      width: 100%;
      max-width: 700px;
      height: auto;
    }

    video {
      width: 100%;
      height: auto;
      /* Maintain aspect ratio */
      border: 2px solid #333;
      border-radius: 10px;
    }

    #capture,
    #retake,
    #back {
      margin: 30px;
      font-weight: bold;
      font-size: 22px;
      width: 300%;
      max-width: 400px;
      border-radius: 10px;
      text-align: center;
      color: white;
      /*background: linear-gradient(45deg, #6a11cb, #2575fc);*/
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);

    }
    #upload{
      margin: 30px;
      font-weight: bold;
      font-size: 22px;
      width: 300%;
      max-width: 400px;
      border-radius: 10px;
      text-align: center;
      color: white;
      background-color:#28a745;
      border: none;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    button {
      background-color: #24a0ed;
    }
    

    #image-container {
      display: none;
      text-align: center;
      width: 100%;
    }

    img {
      width: 100%;
      max-width: 700px;
      /* Adjust max width as needed */
      border: 2px solid #333;
      border-radius: 10px;
    }

    button {
      height: 100px;
      width: 300%;

    }
  </style>
</head>
<?php
$reg_id = $_GET['studentId'];

// echo $new;
?>

<body>
  <img src='../images/EZWITNESS.png' style="width:200px;height:100px;margin:0px;margin-top:10px">
  <h1>Capture Image</h1>
  <div id="video-container">

    <video id="video" autoplay></video>
  </div>

  <div id="textmsg">
    <center>
      
      <h3>Ensure your Face is positioned in the center of the screen and proper lightning condition required</h3>
     
    </center>
  </div>
  <button id="capture">Capture</button>
  <!--<button id="back" onclick='back()'>Back</button>-->
  <div id="image-container">
    <img id="capturedImage">
    <button id="retake">Retake</button>
    <button id="upload">Upload</button>
    <form id="uploadForm" action="upload_file.php" method="post" enctype="multipart/form-data">
      <input type="hidden" name="imageData" id="imageData">
      <input type="hidden" name="emp_id" id="emp_id" value=<?php echo $eid; ?>>
    </form>
  </div>
  <script>
    //To access the camera of device
    const video = document.getElementById('video');
    navigator.mediaDevices.getUserMedia({
        video: true
      })
      .then(stream => {
        video.srcObject = stream;
      })
      .catch(error => {
        console.error('Error accessing camera:', error);
      });
    /////////////////

    //This script capture image when capture button is pressed and display using image container
    const captureButton = document.getElementById('capture');//To capture the images
    const imageContainer = document.getElementById('image-container');//To create container to store captured images
    const capturedImage = document.getElementById('capturedImage');
    const textmsg = document.getElementById('textmsg');//To show text messages
    captureButton.addEventListener('click', () => {
      const canvas = document.createElement('canvas');
      canvas.width = video.videoWidth;
      canvas.height = video.videoHeight;
      canvas.getContext('2d').drawImage(video, 0, 0, canvas.width, canvas.height);

      capturedImage.src = canvas.toDataURL('image/jpeg');
      video.style.display = 'none';
      captureButton.style.display = 'none';
      imageContainer.style.display = 'block';
      textmsg.style.display = 'none';

    });
    ///
    const retakeButton = document.getElementById('retake');
    retakeButton.addEventListener('click', () => {
      imageContainer.style.display = 'none';
      video.style.display = 'block';
      captureButton.style.display = 'block';
    });
    const uploadButton = document.getElementById('upload');
    uploadButton.addEventListener('click', () => {
      const imageData = capturedImage.src;
      const imageDataInput = document.getElementById('imageData'); // Ensure imageDataInput is defined
      imageDataInput.value = imageData;
      uploadForm.submit();
    });
  </script>
</body>

</html>