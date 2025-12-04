<?php
include ("database.php");
echo $emp_id=$_GET['empid'];
echo $emp_name=$_GET['fn'];
//if(isset($_SESSION['emp_name'])){
//echo $_SESSION['emp_name']=$_POST['fn'];
//echo $_SESSION['emp_id']=$_POST['empid'];
//}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Scan Label</title>
  <style>
    
    body {
      margin: 0;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      background-color: #f0f0f0;
      overflow: hidden;
    }

    video, canvas{
      max-width: 100%;
      height: auto;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 10px 10px;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 20px;
      margin: 4px 2px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      width: 80%;
    }

    button:disabled {
      background-color: #a0a0a0;
      cursor: default;
    }
h5{
    margin: 0;
    padding: 0;
    font-size: 20px;
    margin-bottom: 20px;
}

    #uploadButton {
      margin-top: 10px;
    }

    #controls {
      margin-top: 10px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    #videoContainer {
      margin: 0;
      padding: 0;
      width: 90%;
      max-width: 800px;
      text-align: center;
      /* margin-bottom: 50px; */
    }

    #myVideo {
      width: 100%;
      height: auto;
      max-width: 800px;
      cursor: pointer;
      transition: border 0.3s ease;
    }

    #capturedImage {
      width: 100%;
      height: auto;
      max-width: 100%;
      display:flex;
      flex-direction: column;
      align-items: center;
    }

    #capturedImageContainer {
      display: none;
      width: 80%;
      height: auto;
      max-width: 800px;
    }
    #retakeButton{
      margin-left: 45%;
      margin-top: 10px;
      width:85px;
      height: 35px;
      padding: 2px 2px 2px 2px;
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
      border: 2px  black;
      
    }
    #uploadButton{
      margin-left: 45%;
      padding: 10px 10px 10px 10px;
      width:85px;
      height: 35px;
      padding: 2px 2px 2px 2px;
      box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2), 0 6px 20px 0 rgba(0,0,0,0.19);
    }
  img{
    margin-left: 25%;
  }
  .backbutton{
    background-color: #4CAF50;
    width: 100%;
    height: 15%;
    border-radius: 5px;
    margin-bottom: 200px;
    border: none;
    font-size: 20px;
    color: #f0f0f0;
  }
  </style>
</head>
<body>
   
    <!-- <h5 >Scan Label</h5> -->
    <?php //echo "Label QR : ". $qr_code; ?>
    <div id="videoContainer">
        <video id="myVideo" autoplay muted></video>
    </div>

    <div id="controls">
      <!-- fileupload -->
        <form id="imageForm" action="upload.php?id=<?php echo $emp_id;?>&name=<?php echo $emp_name;?>" method="post">
        <div id="capturedImageContainer">
                <img id="capturedImage" alt="Captured Image">
                <input type="file" accept="image/*" id="uploadInput" style="display: none;">
              
            </div>
            <button id="captureButton" style="width:100%;margin-bottom: 10px;margin-top:0px;" disabled>Capture</button>
            <!-- <button id="captureButto" style="width:100%;margin-bottom: 10px;margin-top:0px; background-color:green;" disabled>Back</button> -->
           <a href='onboard.php'><button type='button' id="captureButto" style="width:100%">Back</button></a>
            <button type="button" id="retakeButton" style="display: none;" onclick="retake_button()">Retake</button>
            <input type="hidden" name="capturedImageData" id="capturedImageData">
            <input type="file" accept="image/*" id="uploadInput" style="display: none;">
            <button type="submit" id="uploadButton" style="display: none; margin-bottom: 200px; margin-top: 10px;">Upload</button>
            
        </form>
    </div>
  <script>
    const captureButton = document.getElementById('captureButton');
    const myVideo = document.getElementById('myVideo');
    const capturedImageContainer = document.getElementById('capturedImageContainer');
    const capturedImage = document.getElementById('capturedImage');
    const uploadButton = document.getElementById('uploadButton');
    const retakeButton = document.getElementById('retakeButton');

    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
      .then(stream => {
        myVideo.srcObject = stream;
        myVideo.play();
        captureButton.disabled = false;
      })
      .catch(error => {
        console.error("Error accessing camera:", error);
      });

    myVideo.addEventListener('click', () => {
      focusVideo();
    });

    captureButton.addEventListener('click', () => {
      focusVideo();
      captureImage();
    });

    function focusVideo() {
      myVideo.style.border = '5px solid #4285f4'; 
      setTimeout(() => {
        myVideo.style.border = 'none'; 
      }, 1000);
    }

  function captureImage() {
  myVideo.pause();
  myVideo.srcObject.getTracks().forEach(track => track.stop());
  myVideo.style.display = 'none';
  capturedImageContainer.style.display = 'flex';

  const canvas = document.createElement('canvas');
  const context = canvas.getContext('2d');
  canvas.width = myVideo.videoWidth;
  canvas.height = myVideo.videoHeight;
  context.drawImage(myVideo, 0, 0);
  const dataURL = canvas.toDataURL('image/jpeg');

  // Set the captured image data in the hidden input field
  document.getElementById('capturedImageData').value = dataURL;

  capturedImage.src = dataURL;
  capturedImage.style.width = '80%';
  capturedImage.style.height = '60%';
  captureButton.disabled = true;
  captureButton.style.display = 'none';
  captureButto.disabled = true;
  captureButto.style.display = 'none';
  uploadButton.style.display = 'block';
  retakeButton.style.display = 'block';
}

    uploadInput.addEventListener('change', (event) => {
      const file = event.target.files[0];
      const reader = new FileReader();

      reader.onload = (e) => {
        capturedImage.src = e.target.result;
        capturedImage.style.width = '100%';
        capturedImage.style.height = 'auto';
      };
      reader.readAsDataURL(file);
      
    });
 

    function retake_button() {
      location.reload();
    }

  </script>
</body>
</html>
