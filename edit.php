<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link href="https://fonts.googleapis.com/css2?family=Sniglet:wght@400;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="edit.css">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
    <title>MIPS</title>
</head>

<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin']!== true) {
    
    echo "<script>alert('You need to log in to view this page.');</script>";
    header('refresh:2; url=sample.php');
    exit;
}
?>

    <!-- Nav -->
    <div  class="nav">
        <div>
            <a href="profile1.php"><i class="fa-solid fa-arrow-left arrow_left"></i></a>
            Edit Profile
        </div>
    </div>
    <!-- Content -->
    <div class="text1">
        Owner's First Name
    </div>
    <div class="box1"><?php echo htmlspecialchars($_SESSION['ownerfname']); ?></div>
    <div class="text2">
        Owner's Last Name
    </div>
    <div class="box2"><?php echo htmlspecialchars($_SESSION['ownerlname']); ?></div>

<!-- Email Address -->
<div class="email_address">
    <div class="text3">
        Email Address
    </div>
    <div class="box3"><?php echo htmlspecialchars($_SESSION['owneremail']); ?></div>
    <a id="activateButton" class="activateButton" >Change Email Address</a>
    <form id="targetElement" class="target"  >
        <div class="input_email" style="margin-top: 10px;"> aly@gmail.com</div>
    
        <div  class="inputs" style="margin-top: 10px;">Input New Email Address</div>
        <input   type="email" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
        <div  class="inputs" style="margin-top: 10px;">Current Password</div>
        <input type="password"style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
        <input type="submit" value="Update"style="margin-top: 10px; background-color:#602147; color:white; font-family:'Sniglet'; height:40px; border: transparent; border-radius: 5px;"  ></input>
    </form>
</div>
<!-- Phone Number -->
<div class="phone_number">
    <div class="text4">
        Phone Number
    </div>
    <div class="box4">
        <?php echo htmlspecialchars($_SESSION['ownernum']); ?>
    </div>
            
        <a id="activateButton1" class="activateButton" class="inputs" >Change Phone Number</a>

        <form id="targetElement1" class="target">
            <div class="input_number"> 09123456789</div>
            <div  class="inputs" >Input New Phone Number</div>
            <input type="tel" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
            <div  class="inputs">Current Password</div>
            <input type="password"style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
            <input type="submit" value="Update" style="margin-top: 10px; background-color:#602147; color:white; font-family:'Sniglet'; height:40px; border: transparent; border-radius: 5px;"></input>
        </form>
</div>
<!-- Password -->
<div class="password"> 
    <div class="text5">
    Password
    </div>
    <div class="box5">********</div>

    <a id="activateButton2" class="activateButton">Change Password</a>

    <form id="targetElement2"class="target"  >
        <div class="inputs"> Input Current Password</div>
        <input type="password"style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
        <div  class="inputs">Input New Password</div>
        <input type="password" style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
        <div  class="inputs">Confirm New Password</div>
        <input type="password"style="margin-top: 5px; background-color: #FFE7E7;width: 400px; height: 40px; border: 1px solid #ccc; border-radius: 5px;">
        <input type="submit" value="Update" style="margin-top: 10px; background-color:#602147; color:white; font-family:'Sniglet'; height:40px; border: transparent; border-radius: 5px;"></input>
    </form>
</div>


    <script>
        function toggleActiveClass(buttonId, targetId) {
            document.getElementById(buttonId).addEventListener("click", function() {
                const item = document.getElementById(targetId);
                const button = document.getElementById(buttonId);
                
                item.classList.toggle("active");
                button.classList.toggle("active"); // Toggle the active class on the button
            });
        }
        
        // Attach event listeners
        toggleActiveClass("activateButton", "targetElement");
        toggleActiveClass("activateButton1", "targetElement1");
        toggleActiveClass("activateButton2", "targetElement2");
        </script>
</body>
</html>