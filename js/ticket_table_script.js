            document.addEventListener('DOMContentLoaded', function () {

                const dateFilterIcon = document.querySelector('.date-filter-icon');
                const dateInputsContainer = document.querySelector('.date-inputs');


                // add event listener to the icon
                if (dateFilterIcon) {
                    dateFilterIcon.addEventListener('click', function () {
                        dateInputsContainer.classList.toggle('hidden');
                    });
                }

                const filterInputs = document.querySelectorAll('.filter-input');

                filterInputs.forEach(input => {
                    input.addEventListener('change', applyFilters);
                });

                function applyFilters() {
                    const table = document.getElementById('myTable');
                    const tbody = table.getElementsByTagName('tbody')[0];
                    const rows = tbody.getElementsByTagName('tr');

                    // get start and end date from input fields that is then used
                    const startDate = new Date(document.getElementById('start-date').value);
                    const endDate = new Date(document.getElementById('end-date').value);

                    endDate.setDate(endDate.getDate() + 1); // Include the selected end date

                    // Loop through each row and check if the ticket open date is within the selected range
                    for (let i = 0; i < rows.length; i++) {
                        const row = rows[i];
                        const ticketOpenCell = row.querySelector('td:nth-child(6)'); // Ticket_Open is the 6th column

                        if (ticketOpenCell) {
                            const ticketOpenDate = new Date(ticketOpenCell.textContent.trim()); //date string to date object

                            const isVisible =
                                (!startDate || ticketOpenDate >= startDate) &&
                                (!endDate || ticketOpenDate <= endDate);

                            row.style.display = isVisible ? '' : 'none'; //displays data based on the range
                        }
                    }
                }


                function removeRow(button) {
                    const row = button.closest('tr');
                    if (row) {
                        // Send AJAX request to update 'hidden' status of the ticket
                        const incidentIdElement = row.querySelector('.incident-id');
                        const incidentId = incidentIdElement.textContent.trim();

                        const xhr = new XMLHttpRequest();
                        xhr.open('POST', 'remove_ticket.php', true);
                        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
                        xhr.onload = function () {
                            if (xhr.status === 200) {
                                // On success, hide the row from the UI
                                row.classList.add('hidden');
                                alert('Ticket hidden successfully');
                            } else {
                                alert('Error hiding ticket: ' + xhr.responseText);
                            }
                        };
                        xhr.send(`incident_id=${incidentId}`);
                    }
                }
            });
