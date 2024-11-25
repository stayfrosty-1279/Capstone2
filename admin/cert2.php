 <html>
<body style="font-family: Arial, sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%); margin: 0; padding: 5vh;">
    <center>
        <!-- Main Certificate Container - with border -->
       <div style="background-color: #ffffff; max-width: 765px; margin: 18px auto; padding: 15px; border-radius: 15px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); position: relative; overflow: hidden; border: 3px solid #3d83cc; border-top-width: 10px;">
            <!-- Decorative Elements -->
     

            <!-- Certificate Content -->
            <div style="position: relative; z-index: 1;">
                <!-- Header -->
                <h2 style="font-size: 34px; color: #2C3E50; margin-bottom: 18px; text-align: center; font-family: Georgia, serif; letter-spacing: 2px;">
                    <span style="display: block; font-size: 22px; color: #3d83cc; margin-bottom: 9px; font-weight: normal;">NEUCONNECT</span>
					
				
                    Certificate of Participation
                </h2>
                <!-- Decorative Line -->
            
				
				<div style="width: 90px; height: 3px; background-color: #3d83cc; margin: 18px auto;"></div>
                <p style="font-size: 18px; color: #3d83cc; margin: 27px 0; text-align: center;">
                    This is to certify that
                </p>
                <!-- Participant Name -->
                <h1 style="font-size: 38px; color: #2C3E50; font-weight: bold; margin: 27px 0; text-transform: uppercase; text-align: center; font-family: Georgia, serif;">
                    <span style="display: block; border-bottom: 2px solid #3d83cc; padding-bottom: 13px; margin: 0 auto; max-width: 80%;">
                        '.$part_name.'
                    </span>
                </h1>
                <p style="font-size: 18px; color: #5D6D7E; margin: 27px 0; text-align: center; line-height: 1.6;">
                    has successfully participated in the<br>
                    <span style="font-size: 22px; color: #3d83cc; font-weight: bold;">'.$event_name.'</span>
                </p>
                <p style="font-size: 18px; color: #5D6D7E; margin: 27px 0; text-align: center;">
                    Awarded on <strong>' . date('F j, Y') . '</strong>
                </p>
                <!-- Signature Section using Table -->
                <table style="width: 100%; margin-top: 45px; border-collapse: collapse;">
                    <tr>
                        <td style="width: 50%; text-align: center; padding: 0 20px;">
                            <img src="https://drive.usercontent.google.com/download?id=1VtfpMHc5ByHx9cRShbQyy3mCO4umOo1A&authuser=1" alt="Authorized Signature 1" style="width: 50px; height: 50px; margin-bottom: 0px;">
                            <div style="border-bottom: 2px solid #3d83cc; width: 80%; margin: 13px auto;"></div>
                            <p style="font-size: 16px; color: #2C3E50; margin: 0;">President</p>
                        </td>
                        <td style="width: 50%; text-align: center; padding: 0 20px;">
                            <img src="'.$_SESSION['rc_signature'].'" alt="Authorized Signature 2" style="width: 50px; height: 50px; margin-bottom: 0px;">
                            <div style="border-bottom: 2px solid #3d83cc; width: 80%; margin: 13px auto;"></div>
                            <p style="font-size: 16px; color: #2C3E50; margin: 0;">Coordinator</p>
                        </td>
                    </tr>
                </table>
                <!-- Footer Message -->
                <p style="font-size: 16px; color: #5D6D7E; margin-top: 36px; text-align: center; font-style: italic;">
                    Thank you for being part of our growing community.
                </p>
            </div>
        </div>
    </center>
</body>
</html>