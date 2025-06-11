document.addEventListener('DOMContentLoaded', () => {
    const selectElement = document.getElementById('ingredientes-select');
    const ingredientesAñadidos = document.getElementById('ingredientes-añadidos');

    // Manejar la selección de ingredientes
    selectElement.addEventListener('change', function () {
        const seleccionados = Array.from(this.selectedOptions);

        seleccionados.forEach((opcion) => {
            const ingrediente = opcion.value;

            // Verificar si el ingrediente ya está en la lista
            const existe = Array.from(ingredientesAñadidos.children).some(
                (child) => child.querySelector('span').textContent === ingrediente
            );

            if (!existe) {
                // Crear un div para el ingrediente añadido
                const ingredienteDiv = document.createElement('div');
                ingredienteDiv.className = 'flex items-center bg-gray-200 px-4 py-2 rounded-lg';
                ingredienteDiv.innerHTML = `
                    <span class="text-gray-700">${ingrediente}</span>
                    <span class="ml-2 text-green-500">✔</span>
                    <button class="ml-2 text-red-500 hover:text-red-700">✖</button>
                `;

                // Añadir el ingrediente al contenedor
                ingredientesAñadidos.appendChild(ingredienteDiv);

                // Manejar la eliminación del ingrediente
                ingredienteDiv.querySelector('button').addEventListener('click', function () {
                    ingredienteDiv.remove();
                    opcion.selected = false; // Deseleccionar el ingrediente en el select
                });
            }
        });

        // Deseleccionar todos los ingredientes en el select después de añadirlos
        this.value = '';
    });
});