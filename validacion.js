document.addEventListener("DOMContentLoaded", () => {

    const form = document.querySelector("form");

    if (!form) return;

    form.addEventListener("submit", function (e) {

        let errors = [];
        const nombre = document.querySelector("[name='nombre']");
        const categoria = document.querySelector("[name='categoria']");
        const cantidad = document.querySelector("[name='cantidad']");
        const precio = document.querySelector("[name='precio']");

        // Validaciones básicas
        if (nombre && nombre.value.trim() === "") {
            errors.push("El nombre es obligatorio.");
        }

        if (categoria && categoria.value.trim() === "") {
            errors.push("La categoría es obligatoria.");
        }

        if (cantidad && (cantidad.value === "" || isNaN(cantidad.value) || cantidad.value < 0)) {
            errors.push("La cantidad debe ser un número válido.");
        }

        if (precio && (precio.value === "" || isNaN(precio.value) || precio.value < 0)) {
            errors.push("El precio debe ser un valor numérico válido.");
        }

        // Si hay errores, evitar envío
        if (errors.length > 0) {
            e.preventDefault();
            alert(errors.join("\n"));
        }
    });
});
