/**
 * Pagination Utility for managing table data with server-side pagination
 * @author KingLang-Booking
 */

function initializePagination(config) {
    const {
        tableBodyId,
        paginationContainerId,
        recordInfoId,
        defaultLimit = 10,
        defaultSortColumn = 'id',
        defaultSortOrder = 'asc',
        fetchDataFunction,
        renderRowsFunction,
    } = config;

    // Internal state
    const state = {
        currentPage: 1,
        limit: defaultLimit,
        sortColumn: defaultSortColumn,
        sortOrder: defaultSortOrder,
        filter: 'all',
        search: '',
        totalItems: 0,
        totalPages: 0,
        loading: false
    };

    // DOM Elements
    const tableBody = document.getElementById(tableBodyId);
    const paginationContainer = document.getElementById(paginationContainerId);
    const recordInfo = document.getElementById(recordInfoId);

    // Function to load data
    async function loadData() {
        if (state.loading) return;
        
        state.loading = true;
        console.log("Loading data with state:", { ...state });
        showLoading();
        
        try {
            const result = await fetchDataFunction({
                page: state.currentPage,
                limit: state.limit,
                sortColumn: state.sortColumn,
                sortOrder: state.sortOrder,
                status: state.filter,
                search: state.search
            });
            
            console.log("Data loaded:", result);
            
            if (result) {
                state.totalItems = result.total;
                state.totalPages = result.totalPages;
                
                renderRowsFunction(result.items);
                renderPagination();
                updateRecordInfo();
            }
        } catch (error) {
            console.error("Error loading data:", error);
            tableBody.innerHTML = `<tr><td colspan="20" class="text-center text-danger">Error loading data. Please try again.</td></tr>`;
        } finally {
            state.loading = false;
            hideLoading();
        }
    }

    // Show loading indicator
    function showLoading() {
        tableBody.innerHTML = `<tr><td colspan="20" class="text-center">
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div> Loading data...
        </td></tr>`;
    }

    // Hide loading indicator
    function hideLoading() {
        // Loading indicator will be replaced by the actual data
    }

    // Render pagination controls
    function renderPagination() {
        if (!paginationContainer) return;
        
        paginationContainer.innerHTML = '';
        
        if (state.totalPages <= 1) return;
        
        const ul = document.createElement('ul');
        ul.className = 'pagination justify-content-center';
        
        // Previous button
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${state.currentPage === 1 ? 'disabled' : ''}`;
        
        const prevLink = document.createElement('a');
        prevLink.className = 'page-link';
        prevLink.href = '#';
        prevLink.innerHTML = '&laquo;';
        prevLink.setAttribute('aria-label', 'Previous');
        
        prevLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (state.currentPage > 1) {
                goToPage(state.currentPage - 1);
            }
        });
        
        prevLi.appendChild(prevLink);
        ul.appendChild(prevLi);
        
        // Page numbers
        const startPage = Math.max(1, state.currentPage - 2);
        const endPage = Math.min(state.totalPages, startPage + 4);
        
        for (let i = startPage; i <= endPage; i++) {
            const li = document.createElement('li');
            li.className = `page-item ${i === state.currentPage ? 'active' : ''}`;
            
            const link = document.createElement('a');
            link.className = 'page-link';
            link.href = '#';
            link.textContent = i;
            
            link.addEventListener('click', (e) => {
                e.preventDefault();
                goToPage(i);
            });
            
            li.appendChild(link);
            ul.appendChild(li);
        }
        
        // Next button
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${state.currentPage === state.totalPages ? 'disabled' : ''}`;
        
        const nextLink = document.createElement('a');
        nextLink.className = 'page-link';
        nextLink.href = '#';
        nextLink.innerHTML = '&raquo;';
        nextLink.setAttribute('aria-label', 'Next');
        
        nextLink.addEventListener('click', (e) => {
            e.preventDefault();
            if (state.currentPage < state.totalPages) {
                goToPage(state.currentPage + 1);
            }
        });
        
        nextLi.appendChild(nextLink);
        ul.appendChild(nextLi);
        
        paginationContainer.appendChild(ul);
    }

    // Update record info text
    function updateRecordInfo() {
        if (!recordInfo) return;
        
        const start = (state.currentPage - 1) * state.limit + 1;
        const end = Math.min(state.currentPage * state.limit, state.totalItems);
        
        if (state.totalItems === 0) {
            recordInfo.textContent = 'No records found';
        } else {
            recordInfo.textContent = `Showing ${start} to ${end} of ${state.totalItems} entries`;
        }
    }

    // Go to specific page
    function goToPage(page) {
        if (page < 1 || page > state.totalPages || page === state.currentPage) return;
        
        state.currentPage = page;
        loadData();
    }

    // Public methods for the API
    return {
        // Initialize and load data
        init() {
            loadData();
            return this;
        },
        
        // Refresh data with current parameters
        refresh() {
            state.currentPage = 1;
            loadData();
            return this;
        },
        
        // Set current page
        setPage(page) {
            goToPage(page);
            return this;
        },
        
        // Set sort parameters and refresh
        setSort(column, order) {
            if (column) state.sortColumn = column;
            if (order) state.sortOrder = order;
            state.currentPage = 1;
            loadData();
            return this;
        },
        
        // Set filter and refresh
        setFilter(filter) {
            state.filter = filter;
            state.currentPage = 1;
            loadData();
            return this;
        },
        
        // Set search term and refresh
        setSearchTerm(term) {
            state.search = term;
            state.currentPage = 1;
            loadData();
            return this;
        },
        
        // Set limit per page and refresh
        setLimit(limit) {
            state.limit = parseInt(limit, 10);
            state.currentPage = 1;
            loadData();
            return this;
        },
        
        // Get current state
        getState() {
            return { ...state };
        }
    };
} 