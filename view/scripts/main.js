function confirmUserLogout() {

    if (window.confirm("Are you sure you want to logout?")) {

        window.location.replace("http://www.mycal.com/view/controller/logout-controller.php");
    }
}

function hideDivById(id) {

    document.getElementById(id).style.display = 'none';
    document.getElementById(id).style.visibility = 'hidden';
}

function showDivById(id) {

    document.getElementById(id).style.visibility = 'visible';
    document.getElementById(id).style.display = 'block';
}

function validatePasswords(password, reenterPassword, messageContainer, id) {

    if (password != reenterPassword) {

        document.getElementById(id).reset();
        document.getElementById(messageContainer).innerHTML = "Passwords Don't Match";
        return false;
    }

    return true;
}

function addSelectionRowHandler(tableId) {

    var table = document.getElementById(tableId);
    var rows = table.getElementsByTagName("tr");

    for (i = 1; i < rows.length; i++) {

        var currentRow = table.rows[i];

        var createClickHandler = function (row) {

            return function () {
                
                var selectedRow = document.getElementsByClassName('selected-row');
                if(selectedRow[0] != null) {
                    
                    selectedRow[0].classList.remove("selected-row");
                }
                row.classList.add("selected-row");
            };
        };

        currentRow.onclick = createClickHandler(currentRow);
    }
}