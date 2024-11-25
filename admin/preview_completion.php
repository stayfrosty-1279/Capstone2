<?php
// Set content type to HTML
header('Content-Type: text/html');

// Get event name from URL parameter
$event_name = isset($_GET['event_name']) ? $_GET['event_name'] : 'Sample Event';

// Sample participant name for preview
$participant_name = "SAMPLE PARTICIPANT";
$current_date = date('F j, Y');

// Certificate HTML template - similar to participation but with "Completion" title
$html = '
<html>
<body style="font-family: Arial, sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%); margin: 0; padding: 5vh;">
    <center>
        <div style="background-color: #ffffff; max-width: 765px; margin: 18px auto; padding: 15px; border-radius: 15px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); position: relative; overflow: hidden; border: 3px solid #3d83cc; border-top-width: 10px;">
            <div style="position: relative; z-index: 1;">
                <h2 style="font-size: 34px; color: #2C3E50; margin-bottom: 18px; text-align: center; font-family: Georgia, serif; letter-spacing: 2px;">
                    <span style="display: block; font-size: 22px; color: #3d83cc; margin-bottom: 9px; font-weight: normal;">NEUCONNECT</span>
                    Certificate of Completion
                </h2> 
                <div style="width: 90px; height: 3px; background-color: #3d83cc; margin: 18px auto;"></div>
                <p style="font-size: 18px; color: #3d83cc; margin: 27px 0; text-align: center;">
                    This is to certify that
                </p>
                <h1 style="font-size: 38px; color: #2C3E50; font-weight: bold; margin: 27px 0; text-transform: uppercase; text-align: center; font-family: Georgia, serif;">
                    <span style="display: block; border-bottom: 2px solid #3d83cc; padding-bottom: 13px; margin: 0 auto; max-width: 80%;">
                        ' . $participant_name . '
                    </span>
                </h1>
                <p style="font-size: 18px; color: #5D6D7E; margin: 27px 0; text-align: center; line-height: 1.6;">
                    has successfully completed the<br>
                    <span style="font-size: 22px; color: #3d83cc; font-weight: bold;">' . $event_name . '</span>
                </p>
                <p style="font-size: 18px; color: #5D6D7E; margin: 27px 0; text-align: center;">
                    Awarded on <strong>' . $current_date . '</strong>
                </p>
                
                <table style="width: 100%; margin-top: 45px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; text-align: center; padding: 0 20px;">
                            <div style="border-bottom: 2px solid #3d83cc; width: 80%; margin: 13px auto;"></div>
                            <p style="font-size: 16px; color: #2C3E50; margin: 0;">President</p>
                        </td>
                        <td style="width: 50%; text-align: center; padding: 0 20px;">
                            <div style="border-bottom: 2px solid #3d83cc; width: 80%; margin: 13px auto;"></div>
                            <p style="font-size: 16px; color: #2C3E50; margin: 0;">Coordinator</p>
                        </td>
                    </tr>
                </table>
                
                <p style="font-size: 16px; color: #5D6D7E; margin-top: 36px; text-align: center; font-style: italic;">
                    Thank you for being part of our growing community.
                </p>
            </div>
        </div>
    </center>
</body>
</html>';

echo $html;
