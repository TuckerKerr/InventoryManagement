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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

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
            console.log(capitalizedUsername);
        }

        // Check if the user is staff and hide/show the button
        const staffButton = document.getElementById('menu'); // Get the button by its ID
        if (staffButton) {
            if (admin === 'true') {
                staffButton.style.display = 'block'; // Show the button if the user is staff
                console.log("Admin");
            } else {
                staffButton.style.display = 'none'; // Hide the button if the user is not staff
                console.log('Not Admin');
            }
        } else {
            console.log("staffButton element not found");
        }
 

    }
    });

    document.addEventListener('DOMContentLoaded', function(){
        TableLoader();
        TonerTableLoader();
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
                    <h2>Laptops</h2>
                </div>
                <div class="box-content">
                    <div class="tablewrapper">
                   <table id="Laptopstable" border = "1">
                    <thead>
                        <tr><th>Quantity</th><th>Model</th><th>Campus</th></tr>
                    </thead>
                    <tbody></tbody>
                   </table>
                </div>
            </div>
        </div>
            <div class="box bottom-box">
                <div class="box-header">
                    <h2>Desktops</h2>
                </div>
                <div class="box-content">
                    <div class="tablewrapper">
                   <table id="Desktopstable" border = "1">
                    <thead>
                       <tr><th>Quantity</th><th>Model</th><th>Campus</th></tr>
                    </thead>
                    <tbody></tbody>
                   </table>
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
                   <table id="Monitorstable" border = "1">
                    <thead>
                        <tr><th>Quantity</th><th>Model</th><th>Campus</th></tr>
                    </thead>
                    <tbody></tbody>
                   </table>
                </div>
            </div>
        </div>

            <div class="box bottom-box">
                <div class="box-header">
                    <h2>Peripherals</h2>
                </div>
                <div class="box-content">
                    <div class="tablewrapper">
                   <table id="Peripheralstable" border = "1">
                    <thead>
                        <tr><th>Quantity</th><th>Model</th><th>Campus</th></tr>
                    </thead>
                    <tbody></tbody>
                   </table>
                </div>
            </div>
            </div>
        </div>

        <div class="box-right-column">
            <div class="box top-box">
                <div class="box-header">
                    <h2>Printers</h2>
                </div>
                <div class="box-content">
                    <div class="tablewrapper">
                   <table id="Printerstable" border = "1">
                    <thead>
                        <tr><th>Quantity</th><th>Model</th><th>Campus</th></tr>
                    </thead>
                    <tbody></tbody>
                   </table>
                </div>
            </div>
            </div>

            <div class="box bottom-box">
                <div class="box-header">
                    <h2>AV</h2>
                </div>
                <div class="box-content">
                    <div class="tablewrapper">
                   <table id="avtable" border = "1">
                    <thead>
                        <tr><th>Quantity</th><th>Model</th><th>Campus</th></tr>
                    </thead>
                    <tbody></tbody>
                   </table>
                </div>
            </div>
            </div>
        </div>

        <div class="tonerBottom">
            <div class="table-header">
                    <h2>Toner</h2>
                    <button style="margin-left: 0;"class="action-btn" onclick="showAddToner()">Input Toner</button>
                    <button onclick="printSticker(event)" class="action-btn" id="print">Print Sticker</button>
                    <input type="text" id="searchInput" placeholder="Search...">
                </div>
                <div class="box-content">
                    <div class="tablewrapper-toner">
                   <table id="tonerTable" border = "1">
                    <thead id="tonerHead">
                        <tr>
                            <th style="position: sticky;">
                                <label>
                                    <input type="radio" name="search" value="sticker_id" style="display: none;">Sticker ID</input>
                                </label>
                            </th>
                            <th style="position: sticky;">
                                <label>
                                    <input type="radio" name="search" value="Toner_ID" style="display: none;">Toner ID</input>
                                </label>
                            </th>
                            <th style="position: sticky;">
                                <label>
                                    <input type="radio" name="search" value="Printer_model" style="display: none;">Printer Model</input>
                                </label>
                            </th>
                            <th style="position: sticky;">
                                <label>
                                    <input type="radio" name="search" value="Color" style="display: none;">Color</input>
                                </label>
                            </th>
                            <th style="position: sticky;">
                                <label>
                                    <input type="radio" name="search" value="Located" style="display: none;">Location</input>
                                </label>
                            </th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                   </table>
                </div>
            </div>
        </div>

        <div id="InputPopup" class="popup">
            <div class="popup-content">
                <button class="close-popup" id="closeInput" onclick="closeButtonAdd()"><i class="fa-solid fa-xmark"></i></button>
                <h2>Add Items</h2>
                <form id="AddQuantityForm">
                    <input type="hidden" name="model" id="modelInput">
                    <input type="hidden" name="delivery" id="deliveryInput">
                    <input type="number" id="QuantityInput" name="quantityinput" min="0" max="999" step="1" value="0">
                    <button class="action-btn" name="action" value="add">Confirm</button>
                </form>
            </div>
        </div>

         <div id="RemovePopup" class="popup">
            <div class="popup-content">
                <button class="close-popup" id="closeRemove" onclick="closeButtonDelete()"><i class="fa-solid fa-xmark"></i></button>
                <h2>Remove Items</h2>
                <form id="RemoveQuantityForm">
                    <input type="hidden" name="model" id="modelRemove">
                    <input type="hidden" name="delivery" id="deliveryRemove">
                    <input type="number" id="QuantityInput" name="quantityinput" min="0" max="999" step="1" value="0">
                    <button class="action-btn" name="action" value="remove">Confirm</button>
                </form>
            </div>
        </div>

        <div id="TonerPopup" class="popup">
            <div class="popup-content">
                <button class="close-popup" onclick="closeButtonToner()"><i class="fa-solid fa-xmark"></i></button>
                <h2>Remove Toner</h2>
                <form id="TonerForm">
                    <input type="hidden" name="sticker_id" id="sticker_id">
                    <span style="padding: 10px; ">Are you sure you want to get rid of the toner: </span>
                    <span id="tonerNumber" style="font-weight: bold;"></span>
                    <br>
                    <br>
                    <button style="width: 100%; margin-left: 0;"class="action-btn" name="action" value="confirm">Confirm</button>
                    
                </form>
            </div>
        </div>

        <div id="TonerAdd" class="popup">
            <div class="popup-content-input">
                <button class="close-popup" onclick="closeAddToner()"><i class="fa-solid fa-xmark"></i></button>
                <h2>Add Toner</h2>
            <form id="TonerAddForm" style="display: flex; flex-direction: column;">
                    <label for="Date-Received">Date Received:</label>
                    <input type="date" id="Date-Received" name="date-received" class="form-input" value="<?php echo date('Y-m-d'); ?>" required>

                    <label id="Toner-ID-Title" for="Toner-ID">Toner ID:</label>
                    <input type="text" id="Toner-ID" name="toner-id" maxlength="255" class="form-input" required>
                

                    <label id="Color-Title" for="Color">Color:</label>
                    <select id="Color" name="color" class="form-input" required>
                            <option value="" disabled selected>Select Toner Color</option>
                            <option value="Black">Black</option>
                            <option value="Magenta">Magenta</option>
                            <option value="Cyan">Cyan</option>
                            <option value="Yellow">Yellow</option>
                        </select>
                    

                    <label id="Toner-Model-Title" for="Toner-Model"> Printer Model:</label>
                    <input type="text" id="Toner-Model" name="toner-model" maxlength="255" class="form-input" required >

                    <label id="Toner-Location-Title" for="Toner-Location" >Location:</label>
                    <select id="Toner-Location" name="toner-location" class="form-input" maxlength="255" required>
                        <option value="" disabled selected>Where is it going?</option>
                        <option value="CE/Q/W/Canon: Toner Closet">CE/Q/W/Canon: Toner Closet</option>
                        <option value="CF: Upstairs">CF: Upstairs</option>
                    </select>
                    
                    <button type="submit" id="submitAddToner" style = "margin-left: 0px; margin-top: 15px;" 
                    class="action-btn">Submit</button>
                    
                    <div class="decorative-line"></div>
                </form>
            </div>
        </div>

        <div id="ModelAdd" class="popup">
            <div class="popup-content-input">
                <button class="close-popup" onclick="closeModelAdd()"><i class="fa-solid fa-xmark"></i></button>
                <h2>Add/Remove Model</h2>
                    <form id="ModelForm" style="display: flex; flex-direction: column;">

                    <label for="Model-Tag" for="Model-Tag">Model:</label>
                    <input type="text" id="Model-Tag" name="model-tag" maxlength="255" required>

                    <label for="Type-of-Delivery">Type of Equipment:</label>
                    <select id="Type-of-Delivery" name="type-of-delivery" required>
                    <option value="" disabled selected>Select Type of Equipment</option>
                    <option value="Laptops">Laptop</option>
                    <option value="Desktops">Desktops</option>
                    <option value="Monitors">Monitors</option>
                    <option value="Macs">Macs</option>
                    <option value="Printers">Printers</option>
                    <option value="Peripherals">Consumable</option>
                    </select>  

                    <label for="Campus">Location:</label>
                    <select id="Campus" name="campus" required>
                      <option value="" disabled selected>Enter Campus</option>
                      <option value="Downcity">Downcity</option>
                      <option value="Harborside">Harborside</option>
                    </select>
                    
                    <button type="submit" style = "background-color: #302929; color: white; border: none; padding: 5px 20px; border-radius: 5px; cursor: pointer;" name="action" value="add"
                    class="btn btn-success mt-4">Add</button>

                    <button type="submit" style = "background-color: #302929; color: white; border: none; padding: 5px 20px; border-radius: 5px; cursor: pointer;" name="action" value="remove"
                    class="btn btn-success mt-4">Remove</button>
                    </form>
            </div>
     </div>
           

    </div>

    <div class="overlay" id="overlay"></div>
    

<div class="container mt-4">
<div class = "button-container" style="display: flex; gap: 10px;">
    <table id="recentTable" class="table table-sm mt-3" style="display: none; justify-content: left; align-items: center; width: 100px;">
                <thead>
                    <tr>
                        <th>Recent Sticker ID</th>
                    </tr>
                </thead>
                <tbody id="recentResult" style="justify-content: left; align-items: center; width: 450px;">
                    <!-- Data will be inserted here -->
                </tbody>
            </table> 
    </div>
</div>
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

    let clickedAction = ''; // Variable to hold which button you pressed
    const popup = document.getElementById("InputPopup");
    const closeBtn = document.getElementById("closeInput");

    const tonerBtn = document.getElementById("TonerPopup");
    const deleteToner = document.getElementById('TonerForm');

    const tonerAdd = document.getElementById("TonerAdd");
    const tonerAddForm = document.getElementById("TonerAddForm");

    const modelAR = document.getElementById('ModelAdd');
    const modelForm = document.getElementById('ModelForm');

    const RemovePopup = document.getElementById("RemovePopup");
    const closeRemove = document.getElementById("closeRemove");

    const addquantityForm = document.getElementById("AddQuantityForm");
    const removequantityForm = document.getElementById("RemoveQuantityForm");

    const sticker_id = document.getElementById("sticker_id");

    let search = '';

    //Dropdown Code for profile icon

   const profileIcon = document.getElementById("profileIcon");
  const dropdown = document.getElementById("dropdown");

  profileIcon.addEventListener("click", function (e) {
    e.stopPropagation(); // Prevents the document click from immediately hiding the dropdown
    console.log("clicked");
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
    console.log("clicked");
    menudropdown.classList.toggle("show");
  });

  // Close dropdown when clicking outside of it
  document.addEventListener("click", function (e) {
    if (!document.getElementById("profile").contains(e.target)) {
      menudropdown.classList.remove("show");
    }
  });

  //End of Dropdown Code

  //Code for the views


  const views = {
    "tableviews":['PeripheralsInSystem','LaptopsInSystem', 'DesktopsInSystem','MonitorsInSystem', 'PrintersInSystem'
    ]
  }
  const table = {
        "tables":["Peripherals","Laptops", "Desktops","Monitors", 'Printers']
  }

function TableLoader(){

views.tableviews.forEach((viewName, index) => {
  const tableId = `${table.tables[index]}table`; 
  const delivery = `${table.tables[index]}`;

  fetch(`query/expand.php?view=${viewName}`)
    .then(response=> response.json())
    .then(result=> {
        if(result.success && Array.isArray(result.data)){
        console.log(tableId);
        const tbody = document.querySelector(`#${tableId} tbody`);
        console.log(viewName);
        tbody.innerHTML=''
        result.data.forEach(row=> {
            const tr=document.createElement('tr');
            tr.innerHTML = Object.values(row).map((val, index, arr) => {
                if(index === 0){
                    const idValue = arr[1];
                    return `<td>${val} <button data-role="${delivery}" class="action-btn" id="${idValue}" onclick="showPopupDelete(this)" name="operation" value="remove_${idValue}"><i class="fa-solid fa-minus"></i></button><button data-role="${delivery}"  class="action-btn" id="${idValue}" onclick="showPopupAdd(this)"  name="operation" value="add_${idValue}"><i class="fa-solid fa-plus"></i></button></td>`;
                }
                return `<td>${val}</td>`;
            }).join('');

            tbody.appendChild(tr);
            });
        }
    else{
            console.error('Unexpected Response Format: ', result);
        }
      })
        .catch(error => console.error('Error fetching data:', error));
    });

}

function TonerTableLoader(){
  fetch(`query/expand.php?view=TonerInSystem&search=${search}`)
    .then(response=> response.json())
    .then(result=> {
        if(result.success && Array.isArray(result.data)){
        const tbody = document.querySelector(`#tonerTable tbody`);
        tbody.innerHTML=''
        result.data.forEach(row=> {
            const tr=document.createElement('tr');
             tr.innerHTML = Object.values(row).map((val, index, arr) => {
                if(index === 0){
                    const idValue = arr[0];
                    return `<td>${val} <button class="action-btn" id="${idValue}" onclick="showButtonToner(this)" name="operation" value="${idValue}"><i class="fa-solid fa-minus"></i></button></td>`;
                }
                return `<td>${val}</td>`;
            }).join('');
            tbody.appendChild(tr);
            });
        }
    else{
            console.error('Unexpected Response Format: ', result);
        }
      })
        .catch(error => console.error('Error fetching data:', error));
    }


    //END Code for the views


    //code for the searchbar for toner

    document.getElementById('searchInput').addEventListener('input', function(){
        const searchTerm = this.value.toLowerCase();
        const thead = document.getElementById('tonerHead');
        console.log(searchTerm);
        fetch(`query/searchTest.php?EQSearch=${searchTerm}&viewInfo=TonerInSystem`)
                .then(response => response.text())
                .then(data => {
                    tbody.innerHTML = data;
                })
                .catch(console.error)


                const tbody = document.querySelector('#tonerTable tbody');
                const rows = tbody.getElementsByTagName('tr');

                Array.from(rows).forEach((row,index) => {
                    console.log(index);

                    const text = Array.from(row.getElementsByTagName('td'))
                        .map(td => td.textContent.toLowerCase())
                        .join(' ');
                    row.classList.toggle('hidden', !text.includes(searchTerm));

            
                });
        })
        //end code for the searchbar

       
    document.querySelectorAll('input[name="search"]').forEach(radio =>{
        radio.addEventListener('change', event => {
        const sort = event.target.value;
        console.log(sort);
        search = sort;
        TonerTableLoader();
        });
    })


    tonerAddForm.addEventListener('submit',function(event){
        event.preventDefault();

        const formData = new FormData(tonerAddForm);

        fetch('query/tonerAdd.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    closeAddToner();
                    TonerTableLoader();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    });


      deleteToner.addEventListener('submit',function(event){
        event.preventDefault();

        const formData = new FormData(deleteToner);

        fetch('query/tonerRetrieval.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    closeButtonToner();
                    TonerTableLoader();
                    document.getElementById('searchInput').value = '';
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    });


    modelForm.addEventListener('submit',function(event){
        event.preventDefault();

        const formData = new FormData(modelForm);

        const actionValue = event.submitter.value;
        formData.set('action', actionValue);

        fetch('query/ModelAR.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    closeModelAdd();
                    TableLoader();
                    modelForm.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    });

    addquantityForm.addEventListener('submit',function(event){
        event.preventDefault();

        const formData = new FormData(addquantityForm);

        const actionValue = event.submitter.value;
        formData.set('action', actionValue);

        fetch('query/QuantityChange.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    closeButtonAdd();
                    closeButtonDelete();
                    TableLoader();
                    addquantityForm.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    });

    removequantityForm.addEventListener('submit',function(event){
        event.preventDefault();

        const formData = new FormData(removequantityForm);

        const actionValue = event.submitter.value;
        formData.set('action', actionValue);

        fetch('query/QuantityChange.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.text())
                .then(data => {
                    console.log(data);
                    closeButtonAdd();
                    closeButtonDelete();
                    TableLoader();
                    removequantityForm.reset();
                })
                .catch(error => {
                    console.error('Error:', error);
                });
    });




    //Code to auto adjust the tables
    const container = document.querySelector('bottom-box');
    const tablesize = document.querySelector('peripheraltable');

    container.style.height = `${tablesize.offsetHeight}px`;
    container.style.width = `${tablesize.offsetWidth}px`;
    //End of code for adjust

    
    //Popup Code for input
   
    // Show the popup
    function showPopupAdd(button) {
        const model= button.id;
        const table = button.dataset.role;
        console.log(model);
        console.log(table);
        document.getElementById('modelInput').value = model;
        document.getElementById('deliveryInput').value = table;
        popup.style.display = "flex";
        document.body.classList.add('modal-open');
        }

    // Close the popup
    function closeButtonAdd(){
        popup.style.display = "none";
        document.body.classList.remove('modal-open');
        console.log("clicked");
        }

        // Show the popup
    function showPopupDelete(button) {
        const model= button.id;
        const table = button.dataset.role;
        document.getElementById('modelRemove').value = model;
        document.getElementById('deliveryRemove').value = table;
        RemovePopup.style.display = "flex";
        document.body.classList.add('modal-open');
        }

    // Close the popup
    function closeButtonDelete(){
        RemovePopup.style.display = "none";
        document.body.classList.remove('modal-open');
        console.log("clicked");
        }

    // open the popup
    function showButtonToner(button){
        const toner= button.id;
        document.getElementById('sticker_id').value = toner;
        document.getElementById('tonerNumber').textContent = toner;
        tonerBtn.style.display = "flex";
        document.body.classList.add('modal-open');
        console.log("clicked");
        }

    // Close the popup
    function closeButtonToner(){
        tonerBtn.style.display = "none";
        document.body.classList.remove('modal-open');
        console.log("clicked");
        }

    
    // Open the popup
    function showAddToner(){
        tonerAdd.style.display = "flex";
        document.body.classList.add('modal-open');
        console.log("clicked");
        }

    // Close the popup
    function closeAddToner(){
        tonerAdd.style.display = "none";
        document.body.classList.remove('modal-open');
        console.log("clicked");
        }

    // Open the popup
    function showModelAdd(){
        modelAR.style.display = "flex";
        document.body.classList.add('modal-open');
        console.log("clicked");
        }

    // Close the popup
    function closeModelAdd(){
        modelAR.style.display = "none";
        document.body.classList.remove('modal-open');
        console.log("clicked");
        }


    
 
async function printSticker(event) {
    if (event) event.preventDefault();
    try {
        const response = await fetch('query/recentSticker.php');

        if (!response.ok) {
            throw new Error('Network response was not ok ' + response.statusText);
        }

        const data = await response.text(); // Get the response as plain text
        console.log(data); // Log the response to check its format

        const tonerResultBody = document.getElementById('recentResult');
        tonerResultBody.innerHTML = ''; // Clear previous results

        const rows = data.trim().split('\n'); // Split the plain text response into rows

        rows.forEach(sticker_id => {
            const newRow = document.createElement('tr');
            newRow.innerHTML = `<td>${sticker_id}</td>`;
            tonerResultBody.appendChild(newRow);
        });

        const sticker_id = rows[0]; // Assuming you want the first sticker_id for the barcode

        if(sticker_id === null || sticker_id === "" || sticker_id === "No data available."){
            showAlert();
        }
        else{
        var barcodeUrl = "https://barcode.tec-it.com/barcode.ashx?data=" + sticker_id; // URL to generate Code 39 barcode

        var printWindow = window.open('', '', 'width=600,height=400');
        printWindow.document.write('<html><head><title>Print Label</title>');
        printWindow.document.write('<style>@page { margin: 0; }</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<div style="width:337px; height:106px; font-size:24px; text-align:center; transform:rotate(90deg); transform-origin: 50px 70px;">');
        printWindow.document.write('<div style="font-size:14px;">' + sticker_id + '</div>'); // Print the number with smaller font size
        printWindow.document.write('<img src="' + barcodeUrl + '" style="width:250px; height:100px;"/>'); // Print the barcode with smaller size
        printWindow.document.write('</div>');
        printWindow.document.write('</body></html>');
        printWindow.document.close();

        // Ensure the entire document is loaded before printing
        printWindow.onload = function() {
            printWindow.print();
        };

        // Event listener for after printing
        printWindow.onafterprint = function() {
            // Wait 5 seconds before returning to the original page
            setTimeout(function() {        
                // Option 2: Redirect to the original page (if you want to go to another URL)
                printWindow.close();
                // window.location.href = "your-original-page-url.html";
            }, 1000); // 5000 ms = 5 seconds
        };
    }
    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

    </script>
 
</body>
</html>
