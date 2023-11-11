<?php
$pageTitle = "Inventory";
include '../contain/header.php';
?>

<div class="main-content">

    <header>
        <div class="directory-tag">
            <p>Inventory</p>
        </div>

        <div class="social-icons">
            <div></div>
            <span>username</span>
        </div>
    </header>

    <main>
        <div class="button-and-search">
            <button>Create New</button>
            <input type="text" id="searchInput" placeholder="Search for names..." onkeyup="searchTable()">
        </div>

        <div class="table-responsive">
            <table id="myTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Office</th>
                        <th>Age</th>
                        <th>Start date</th>
                        <th>Salary</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Table rows will be dynamically added here using JavaScript -->
                </tbody>
            </table>
        </div>

        <div id="pagination" class="pagination">
            <!-- Pagination links will be dynamically added here using JavaScript -->
        </div>
    </main>

</div>

<script>
    function searchTable() {
        // Declare variables
        var input, filter, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        filter = input.value.toUpperCase();
        table = document.getElementById("myTable"); // Change to 'myTable' as that's the ID of your table
        tr = table.getElementsByTagName("tr");

        // If the search input is empty, show all rows and return
        if (filter === "") {
            for (i = 0; i < tr.length; i++) {
                tr[i].style.display = "";
            }
            return;
        }

        // Loop through all table rows, and hide those that don't match the search query
        for (i = 0; i < tr.length; i++) {
            if (i === 0) {
                // Skip the first row (thead)
                continue;
            }

            td = tr[i].getElementsByTagName("td"); // Change to 'td' to get all td elements
            let rowShouldBeVisible = false;

            // Loop through all td elements in the current row
            for (let j = 0; j < td.length; j++) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                    // If any td contains the search query, show the entire row
                    rowShouldBeVisible = true;
                    break;
                }
            }

            if (rowShouldBeVisible) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }

    const itemsPerPage = 10; // Number of items to display per page
    const data = [
        ["John Doe", "Developer", "New York", 30, "2020-01-15", "$90,000"],
        ["Jane Smith", "Designer", "San Francisco", 28, "2018-05-20", "$80,000"],
        ["Bob Johnson", "Manager", "Chicago", 35, "2015-08-10", "$110,000"],
        ["Alice Williams", "Analyst", "Los Angeles", 32, "2017-03-05", "$95,000"],
        ["Charlie Brown", "Engineer", "Seattle", 29, "2019-11-12", "$85,000"],
        ["Eva Davis", "Coordinator", "Boston", 27, "2021-02-28", "$75,000"],
        ["Michael Lee", "Consultant", "Dallas", 40, "2012-09-08", "$120,000"],
        ["Olivia Turner", "Supervisor", "Miami", 33, "2016-06-15", "$100,000"],
        ["David Martinez", "Administrator", "Phoenix", 31, "2018-12-20", "$88,000"],
        ["Sophia Clark", "Specialist", "Denver", 26, "2022-04-03", "$70,000"],
        ["Liam Wilson", "Coordinator", "Atlanta", 28, "2019-07-18", "$80,000"],
        ["Emma Harris", "Analyst", "San Diego", 34, "2014-10-25", "$98,000"],
        ["James Allen", "Designer", "Houston", 29, "2020-03-30", "$87,000"],
        ["Ava Adams", "Developer", "Portland", 27, "2021-08-05", "$92,000"],
        // ... (other employee data)
    ];

    function displayTablePage(page) {
        const startIndex = (page - 1) * itemsPerPage;
        const endIndex = startIndex + itemsPerPage;
        const currentPageData = data.slice(startIndex, endIndex);

        const tableBody = document.getElementById("tableBody");
        tableBody.innerHTML = ""; // Clear existing content

        currentPageData.forEach(employee => {
            const row = document.createElement("tr");
            row.innerHTML = `
            <td>${employee[0]}</td>
            <td>${employee[1]}</td>
            <td>${employee[2]}</td>
            <td>${employee[3]}</td>
            <td>${employee[4]}</td>
            <td>${employee[5]}</td>
            <td>
                <button class="edit">edit</button>
                <button class="delete">delete</button>
            </td>
        `;
            tableBody.appendChild(row);
        });
    }

    function updatePaginationLinks(currentPage, totalPages) {
    const pagination = document.getElementById("pagination");
    pagination.innerHTML = ""; // Clear existing content

    const visiblePages = 5; // Number of visible pagination links
    const ellipsisThreshold = 2; // Threshold for displaying ellipsis

    let startPage = Math.max(1, currentPage - Math.floor(visiblePages / 2));
    let endPage = Math.min(totalPages, startPage + visiblePages - 1);

    if (totalPages > visiblePages) {
        // Adjust startPage and endPage to show ellipsis
        if (currentPage <= ellipsisThreshold) {
            endPage = visiblePages;
        } else if (currentPage >= totalPages - ellipsisThreshold) {
            startPage = totalPages - visiblePages + 1;
        } else {
            startPage = currentPage - Math.floor(visiblePages / 2);
            endPage = startPage + visiblePages - 1;
        }
    }

    for (let i = startPage; i <= endPage; i++) {
        const link = document.createElement("a");
        link.href = "javascript:void(0);";
        link.textContent = i;
        link.onclick = function () {
            const clickedPage = parseInt(link.textContent);
            displayTablePage(clickedPage);
            updatePaginationLinks(clickedPage, totalPages);
        };

        if (i === currentPage) {
            link.classList.add("active"); // Highlight the current page
        }

        pagination.appendChild(link);
    }

    // Add ellipsis if necessary
    if (startPage > 1) {
        const ellipsisStart = document.createElement("span");
        ellipsisStart.textContent = "...";
        ellipsisStart.classList.add("ellipsis");
        pagination.insertBefore(ellipsisStart, pagination.firstChild);
    }

    if (endPage < totalPages) {
        const ellipsisEnd = document.createElement("span");
        ellipsisEnd.textContent = "...";
        ellipsisEnd.classList.add("ellipsis");
        pagination.appendChild(ellipsisEnd);
    }
}

    function displayPagination() {
        const totalPages = Math.ceil(data.length / itemsPerPage);
        const currentPage = 1; // You can set the default page here

        updatePaginationLinks(currentPage, totalPages);
    }

    // Initialize the page with the first set of data and pagination links
    displayTablePage(1);
    displayPagination();
</script>

</body>
</html>