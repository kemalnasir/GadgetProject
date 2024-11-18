<?php
session_start();
include('includes/config.php');
include('includes/checklogin.php');
check_login();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gadget Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.11.3/css/dataTables.bootstrap.min.css">
    <style>
        body {
            background-image: url('photo/okay.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            font-family: 'Lato', sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 10px;
            margin-top: 20px;
        }
        h2 {
            font-size: 2.5rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            color: #333;
        }
        .form-label {
            font-size: 1.2rem;
            font-weight: bold;
            color: #555;
        }
        .form-control {
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
        }
        .btn-primary, .btn-danger, .btn-feedback {
            border-radius: 20px;
            padding: 10px 20px;
            font-size: 1rem;
            font-weight: bold;
            transition: opacity 0.3s ease;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }
        .btn-feedback {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-primary:hover, .btn-danger:hover, .btn-feedback:hover {
            opacity: 0.8;
        }
        table {
            width: 100%;
            margin-top: 30px;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 0 5px rgba(0,0,0,0.1);
            border-radius: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
            font-size: 1.2rem;
            font-weight: bold;
            color: #333;
        }
        td {
            font-size: 1rem;
            color: #555;
        }
        img {
            max-width: 100%;
            height: auto;
            border-radius: 10px;
        }
        video, audio {
            max-width: 100%;
            border-radius: 10px;
        }
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }
            h2 {
                font-size: 2rem;
                margin-bottom: 20px;
            }
            .form-control, .btn-primary, .btn-danger, .btn-feedback {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
            th, td {
                padding: 10px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <?php include('includes/header.php'); ?>

    <div class="container">
        <h2 class="mb-4">Gadget Details</h2>

        <div class="row mb-3">
            <div class="col-md-6 offset-md-3">
                <div class="mb-3">
                    <label for="gadgetSearchInput" class="form-label">Search Gadget by Name</label>
                    <input type="text" class="form-control" id="gadgetSearchInput" placeholder="Enter gadget name">
                </div>
                <div>
                    <button id="startVoiceBtn" class="btn btn-primary mb-3">Start Voice Search</button>
                    <button id="stopVoiceBtn" class="btn btn-danger mb-3 ms-2" disabled>Stop Voice Search</button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <table id="gadgetDetailsTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Gadget Name</th>
                            <th>Category</th>
                            <th>Image</th>
                            <th>Video</th>
                            <th>Audio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Gadget details will be dynamically added here -->
                    </tbody>
                </table>
            </div>
        </div>
        <div class="text-center mt-4">
            <a href="feedback.php" class="btn btn-feedback" style="display: none;">Feedback</a>
        </div>
    </div>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS for Voice Recognition -->
    <script>
        let recognition = null;
        let isListening = false;

        function startRecognition() {
            recognition = new window.webkitSpeechRecognition();
            recognition.lang = 'en-US';

            recognition.onresult = function(event) {
                let voiceInput = event.results[0][0].transcript.trim();
                document.getElementById('gadgetSearchInput').value = voiceInput;
                fetchGadgetDetails(voiceInput);
            }

            recognition.start();
            isListening = true;

            document.getElementById('startVoiceBtn').disabled = true;
            document.getElementById('stopVoiceBtn').disabled = false;
        }

        function stopRecognition() {
            if (recognition) {
                recognition.stop();
                recognition = null;
                isListening = false;

                document.getElementById('startVoiceBtn').disabled = false;
                document.getElementById('stopVoiceBtn').disabled = true;
            }
        }

        document.getElementById('startVoiceBtn').addEventListener('click', function() {
            startRecognition();
        });

        document.getElementById('stopVoiceBtn').addEventListener('click', function() {
            stopRecognition();
        });

        function fetchGadgetDetails(gadgetName) {
            $.ajax({
                type: 'POST',
                url: 'fetch_product.php',
                data: {
                    gadget_name: gadgetName
                },
                dataType: 'json',
                success: function(response) {
                    $('#gadgetDetailsTable tbody').empty();

                    if (response.success) {
                        let gadgetDetails = response.gadget_details;
                        if (gadgetDetails.length > 0) {
                            for (let i = 0; i < gadgetDetails.length; i++) {
                                let videoContent = gadgetDetails[i].P_Video ? `<video controls width='320' height='240'><source src="${gadgetDetails[i].P_Video}" type='video/mp4'>Your browser does not support the video tag.</video>` : 'N/A';
                                let audioContent = gadgetDetails[i].P_Audio ? `<audio controls><source src="${gadgetDetails[i].P_Audio}" type='audio/mpeg'>Your browser does not support the audio element.</audio>` : 'N/A';
                                let row = `<tr>
                                            <td>${i + 1}</td>
                                            <td>${gadgetDetails[i].P_Name}</td>
                                            <td>${gadgetDetails[i].C_Name}</td>
                                            <td><img src="${gadgetDetails[i].P_Image}" width="200" height="200"></td>
                                            <td>${videoContent}</td>
                                            <td>${audioContent}</td>
                                          </tr>`;
                                $('#gadgetDetailsTable tbody').append(row);
                            }
                            // Show the feedback button
                            showFeedbackButton();
                        } else {
                            let errorRow = `<tr><td colspan='6'>No gadgets found for '${gadgetName}'</td></tr>`;
                            $('#gadgetDetailsTable tbody').append(errorRow);

                            // Hide the feedback button if no gadgets found
                            hideFeedbackButton();
                        }
                    } else {
                        let errorRow = `<tr><td colspan='6'>${response.error}</td></tr>`;
                        $('#gadgetDetailsTable tbody').append(errorRow);
                        console.error('Failed to fetch gadget details: ' + response.error);

                        // Hide the feedback button on error
                        hideFeedbackButton();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX error: ' + error);
                                        let errorRow = `<tr><td colspan='6'>Failed to fetch gadget details. Please try again later.</td></tr>`;
                    $('#gadgetDetailsTable tbody').append(errorRow);

                    // Hide the feedback button on error
                    hideFeedbackButton();
                }
            });
        }

        function showFeedbackButton() {
            $('.btn-feedback').show(); // Show the feedback button
        }

        function hideFeedbackButton() {
            $('.btn-feedback').hide(); // Hide the feedback button
        }

        // Initially hide the feedback button
        $(document).ready(function() {
            hideFeedbackButton();
        });
    </script>

    <!-- Loading Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.11.3/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.11.3/js/dataTables.bootstrap.min.js"></script>
</body>
</html>
