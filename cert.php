<html>
<body style="font-family: 'Arial', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e9f2 100%); margin: 0; padding: 5vh;">
    <center>
        <!-- Main Certificate Container -->
        <div style="background-color: #ffffff; max-width: 850px; margin: 20px auto; padding: 40px; border-radius: 15px; box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12); position: relative; overflow: hidden;">
            <!-- Decorative Elements -->
            <div style="position: absolute; top: 0; left: 0; width: 100%; height: 10px; background: linear-gradient(90deg, #4A90E2, #357ABD);"></div>
            <div style="position: absolute; top: 25px; left: 20px; transform: rotate(-45deg);">
                <svg width="100" height="100" viewBox="0 0 100 100" style="opacity: 0.1">
                    <path d="M50 0 L100 50 L50 100 L0 50 Z" fill="#4A90E2"/>
                </svg>
            </div>
			 <div style="position: absolute; top: 25px; right: 20px; transform: rotate(-45deg);">
                <svg width="100" height="100" viewBox="0 0 100 100" style="opacity: 0.1">
                    <path d="M50 0 L100 50 L50 100 L0 50 Z" fill="#4A90E2"/>
                </svg>
            </div>
            
            <!-- Certificate Content -->
            <div style="position: relative; z-index: 1;">
                <!-- Header -->
                <h2 style="font-size: 38px; color: #2C3E50; margin-bottom: 20px; text-align: center; font-family: 'Georgia', serif; letter-spacing: 2px;">
                    <span style="display: block; font-size: 24px; color: #4A90E2; margin-bottom: 10px; font-weight: normal;">NEUCONNECT</span>
                    Certificate of Participation
                </h2>

                <!-- Decorative Line -->
                <div style="width: 100px; height: 3px; background: linear-gradient(90deg, #4A90E2, #357ABD); margin: 20px auto;"></div>

                <p style="font-size: 20px; color: #5D6D7E; margin: 30px 0; text-align: center;">
                    This is to certify that
                </p>

                <!-- Participant Name -->
                <h1 style="font-size: 42px; color: #2C3E50; font-weight: bold; margin: 30px 0; text-transform: uppercase; text-align: center; font-family: 'Georgia', serif;">
                    <span style="display: block; border-bottom: 2px solid #4A90E2; padding-bottom: 15px; margin: 0 auto; max-width: 80%;">
                        '.$part_name.'
                    </span>
                </h1>

                <p style="font-size: 20px; color: #5D6D7E; margin: 30px 0; text-align: center; line-height: 1.6;">
                    has successfully participated in the<br>
                    <span style="font-size: 24px; color: #4A90E2; font-weight: bold;">'.$event_name.'</span>
                </p>

                <p style="font-size: 20px; color: #5D6D7E; margin: 30px 0; text-align: center;">
                    Awarded on <strong>' . date('F j, Y') . '</strong>
                </p>

                <!-- Signature Section -->
                <div style="margin-top: 50px; display: flex; justify-content: space-around; gap: 40px;">
                    <!-- Left Signature -->
                    <div style="flex: 1; text-align: center;">
                        <img src="https://drive.google.com/uc?id=1D0jqs5hkm73t4THVXmjpXMMqFW2tADgi" alt="Authorized Signature 1" style="width: 130px; height: 130px; margin-bottom: 10px;">
                        <hr style="border: none; border-bottom: 2px solid #4A90E2; width: 80%; margin: 15px auto;">
                        <p style="font-size: 18px; color: #2C3E50; margin: 0;">School President</p>
                    </div>

                    <!-- Right Signature -->
                    <div style="flex: 1; text-align: center;">
                        <img src="'.$_SESSION['rc_signature'].'" alt="Authorized Signature 2" style="width: 130px; height: 130px; margin-bottom: 10px;">
                        <hr style="border: none; border-bottom: 2px solid #4A90E2; width: 80%; margin: 15px auto;">
                        <p style="font-size: 18px; color: #2C3E50; margin: 0;">Coordinator</p>
                    </div>
                </div>

                <!-- Footer Message -->
                <p style="font-size: 18px; color: #5D6D7E; margin-top: 40px; text-align: center; font-style: italic;">
                    Thank you for being part of our growing community.
                </p>
            </div>
        </div>


    </center>
</body>
</html>