function searchTable() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("searchInput");
    filter = input.value.toUpperCase();
    table = document.getElementById("myTable");
    tr = table.getElementsByTagName("tr");

    for (i = 1; i < tr.length; i++) { // Start from index 1 to skip the header row
        td = tr[i].getElementsByTagName("td");
        var found = false;
        for (var j = 0; j < td.length; j++) {
            txtValue = td[j].textContent || td[j].innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                found = true;
                break;
            }
        }
        tr[i].style.display = found ? "" : "none";
    }
}

document.addEventListener('DOMContentLoaded', function () {
    var socialIcon = document.querySelector('.social-icon');
    var dropdown = document.querySelector('.dropdown');

    socialIcon.addEventListener('click', function (event) {
        event.stopPropagation(); // Prevents the click event from reaching the document click listener

        // Toggle the display of the dropdown
        dropdown.style.display = (dropdown.style.display === 'block') ? 'none' : 'block';
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', function () {
        dropdown.style.display = 'none';
    });
});

function validateNumberInput(input) {
    // Ensure the input value is a valid number
    if (isNaN(input.value)) {
        input.setCustomValidity("Please enter a valid number.");
    } else {
        input.setCustomValidity(""); // Clear the custom validity message
    }
}
