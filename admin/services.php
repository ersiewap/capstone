<?php
session_start();

// Database connection details
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "capstone";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the logged-in admin's salon ID from the session
$salonid = isset($_SESSION['salonid']) ? $_SESSION['salonid'] : '';

?>

<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Services</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="services.css" />
</head>
<body>
    <!-- Mobile Nav -->
    <div class="navbar">
        <a href="admin.php"><i class="fa-solid fa-house"></i><br />Dashboard</a>
        <a href="report.php"><i class="fa-solid fa-newspaper"></i><br />Reports</a>
        <a href="services.php"><i class="fa-solid fa-briefcase"></i><br />Services</a>
        <a href="profile.php"><i class="fa-solid fa-user"></i><br />Admin</a>
    </div>

    <!-- Web Nav -->
    <nav class="sidebar">
        <header>
            <div class="logo">
                <img class="logo_1" src="logo-nav.png" />
            </div>
        </header>
        <div class="menu-bar">
            <div class="menu">
                <ul class="menu-link">
                    <li class="nav-link"><a href="admin.php">Dashboard</a></li>
                    <li class="nav-link"><a href="report.php">Reports</a></li>
                    <li class="nav-link"><a href="services.php">Services</a></li>
                </ul>
                <ul class="menu-sign">
                    <li class="nav-link profile">
                        <a href="#">
                            <i class="fa-solid fa-user"></i>
                            <span class="title nav">Admin</span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="login_staff.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Content -->
    <main class="home">
        <header class="dashboard">Services</header>
        <div class="first">
            <button class="addbtn" onclick="openModal()">Add A Service</button>
        </div>
        <div class="second">
            <div class="appts">List of Services</div>
            <hr />
            <table class="appointments_table">
                <tr>
                    <th>Service ID</th>
                    <th>Service Name</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
                <?php
                $sql = "SELECT * FROM services WHERE salonid = '$salonid'";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr id='service-".$row['serviceid']."'>";
                        echo "<td>".$row['serviceid']."</td>";
                        echo "<td>".$row['servicename']."</td>";
                        echo "<td id='amount-".$row['serviceid']."'>".$row['price']."</td>";
                        echo "<td>";
                        echo "<button class='editbtn' onclick='openEditModal(\"".$row['serviceid']."\", \"".$row['servicename']."\", \"".$row['price']."\")'>";
                        echo "<i class='fa-solid fa-pen-to-square'></i>";
                        echo "</button>";
                        echo "<button class='deletebtn' onclick='deleteService(\"".$row['serviceid']." \")'>";
                        echo "<i class='fa-solid fa-trash'></i>";
                        echo "</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                }
                ?>
            </table>
        </div>

        <!-- The Modal -->
        <div id="addServiceModal" class="modal">
            <div class="modal-content">
                <span class="close" onclick="closeModal()">&times;</span>
                <h2 class="textt">Add A New Service</h2>
                <form id="serviceForm" method="post">
                    <label class="label" for="serviceName">Enter Service Name</label><br>
                    <input class="serviceName" type="text" id="serviceName" name="serviceName"><br>
                    <label class="label" for="amount">Enter Amount</label><br>
                    <input class="serviceamount" type="text" id="amount" name="amount"><br><br>
                    <button type="submit" class="submitbtn">Submit</button>
                </form>
            </div>
        </div>

        <!-- Edit Modal -->
        <div id="editServiceModal" class="modal1">
            <div class="modal-content1">
                <span class="close" onclick="closeEditModal()">&times;</span>
                <h2 class="textt">Edit Service</h2>
                <form id="editServiceForm" method="post">
                    <label class="label" for="editServiceId">Service ID</label><br>
                    <input class="serviceName" type="text" id="editServiceId" name="editServiceId" readonly><br>
                    
                    <label class="label" for="editServiceName">Service Name</label><br>
                    <input class="serviceName" type="text" id="editServiceName" name="editServiceName" readonly><br>
                    
                    <label class="label" for="editAmount">Enter New Amount</label><br>
                    <input class="serviceamount" type="text" id="editAmount" name="editAmount"><br><br>
                    
                    <button type="submit" class="updatebtn">Update</button>
                </form>
            </div>
        </div>

    </main>

    <script>
        // Function to add a new service when the form is submitted
// Function to add a new service when the form is submitted
document.getElementById("serviceForm").addEventListener("submit", function(event) {
    event.preventDefault(); // Prevent the form from submitting and refreshing the page

    // Get the values from the input fields
    const serviceName = document.getElementById("serviceName").value;
    const amount = document.getElementById("amount").value;

    // Send a POST request to add the new service
    fetch('add_service.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            serviceName: serviceName,
            amount: amount,
            salonid: <?php echo $salonid; ?>
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Create a new row in the table
            const table = document.querySelector(".appointments_table");
            const newRow = document.createElement("tr");
            newRow.setAttribute("id", `service-${data.serviceid}`);
            newRow.innerHTML = `
                <td>${data.serviceid}</td>
                <td>${serviceName}</td>
                <td id="amount-${ data.serviceid}">${amount}</td>
                <td>
                    <button class="editbtn" onclick="openEditModal('${data.serviceid}', '${serviceName}', '${amount}')">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </button>
                    <button class="deletebtn" onclick="deleteService('${data.serviceid}')">
                        <i class="fa-solid fa-trash"></i>
                    </button>
                </td>
            `;

            // Append the new row to the table
            table.appendChild(newRow);

            // Clear the input fields
            document.getElementById("serviceName").value = "";
            document.getElementById("amount").value = "";

            // Close the modal
            closeModal();
        } else {
            alert("Failed to add service");
        }
    })
    .catch(error => console.error('Error:', error));
});

        // For Delete button
        // Function to delete a service instantly without a prompt
        function deleteService(serviceId) {
            // Confirm with the user before deleting
            const confirmDelete = confirm("Are you sure you want to delete this service?");
            
            if (confirmDelete) {
                // Send a POST request to delete the service
                fetch('delete_service.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        serviceId: serviceId
                    })
                })
                .then(response => response .json())
                .then(data => {
                    if (data.success) {
                        // Find the row by its service ID and remove it from the DOM
                        const row = document.getElementById(`service-${serviceId}`);
                        if (row) {
                            row.remove(); // Instantly removes the row
                            alert("Service deleted successfully!");
                        } else {
                            alert("Service not found!");
                        }
                    } else {
                        alert("Failed to delete service");
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }

        // For Edit Modal
        // Function to open the edit modal and pre-fill the form with current values
        function openEditModal(serviceId, serviceName, currentAmount) {
            // Display the modal
            document.getElementById("editServiceModal").style.display = "block";
            
            // Fill the form fields with the service data
            document.getElementById("editServiceId").value = serviceId;
            document.getElementById("editServiceName").value = serviceName;
            document.getElementById("editAmount").value = currentAmount;
        }

        // Function to close the modal
        function closeEditModal() {
            document.getElementById("editServiceModal").style.display = "none";
        }

        // Function to update the service amount when the form is submitted
        function updateService(event) {
            event.preventDefault(); // Prevent the form from submitting and refreshing the page
            
            // Get the values from the form
            const serviceId = document.getElementById("editServiceId").value;
            const newAmount = document.getElementById("editAmount").value;

            // Send a POST request to update the service
            fetch('update_service.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    serviceId: serviceId,
                    newAmount: newAmount
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the amount in the table
                    document.getElementById(`amount-${serviceId}`).innerText = newAmount;

                    // Close the modal after updating
                    closeEditModal();
                } else {
                    alert("Failed to update service");
                }
            })
            .catch(error => console.error('Error:', error));
        }

        document.getElementById("editServiceForm").addEventListener("submit", updateService);

        // For Add Button MODAL
        // Open Modal
        function openModal() {
            document.getElementById("addServiceModal").style.display = "block";
        }

        // Close Modal
        function closeModal() {
            document.getElementById("addServiceModal").style.display = "none";
        }

        // Close modal if clicked outside of it
        window.onclick = function(event) {
            var modal = document.getElementById("addServiceModal");
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        const profileLink = document.querySelector(".nav-link.profile > a");
        profileLink.addEventListener("click", (e) => {
            e.preventDefault();
            const dropdown = profileLink.nextElementSibling;
            dropdown.classList.toggle("show");
        });

        // Close dropdown if clicked outside
        document.addEventListener("click", (e) => {
            if (!profileLink.contains(e.target)) {
                document
                    .querySelector(".dropdown-menu.show")
                    ?.classList.remove("show");
            }
        });
    </script>
</body>
</html>