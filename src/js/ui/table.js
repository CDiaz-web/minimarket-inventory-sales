

export function initTables() {

    const tables = document.querySelectorAll('[data-table]');
    if (!tables.length) return;

    tables.forEach(table => {

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        const rows = Array.from(tbody.querySelectorAll('tr'));
        if (!rows.length) return;
        
        const pageSize = parseInt(
            table.dataset.pageSize || 10
        );

        let currentPage = 1;
        let filteredRows = [...rows];

        const searchInput = document.querySelector(
            `[data-table-search="${table.id}"]`
        );

        const paginationContainer = document.querySelector(
            `[data-table-pagination="${table.id}"]`
        );

        /* =====================
           RENDER
        ====================== */

        function render() {

            tbody.innerHTML = '';

            const start = (currentPage - 1) * pageSize;
            const end = start + pageSize;

            const pageRows = filteredRows.slice(start, end);

            pageRows.forEach(row => tbody.appendChild(row));

            renderPagination();
        }

        /* =====================
           PAGINATION
        ====================== */

        function renderPagination() {

            if(!paginationContainer) return;

            const totalPages =
                Math.ceil(filteredRows.length / pageSize);

            paginationContainer.innerHTML = '';

            for(let i=1;i<=totalPages;i++){

                const btn = document.createElement('button');
                btn.textContent = i;

                if(i === currentPage){
                    btn.classList.add('active');
                }

                btn.addEventListener('click', () => {
                    currentPage = i;
                    render();
                });

                paginationContainer.appendChild(btn);
            }
        }

        /* =====================
           SEARCH
        ====================== */

        if(searchInput){

            searchInput.addEventListener('input', e => {

                const term = e.target.value.toLowerCase();

                filteredRows = rows.filter(row =>
                    row.textContent.toLowerCase().includes(term)
                );

                currentPage = 1;
                render();
            });
        }

        render();
    });
}