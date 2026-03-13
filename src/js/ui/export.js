
export function exportarTablaXLSX(idTabla, nombreArchivo = "export.xlsx", nombreHoja = "Hoja1") {

    const tabla = document.getElementById(idTabla);

    if (!tabla) {
        console.warn(`No se encontró la tabla con id: ${idTabla}`);
        return;
    }

    try {

        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.table_to_sheet(tabla, { raw: true });

        XLSX.utils.book_append_sheet(wb, ws, nombreHoja);
        XLSX.writeFile(wb, nombreArchivo);

    } catch (error) {
        console.error("Error al exportar la tabla:", error);
    }
}