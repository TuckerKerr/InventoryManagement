<!DOCTYPE html>
<html lang="en">
    <head>
    <title>Inventory Management</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700&display=swap%27">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="prefetch" href="main.php">
    <link rel="stylesheet" href="style.css">

    <script>
    document.addEventListener('DOMContentLoaded', function(){

    // Check if the user is logged in
    if (sessionStorage.getItem('isLoggedIn') !== 'true') {
    window.location.href = 'index.html'; // Redirect to login page if not logged in
    } else {
        const username = sessionStorage.getItem('userName');
        const admin = sessionStorage.getItem('is_staff');
        
        if (username) {
            const capitalizedUsername = username.charAt(0).toUpperCase() + username.slice(1);
            document.getElementById('usernameDisplay').textContent = capitalizedUsername;
            //const firstLetter = username.charAt(0).toUpperCase();
            //document.getElementById('profileIcon').textContent = firstLetter;
            //console.log(firstLetter);
        }

        // Check if the user is staff and hide/show the button
        const staffButton = document.getElementById('menu'); // Get the button by its ID
        if (staffButton) {
            if (admin === 'true') {
                staffButton.style.display = 'block'; // Show the button if the user is staff
            } else {
                staffButton.style.display = 'none'; // Hide the button if the user is not staff
            }
        } else {
            console.log("staffButton element not found");
        }
 

    }
    });

    let idleTime = 0;
    let idleLimit = 2 * 60 * 1000;
    let sessionTimeout; 

    const resetIdleTimer = () =>{
        idleTimer = 0;
    };
/*
    const trackIdleTime = () => {
        idleTime += 1000;
        if(idleTime >= idleLimit){
            logout();
        }
    };
*/
    document.onmousemove = resetIdleTimer;
    document.onkeypress = resetIdleTimer;
    document.onclick = resetIdleTimer;
    document.onscroll = resetIdleTimer;

    setInterval(trackIdleTime, 1000);

        function logout() {
        sessionStorage.removeItem('isLoggedIn'); // Clear login status

        sessionStorage.removeItem('username'); // Clear username
        sessionStorage.removeItem('is_staff'); // Clear username
        window.location.href = 'index.html'; // Redirect to login page
        }
    </script>
</head>

<body>
<!-- Top Navigation Bar -->
<div class="topnav">
        <div class="profile" id="profile">
            <div class="profile-icon" id="profileIcon"><i class="fa-solid fa-user"></i></div>
            <div class="dropdown" id="dropdown">
                <div class="username">
                    <h2 id="usernameDisplay"></h2>
                </div>
                <a href="../schedule/INDEX-HTML/schedule_integration.html" class="dropdown-item">Schedule <i class="fa-solid fa-calendar-days"></i></a>
                <a href="../sts/INDEX-HTML/main.html" class="dropdown-item">STS <i class="fa-solid fa-building"></i></a>
                <a href="#" onclick="logout()" class="dropdown-item">Sign Out <i class="fa-solid fa-right-from-bracket"></i></a>
            </div>
        </div>
        <div class="menu" id="menu">
            <div class="menu-icon" id="menuIcon"><i class="fa-solid fa-folder-open"></i></div>
                <div class="menudropdown" id="menudropdown">
                    <div class="username">
                        <h2>Menu</h2>
                    </div>
                    <a href="adminPage.php" class="dropdown-item">Admin Page</a>
                    <a onclick="showModelAdd()" class="dropdown-item">Add Model <i class="fa-solid fa-gear"></i></a>
                </div>
        </div>
    </div>

<div class="main-content">
    <div class="title">Inventory Management</div>
<div class="box-container">
    <div class="box-left-column">
        <div class="box top-box">
            <div class="box-header">
                <h2>Bar chart</h2>
            </div>
            <div class="box-content">
                <div class="tablewrapper">
                    <canvas id="generalBar" style="height:800px;"></canvas>
              </div>
            </div>
        </div>
    </div>

    <div class="box-middle-column">
        <div class="box top-box">
            <div class="box-header">
                <h2>Monitors</h2>
            </div>
            <div class="box-content">
                <div class="tablewrapper">
                    <canvas id="monitorsPie"></canvas>
            </div>
        </div>
    </div>

        <div class="box bottom-box">
            <div class="box-header">
                <h2>Laptops</h2>
            </div>
            <div class="box-content">
                <div class="tablewrapper">
                    <canvas id="laptopsPie"></canvas>
            </div>
        </div>
        </div>
    </div>

    <div class="box-right-column">
        <div class="box top-box">
            <div class="box-header">
                <h2>Desktops</h2>
            </div>
            <div class="box-content">
                <div class="tablewrapper">
                    <canvas id="desktopsPie"></canvas>
            </div>
        </div>
        </div>

        <div class="box bottom-box">
            <div class="box-header">
                <h2>Printers</h2>
            </div>
            <div class="box-content">
                <div class="tablewrapper">
                    <canvas id="printersPie"></canvas>
            </div>
        </div>
        </div>
    </div>
</div>

<div class="bottom-box-container">
    <div class="tonerBottom-left">
        <div class="table-header">
                <h2>Last Used</h2>
                <button style="margin-left: 0;"class="action-btn" onclick="showAddToner()">Input Toner</button>
                <button onclick="printSticker(event)" class="action-btn" id="print">Print Sticker</button>
                <input type="text" id="searchInput" placeholder="Search...">
            </div>
            <div class="box-content">
                <div class="tablewrapper-toner">
        
            </div>
        </div>
    </div>

    <div class="tonerBottom-right">
        <div class="table-header">
                <h2>Open Status</h2>
                <button style="margin-left: 0;"class="action-btn" onclick="showAddEquipment()">Input Equipment</button>
                <input type="text" id="EQsearchInput" placeholder="Search...">
            </div>
            <div class="box-content">
                <div class="tablewrapper-toner">
                <table id="eqTable" border = "1">
               
            </div>
        </div>
    </div>
</div>

<div class="overlay" id="overlay"></div>
    
<footer>
    <p>© Johnson & Wales University 2025</p>
 </footer>
    <div class="decorative-line"></div>
    <div class = "search-header"></div>
    </div> 
<div id="modal" class="modal">
    <div class="modal-content">
        <span id="closeModal" class="close">&times;</span>
        <h2>Please Enter Information</h2>
        <form id="inputForm">
            <label for="userInput">Your Input:</label>
            <input type="text" id="userInput" name="userInput" required>
            <button type="submit" onclick="delayedFetch()">Submit</button>
        </form>
    </div>
</div>
  </div>
    <script>
    //Dropdown Code for profile icon

    const profileIcon = document.getElementById("profileIcon");
    const dropdown = document.getElementById("dropdown");

  profileIcon.addEventListener("click", function (e) {
    e.stopPropagation(); // Prevents the document click from immediately hiding the dropdown

    dropdown.classList.toggle("show");
  });

  // Close dropdown when clicking outside of it
  document.addEventListener("click", function (e) {
    if (!document.getElementById("profile").contains(e.target)) {
      dropdown.classList.remove("show");
    }
  });


   const menuIcon = document.getElementById("menuIcon");
  const menudropdown = document.getElementById("menudropdown");

  menuIcon.addEventListener("click", function (e) {
    e.stopPropagation(); // Prevents the document click from immediately hiding the dropdown

    menudropdown.classList.toggle("show");
  });

    // Close dropdown when clicking outside of it
    document.addEventListener("click", function (e) {
    if (!document.getElementById("profile").contains(e.target)) {
        menudropdown.classList.remove("show");
    }
    });

    //End of Dropdown Code


    //code for bar chart data

    fetch('query/chartData.php')
        .then(response => response.json())
        .then(data => {

            const allLabels = data.labels;
            const allValues = data.values;
            const allTypes = data.type;

            const monitorsLabels = [];
            const monitorsValues = [];

            const laptopsLabels = [];
            const laptopsValues = [];

            const desktopsLabels = [];
            const desktopsValues = [];

            const printersLabels = [];
            const printersValues = [];

            const backgroundColors = ['#333', '#ffffff', '#1a1a1a', 
            '#4d4d4d', '#e6e6e6', '#555555', 
            '#919191', '#8a8a8a', '#707070'];

            const bar = document.getElementById('generalBar').getContext('2d');
            new Chart(bar, {
                type: 'bar',
                data: {
                    labels: allLabels,
                    datasets: [{
                        label: 'Quantity',
                        backgroundColor: '#333',
                        hoverBackgroundColor: 'black',
                        data: allValues,
                    }]
                },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false
            }
            })
            

            allLabels.forEach((label1, index) => {
                if (allTypes[index] === 'Monitors') {
                    monitorsLabels.push(allLabels[index]);
                    monitorsValues.push(allValues[index]);
                }
            });

            new Chart(document.getElementById('monitorsPie'),{
                type: 'pie',
                data: {
                    labels: monitorsLabels,
                    datasets: [{
                        label1: 'Monitors',
                        data: monitorsValues,
                        backgroundColor: backgroundColors
                    }]
                }
            })

            allLabels.forEach((label1, index) => {
                if (allTypes[index] === 'Laptops') {
                    laptopsLabels.push(allLabels[index]);
                    laptopsValues.push(allValues[index]);
                }
            });

            new Chart(document.getElementById('laptopsPie'),{
                type: 'pie',
                data: {
                    labels: laptopsLabels,
                    datasets: [{
                        label1: 'Laptops',
                        data: laptopsValues,
                        backgroundColor: backgroundColors
                    }]
                }
            })

            allLabels.forEach((label1, index) => {
                if (allTypes[index] === 'Desktops') {
                    desktopsLabels.push(allLabels[index]);
                    desktopsValues.push(allValues[index]);
                }
            });

            new Chart(document.getElementById('desktopsPie'),{
                type: 'pie',
                data: {
                    labels: desktopsLabels,
                    datasets: [{
                        label1: 'Desktops',
                        data: desktopsValues,
                        backgroundColor: backgroundColors
                    }]
                }
            })

            allLabels.forEach((label1, index) => {
                if (allTypes[index] === 'Printers') {
                    printersLabels.push(allLabels[index]);
                    printersValues.push(allValues[index]);
                }
            });

            new Chart(document.getElementById('printersPie'),{
                type: 'pie',
                data: {
                    labels: printersLabels,
                    datasets: [{
                        label1: 'Printers',
                        data: printersValues,
                        backgroundColor: backgroundColors
                    }]
                }
            })
        })
        </script>
    </body>
</html>
