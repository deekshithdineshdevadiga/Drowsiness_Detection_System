<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Image Viewer</title>
    <link rel="stylesheet" href="./../assets/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" href="assets/css/style.css"> -->
    <link rel="stylesheet" href="./../assets/awesome/font-awesome.css">
    
    <link rel="stylesheet" href="./../assets/css/animate.css">
    <link rel="stylesheet" href="./../vendors/datatables/datatables.min.css">
    <script src=".\..\lib\jquery\jquerymin.js"></script>
    <script src=".\..\assets\js\heic2any.min.js"></script>

    <style>
        body{
            user-zoom: none;
            touch-action: none;
            transform: scale(1);
        }
    
        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 75vh; /* Full viewport height */
            margin: 10px; /* Reset margin */
            text-align: center;
        }
        .image-container {
            width: 200px;
            height: 200px;
            border: 2px dashed #ccc;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px auto; /* This centers the container horizontally */
            position: relative;
            cursor: pointer;
            overflow: hidden;
        }
        .image-container::before {
            content: 'Click to choose images';
            color: #ccc;
            font-size: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 1;
        }
        .image-container img {
            width: 100%;
            height: 100%;
            object-fit: contain; /* Ensures the image fits the container */
            display: none;
            position: absolute;
        }
        .image-container img.active {
            display: block;
            z-index: 2;
        }
        .arrow {
            font-size: 24px;
            cursor: pointer;
            color: #aaa;
            margin: 0 10px;
            display: none; /* Hide arrows initially */
        }
        .buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 50px;
        }
        .buttons button {
            padding: 10px 20px;
            margin: 0 10px;
            font-size: 16px;
            cursor: pointer;
        }
        #file-input {
            display: none;
        }
    </style>
    
</head>
<body>
    <div class="wrapper">
    <div>
        <!-- ?php include("left.php");?> -->
        <div class="container">
        <input type="hidden" name="name" id="name" value="<?php echo $_GET['name']; ?>"/>
        <input type="hidden" name="reg_id" id="reg_id" value="<?php echo $_GET['reg_id']; ?>"/>
        <h3>Enrollment</h3>
        <div class="buttons">
            <span class="arrow" id="prevArrow" onclick="prevImage()">&#9664;</span>
            <div class="image-container" id="image-container"></div>
            <span class="arrow" id="nextArrow" onclick="nextImage()">&#9654;</span>
        </div>
        <input type="file" id="file-input" accept="image" multiple />
        <div class="buttons">
            <a href="onboard.php"><button id="bkbtn" class="btn btn-primary" style='background-color:#24a0ed'>Back</button></a>
            <button id="uploadButton" class="btn btn-success">Upload</button>
        </div>
    </div>

    <script>
        const imageContainer = document.getElementById('image-container');
        const fileInput = document.getElementById('file-input');
        const prevArrow = document.getElementById('prevArrow');
        const nextArrow = document.getElementById('nextArrow');
        let images = [];
        let currentIndex = 0;

        imageContainer.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', async function() {
            images = Array.from(this.files);
            const convertedFiles = [];

            for (let file of images) {
                if (file.type === 'image/heic') {
                    try {
                        const convertedBlob = await heic2any({
                            blob: file,
                            toType: 'image/jpeg',
                            quality: 0.8 // Adjust the quality as needed
                        });
                        const convertedFile = new File([convertedBlob], file.name.replace(/\.heic$/i, '.jpg'), {
                            type: 'image/jpeg'
                        });
                        convertedFiles.push(convertedFile);
                    } catch (error) {
                        console.error('Error converting HEIC to JPEG:', error);
                    }
                } else {
                    convertedFiles.push(file);
                }
            }

            images = convertedFiles;
            displayImage();
            toggleArrows();
        });

        function displayImage() {
            if (images.length > 0) {
                imageContainer.innerHTML = ''; // Clear the container
                const imgElements = images.map((file, index) => {
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.alt = `Image ${index + 1}`;
                    img.onload = () => resizeImage(img); // Resize the image after loading
                    if (index === currentIndex) {
                        img.classList.add('active');
                    }
                    return img;
                });

                imgElements.forEach(img => imageContainer.appendChild(img));
            }
        }

        function resizeImage(img) {
            const containerWidth = imageContainer.clientWidth;
            const containerHeight = imageContainer.clientHeight;
            const imgWidth = img.naturalWidth;
            const imgHeight = img.naturalHeight;

            // Calculate aspect ratios
            const containerAspectRatio = containerWidth / containerHeight;
            const imgAspectRatio = imgWidth / imgHeight;

            if (imgAspectRatio > containerAspectRatio) {
                // Image is wider than the container
                img.style.width = '100%';
                img.style.height = 'auto';
            } else {
                // Image is taller than the container
                img.style.width = 'auto';
                img.style.height = '100%';
            }
        }

        function prevImage() {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            updateActiveImage();
        }

        function nextImage() {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            updateActiveImage();
        }

        function updateActiveImage() {
            const imgElements = imageContainer.getElementsByTagName('img');
            Array.from(imgElements).forEach((img, index) => {
                img.classList.toggle('active', index === currentIndex);
            });
        }

        function toggleArrows() {
            const displayValue = images.length > 1 ? 'inline' : 'none';
            prevArrow.style.display = displayValue;
            nextArrow.style.display = displayValue;
        }

        $('#uploadButton').on('click', function() {
            const reg_id = $('#reg_id').val();
            const name = $('#name').val();

            if (images.length > 0) {
                const formData = new FormData();
                for (let file of images) {
                    formData.append('image[]', file);
                }
                formData.append('reg_id', reg_id);
                formData.append('name', name);
                // Log each key-value pair in the FormData object
for (let [key, value] of formData.entries()) {
    console.log(`${key}: ${value}`);
}
                // Show loading indicator
                $('#uploadButton').text('Uploading...').prop('disabled', true);

                $.ajax({
                    url: 'upload.php',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert("Images Uploaded");
                        window.location.href = 'onboard.php';
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        alert("Error uploading images");
                    },
                    complete: function() {
                        $('#uploadButton').text('Upload').prop('disabled', false);
                    }
                });
            } else {
                alert("Please select images.");
            }
        });
    </script>
</body>
</html>