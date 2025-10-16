document.addEventListener('DOMContentLoaded', () => {
    const perPage = 5;
    let currentPage = 1;
    const searchInput = document.getElementById('search') as HTMLInputElement | null;
    const noResultsRow = document.getElementById('no-results') as HTMLElement | null;
    const paginationInfo = document.getElementById('pagination-info') as HTMLElement | null;
    const paginationButtons = document.getElementById('pagination-buttons') as HTMLElement | null;

    const allRows = Array.from(document.querySelectorAll('tr.admin-row')) as HTMLTableRowElement[];

    function getVisibleRows(): HTMLTableRowElement[] {
        const q = (searchInput && searchInput.value || '').toLowerCase().trim();
        if (!q) return allRows.slice();
        return allRows.filter(row => row.textContent?.toLowerCase().includes(q));
    }

    function renderPage(page: number) {
        const visible = getVisibleRows();
        const totalPages = Math.max(1, Math.ceil(visible.length / perPage));
        if (page < 1) page = 1;
        if (page > totalPages) page = totalPages;
        currentPage = page;

        // hide all
        allRows.forEach(r => r.style.display = 'none');

        if (visible.length === 0) {
            if (noResultsRow) noResultsRow.classList.remove('hidden');
            if (paginationInfo) paginationInfo.textContent = 'Showing 0 of 0 admins';
            if (paginationButtons) paginationButtons.innerHTML = '';
            return;
        }

        if (noResultsRow) noResultsRow.classList.add('hidden');

        const start = (currentPage - 1) * perPage;
        const end = start + perPage;
        const pageRows = visible.slice(start, end);
        pageRows.forEach(r => r.style.display = 'table-row');

        if (paginationInfo) {
            const first = start + 1;
            const last = Math.min(end, visible.length);
            paginationInfo.textContent = `Showing ${first}-${last} of ${visible.length} admins`;
        }

        if (!paginationButtons) return;
        paginationButtons.innerHTML = '';

        const prev = document.createElement('button');
        prev.type = 'button';
        prev.className = 'px-3 py-1 rounded bg-white border text-sm';
        prev.textContent = 'Prev';
        prev.disabled = currentPage === 1;
        prev.addEventListener('click', () => renderPage(currentPage - 1));
        paginationButtons.appendChild(prev);

        const maxButtons = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxButtons / 2));
        let endPage = Math.min(Math.max(1, Math.ceil(visible.length / perPage)), startPage + maxButtons - 1);
        if (endPage - startPage < maxButtons - 1) {
            startPage = Math.max(1, endPage - maxButtons + 1);
        }

        for (let i = startPage; i <= endPage; i++) {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-3 py-1 rounded text-sm ' + (i === currentPage ? 'bg-blue-600 text-white' : 'bg-white border');
            btn.textContent = String(i);
            btn.addEventListener('click', () => renderPage(i));
            paginationButtons.appendChild(btn);
        }

        const next = document.createElement('button');
        next.type = 'button';
        next.className = 'px-3 py-1 rounded bg-white border text-sm';
        next.textContent = 'Next';
        next.disabled = currentPage === Math.ceil(visible.length / perPage);
        next.addEventListener('click', () => renderPage(currentPage + 1));
        paginationButtons.appendChild(next);
    }

    // Debounced search
    if (searchInput) {
        let debounceTimer: number | null = null;
        searchInput.addEventListener('input', () => {
            if (debounceTimer !== null) window.clearTimeout(debounceTimer);
            debounceTimer = window.setTimeout(() => {
                currentPage = 1;
                renderPage(currentPage);
            }, 300);
        });
    }

    // initial render
    renderPage(currentPage);
});

