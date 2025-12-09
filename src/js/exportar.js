// Definimos la función como global (en window) para poder usarla en cualquier vista
window.exportarTablaXLSX = function (idTabla, nombreArchivo = "export.xlsx", nombreHoja = "Hoja1") {
    // Verifica que exista la tabla
    const tabla = document.getElementById(idTabla);
    if (!tabla) {
        console.warn(`No se encontró la tabla con id: ${idTabla}`);
        return;
    }

    try {
        // Crear un nuevo libro
        const wb = XLSX.utils.book_new();

        // Convertir la tabla en hoja de Excel
        const ws = XLSX.utils.table_to_sheet(tabla, { raw: true });

        // Agregar la hoja al libro
        XLSX.utils.book_append_sheet(wb, ws, nombreHoja);

        // Descargar el archivo
        XLSX.writeFile(wb, nombreArchivo);

    } catch (error) {
        console.error("Error al exportar la tabla:", error);
    }
};
