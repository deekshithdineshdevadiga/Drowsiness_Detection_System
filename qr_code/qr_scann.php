<?php
include "database.php";
session_start();
$studentId = $_SESSION["eid"];
$eid=$studentId;

date_default_timezone_set("Asia/Calcutta");   //India time (GMT+5:30)
$sendate=date('H:i:s');

if($studentId==''){
    header("location:login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mobile QR Code Scanner</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.4/html5-qrcode.min.js" integrity="sha512-k/KAe4Yff9EUdYI5/IAHlwUswqeipP+Cp5qnrsUjTPCgl51La2/JhyyjNciztD7mWNKLSXci48m7cctATKfLlQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <style>
        html,
        body {
            height: 100%;
            margin-top: 40px;
            margin-bottom: 50px;
            padding: 0;
            overflow: auto;
            /* Allow scrolling */
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            /* Align content to the top to allow scrolling */
        }

        h1 {
            margin: 10px 0px;
            font-size: 2rem;
            text-align: center;
            color: #333;
        }

        #reader {
            width: 90%;
            max-width: 320px;
            height: 280px;
            border: 2px solid #50C878;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            margin: 40px 0;
        }

        #result {
            font-size: 1rem;
            color: #333;
            background: #e9ecef;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            width: 90%;
            text-align: center;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 10px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .hidden {
            display: none;
        }
      img{
        height:50px;
        margin-top:20px;
        
      }
    </style>
</head>

<body>
  <img src="../images/EZWITNESS.png" >
    <h4>Scan QR Code</h4>
    <div id="reader"></div>
    <div id="result" class="hidden">Scanned QR Code: <span id="code"></span></div>
    <button id="rescan" class="hidden">Rescan</button>

    <script>
        const reader = document.getElementById("reader");
        const resultDiv = document.getElementById("result");
        const codeSpan = document.getElementById("code");
        const rescanBtn = document.getElementById("rescan");

        // Initialize QR Code scanner
        const html5QrCode = new Html5Qrcode("reader");

        function startScanning() {
            html5QrCode
                .start({
                        facingMode: "environment"
                    }, // Use rear camera
                    {
                        fps: 10, // Frames per second
                        qrbox: {
                            width: 320,
                            height: 320
                        }, // Scanning area
                    },
                    onScanSuccess,
                    onScanError
                )
                .catch((err) => {
                    alert("Camera initialization failed. Please check permissions.");
                    console.error("Camera error:", err);
                });
        }

        function onScanSuccess(decodedText) {
            // Stop scanner
                  console.log("Scanned QR Code:", decodedText);
                  const eid= "<?php echo $eid ?>";
                  window.location.href = `verify_qr_code.php?qr_code=${encodeURIComponent(decodedText)}&emp_id=${encodeURIComponent(eid)}`;      
        }

        function onScanError(error) {
            console.warn("Scanning error:", error);
        }

        rescanBtn.addEventListener("click", () => {
            resultDiv.classList.add("hidden");
            rescanBtn.classList.add("hidden");
            reader.classList.remove("hidden");
            startScanning();
        });

        // Start scanning on page load
        startScanning();
    </script>
</body>

</html>