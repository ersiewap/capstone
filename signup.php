    <?php
        include('dbconnection.php');
        // getting all values from the HTML form
        if($_SERVER["REQUEST_METHOD"] == "POST") {
            $Owneremail = $_POST['owneremail'];
            $OwnerFname = $_POST['ownerfname'];
            $OwnerLname = $_POST['ownerlname'];
            $Ownerpass = $_POST['ownerpass'];
            $Ownernum = $_POST['ownernum'];
            

            $query = mysqli_query($con,"insert into registration_info ( owneremail, ownerfname, ownerlname, ownerpass, ownernum) 
            Value ( '$Owneremail', '$OwnerFname', '$OwnerLname', '$Ownerpass', '$Ownernum')");

            if($query) {
                echo "<script>alert('sucessfully created Account!. Please Login again')</script>";
                echo "<script type= 'text/javascript'>document.location = 'sample.php';</script>";
            }    else {
                echo "<script>alert('there was an error')</script>";
            }
        }
    ?>