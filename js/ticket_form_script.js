function fetchDepartments() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            //console.log(this.responseText); // Log the response to check if it's received correctly
            var departamentos = JSON.parse(this.responseText);
            populateDepartments(departamentos);
            // Populate categories based on the default department
            if (departamentos.length > 0 && departamentos[0].id_departamento === "1") {
                populateCategories();
            }
        }
    };
    xhr.open('GET', 'includes/department_listing.php', true);
    xhr.send();
}

            function populateDepartments(departamentos) {
                var departmentSelect = document.getElementById('department');
                departamentos.forEach(function (departamento) {
                    var option = document.createElement('option');
                    option.value = departamento.id_departamento;
                    option.textContent = departamento.nombre_departamento;
                    departmentSelect.appendChild(option);
                });
            }

            // Call fetchDepartments when the page is fully loaded
            document.addEventListener('DOMContentLoaded', function () {
                fetchDepartments();
            });