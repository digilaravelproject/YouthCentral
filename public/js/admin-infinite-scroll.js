/**
 * Admin Infinite Scroll Implementation
 * Enhanced version with smooth scrolling, proper indicators, and performance optimizations
 */

class AdminInfiniteScroll {
    constructor(options = {}) {
        this.container = options.container || 'tbody';
        this.currentPage = 1;
        this.isLoading = false;
        this.hasMorePages = true;
        this.baseUrl = window.location.pathname;
        this.searchParams = new URLSearchParams(window.location.search);
        this.scrollThreshold = 500; // Increased threshold for earlier loading
        this.throttleDelay = 16; // 60fps for ultra-smooth response
        
        this.init();
    }
    
    init() {
        this.createIndicators();
        this.bindScrollEvent();
        this.bindFilterEvents();
    }
    
    createIndicators() {
        // Find the card body where we'll place indicators
        const cardBody = document.querySelector('.card-body');
        if (!cardBody) return;
        
        // Create loading indicator card
        const loadingCard = document.createElement('div');
        loadingCard.id = 'infinite-scroll-loading';
        loadingCard.className = 'card mx-4 mt-3 d-none border-primary';
        loadingCard.innerHTML = `
            <div class="card-body text-center py-4">
                <div class="d-flex justify-content-center align-items-center mb-3">
                    <div class="spinner-border text-primary me-3" role="status" style="width: 2.5rem; height: 2.5rem;">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <div>
                        <h6 class="text-primary mb-0">Loading more items...</h6>
                        <small class="text-muted">Please wait while we fetch more data</small>
                    </div>
                </div>
            </div>
        `;
        cardBody.appendChild(loadingCard);
        
        // Create no more data indicator card
        const noMoreCard = document.createElement('div');
        noMoreCard.id = 'infinite-scroll-complete';
        noMoreCard.className = 'card mx-4 mt-3 d-none';
        noMoreCard.innerHTML = `
            <div class="card-body text-center py-4">
                <div class="text-success mb-3">
                    <i class="fas fa-check-circle" style="font-size: 2rem;"></i>
                </div>
                <h6 class="text-success mb-0">All items loaded</h6>
                <small class="text-muted">You've reached the end of the list</small>
            </div>
        `;
        cardBody.appendChild(noMoreCard);
        
        // Create error indicator card
        const errorCard = document.createElement('div');
        errorCard.id = 'infinite-scroll-error';
        errorCard.className = 'card mx-4 mt-3 d-none';
        errorCard.innerHTML = `
            <div class="card-body text-center py-4">
                <div class="text-danger mb-3">
                    <i class="fas fa-exclamation-triangle" style="font-size: 2rem;"></i>
                </div>
                <h6 class="text-danger mb-2">Failed to load more items</h6>
                <small class="text-muted mb-3 d-block">Please check your connection and try again</small>
                <button class="btn btn-outline-primary btn-sm" onclick="window.adminInfiniteScroll.retryLoad()">
                    <i class="fas fa-redo me-1"></i> Retry
                </button>
            </div>
        `;
        cardBody.appendChild(errorCard);
    }
    
    bindScrollEvent() {
        let ticking = false;
        let lastScrollTop = 0;
        
        const handleScroll = () => {
            const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
            
            // Only process if scrolling down
            if (scrollTop > lastScrollTop) {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        if (this.shouldLoadMore()) {
                            this.loadMore();
                        }
                        ticking = false;
                    });
                    ticking = true;
                }
            }
            lastScrollTop = scrollTop;
        };
        
        // Use passive listener for better performance
        window.addEventListener('scroll', handleScroll, { passive: true });
        
        // Also check on resize
        window.addEventListener('resize', handleScroll, { passive: true });
        
        // Initial check in case content is short
        setTimeout(() => {
            if (this.shouldLoadMore()) {
                this.loadMore();
            }
        }, 100);
    }
    
    bindFilterEvents() {
        // Bind form submission events to reset pagination
        const forms = document.querySelectorAll('form[method="GET"]');
        forms.forEach(form => {
            form.addEventListener('submit', () => {
                this.resetPagination();
            });
        });
        
        // Bind filter change events with debouncing
        const filterInputs = document.querySelectorAll('select[name], input[name="search"]');
        filterInputs.forEach(input => {
            let debounceTimer;
            input.addEventListener('input', () => {
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(() => {
                    this.resetPagination();
                    this.applyFilters();
                }, 300);
            });
            
            input.addEventListener('change', () => {
                this.resetPagination();
                this.applyFilters();
            });
        });
    }
    
    shouldLoadMore() {
        if (this.isLoading || !this.hasMorePages) {
            return false;
        }
        
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        const windowHeight = window.innerHeight;
        const documentHeight = document.documentElement.scrollHeight;
        
        // More aggressive loading threshold for smoother experience
        // Load when user is within threshold OR when content height is less than 2 screen heights
        const isNearBottom = scrollTop + windowHeight >= documentHeight - this.scrollThreshold;
        const isContentShort = documentHeight < windowHeight * 2;
        
        return isNearBottom || isContentShort;
    }
    
    async loadMore() {
        if (this.isLoading || !this.hasMorePages) return;
        
        this.isLoading = true;
        
        // Show loading indicator immediately
        this.showLoadingIndicator();
        this.hideErrorIndicator();
        
        try {
            const nextPage = this.currentPage + 1;
            const url = this.buildUrl(nextPage);
            
            const response = await fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'Cache-Control': 'no-cache'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            if (data.success && data.data && data.data.length > 0) {
                // Use document fragment for better performance
                const fragment = document.createDocumentFragment();
                data.data.forEach((item, index) => {
                    const row = this.createRowElement(item);
                    fragment.appendChild(row);
                });
                
                const container = document.querySelector(this.container);
                if (container) {
                    container.appendChild(fragment);
                    
                    // Trigger delete form handlers for new rows
                    this.bindDeleteHandlers();
                }
                
                this.currentPage = data.pagination.current_page;
                this.hasMorePages = data.pagination.has_more;
                
                if (!this.hasMorePages) {
                    this.showCompleteIndicator();
                }
            } else {
                this.hasMorePages = false;
                this.showCompleteIndicator();
            }
        } catch (error) {
            console.error('Error loading more items:', error);
            this.showErrorIndicator();
        } finally {
            this.isLoading = false;
            this.hideLoadingIndicator();
        }
    }
    
    bindDeleteHandlers() {
        // Bind delete confirmation for newly added forms
        document.querySelectorAll('.delete-form').forEach(form => {
            if (!form.hasAttribute('data-bound')) {
                form.setAttribute('data-bound', 'true');
                form.addEventListener('submit', function(e) {
                    if (!confirm('Are you sure you want to delete this business?')) {
                        e.preventDefault();
                    }
                });
            }
        });
    }
    
    retryLoad() {
        this.hideErrorIndicator();
        this.loadMore();
    }
    
    buildUrl(page) {
        const params = new URLSearchParams(this.searchParams);
        params.set('page', page);
        return `${this.baseUrl}?${params.toString()}`;
    }
    
    createRowElement(item) {
        const row = document.createElement('tr');
        
        // Determine the type of data and create appropriate row
        // ⚠️ ORDER MATTERS: Check more specific properties first!
        if (item.subcategories_count !== undefined) {
            row.innerHTML = this.createCategoryRow(item);
        } else if (item.title !== undefined && (item.start_date !== undefined || item.created_by !== undefined)) {
            // Events have title + start_date or created_by
            row.innerHTML = this.createEventRow(item);
        } else if (item.category !== undefined && item.name !== undefined) {
            // Subcategories have category + name
            row.innerHTML = this.createSubcategoryRow(item);
        } else if (item.business_name !== undefined) {
            row.innerHTML = this.createBusinessRow(item);
        } else if (item.state !== undefined) {
            row.innerHTML = this.createCityRow(item);
        } else if (item.city !== undefined) {
            row.innerHTML = this.createAreaRow(item);
        } else if (item.email !== undefined) {
            row.innerHTML = this.createUserRow(item);
        }
        
        return row;
    }
    
    createCategoryRow(category) {
        const imageHtml = category.image 
            ? `<img src="${category.image}" alt="${category.name}" class="avatar avatar-sm rounded-circle me-2">`
            : `<div class="avatar avatar-sm rounded-circle bg-gradient-secondary me-2"><i class="fas fa-image text-white"></i></div>`;
        
        let iconHtml = `<span class="text-muted text-xs">No icon</span>`;
        if (category.icon_class) {
            const isFlaticonIcon = category.icon_class.startsWith('fi ');
            const iconClass = isFlaticonIcon ? category.icon_class : `fas fa-${category.icon_class}`;
            iconHtml = `<i class="${iconClass} text-primary" style="font-size: 1.2rem;" title="${category.icon_class}"></i>`;
        }
        
        return `
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">${category.id}</span>
            </td>
            <td>${imageHtml}</td>
            <td class="align-middle text-center">${iconHtml}</td>
            <td><p class="text-xs font-weight-bold mb-0">${category.name}</p></td>
            <td><p class="text-xs font-weight-bold mb-0">${category.subcategories_count}</p></td>
            <td class="align-middle">
                <a href="/admin/categories/${category.id}/edit" class="text-secondary font-weight-bold text-xs me-3">Edit</a>
                <form action="/admin/categories/${category.id}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this category?')">Delete</button>
                </form>
            </td>
        `;
    }
    
    createSubcategoryRow(subcategory) {
        const imageHtml = subcategory.image 
            ? `<img src="${subcategory.image}" alt="${subcategory.name}" class="avatar avatar-sm rounded-circle me-2">`
            : `<div class="avatar avatar-sm rounded-circle bg-gradient-secondary me-2"><i class="fas fa-image text-white"></i></div>`;
        
        let iconHtml = `<span class="text-muted text-xs">No icon</span>`;
        if (subcategory.icon_class) {
            const isFlaticonIcon = subcategory.icon_class.startsWith('fi ');
            const iconClass = isFlaticonIcon ? subcategory.icon_class : `fas fa-${subcategory.icon_class}`;
            iconHtml = `<i class="${iconClass} text-primary" style="font-size: 1.2rem;" title="${subcategory.icon_class}"></i>`;
        }
        
        return `
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">${subcategory.id}</span>
            </td>
            <td>${imageHtml}</td>
            <td class="align-middle text-center">${iconHtml}</td>
            <td><p class="text-xs font-weight-bold mb-0">${subcategory.name}</p></td>
            <td><p class="text-xs font-weight-bold mb-0">${subcategory.category.name}</p></td>
            <td class="align-middle">
                <a href="/admin/subcategories/${subcategory.id}/edit" class="text-secondary font-weight-bold text-xs me-3">Edit</a>
                <form action="/admin/subcategories/${subcategory.id}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this subcategory?')">Delete</button>
                </form>
            </td>
        `;
    }
    
    createBusinessRow(business) {
        // Match exact server-rendered layout - no images, exact structure
        const statusBadge = business.status === 'active' 
            ? '<span class="badge badge-sm bg-gradient-success">Active</span>'
            : (business.status === 'pending' 
                ? '<span class="badge badge-sm bg-gradient-warning">Pending</span>' 
                : '<span class="badge badge-sm bg-gradient-secondary">Inactive</span>');
        
        const subcategoryInfo = business.subcategory 
            ? `<p class="text-xs font-weight-bold mb-0">${business.subcategory.name}</p>
               <p class="text-xs text-secondary mb-0">${business.subcategory.category.name}</p>`
            : `<p class="text-xs text-secondary mb-0">No Category</p>`;
        
        const locationInfo = business.area 
            ? `<p class="text-xs font-weight-bold mb-0">${business.area.name}</p>
               <p class="text-xs text-secondary mb-0">${business.area.city.name}, ${business.area.city.state.name}</p>`
            : `<p class="text-xs text-secondary mb-0">No Location</p>`;
        
        const ownerInfo = business.owner 
            ? `<p class="text-xs font-weight-bold mb-0">${business.owner.name}</p>
               <p class="text-xs text-secondary mb-0">${business.owner.email}</p>`
            : `<p class="text-xs text-secondary mb-0">Unclaimed</p>`;
        
        const unclaimButton = business.owner 
            ? `<form action="/admin/businesses/${business.id}/unclaim" method="POST" class="d-inline">
                   <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                   <input type="hidden" name="_method" value="PUT">
                   <button type="submit" class="btn btn-link text-warning px-1 mb-0" title="Unclaim">
                       <i class="fas fa-unlink text-warning me-2"></i>
                   </button>
               </form>`
            : '';
        
        return `
            <td>
                <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">${business.business_name}</h6>
                        <p class="text-xs text-secondary mb-0">${business.phone || ''}</p>
                    </div>
                </div>
            </td>
            <td>${subcategoryInfo}</td>
            <td>${locationInfo}</td>
            <td>${statusBadge}</td>
            <td>${ownerInfo}</td>
            <td class="align-middle">
                <div class="d-flex">
                    <a href="/admin/businesses/${business.id}" class="btn btn-link text-info px-1 mb-0" title="View details">
                        <i class="fas fa-eye text-info me-2"></i>
                    </a>
                    <a href="/admin/businesses/${business.id}/edit" class="btn btn-link text-dark px-1 mb-0" title="Edit">
                        <i class="fas fa-pencil-alt text-dark me-2"></i>
                    </a>
                    ${unclaimButton}
                    <form action="/admin/businesses/${business.id}" method="POST" class="d-inline delete-form">
                        <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-link text-danger px-1 mb-0" title="Delete">
                            <i class="fas fa-trash text-danger me-2"></i>
                        </button>
                    </form>
                </div>
            </td>
        `;
    }
    
    createEventRow(event) {
        // Build image HTML with proper handling for banner images
        const imageHtml = event.banners && event.banners.length > 0 
            ? `<img src="/storage/${event.banners[0].image_path}" alt="${event.title || 'Event'}" class="avatar avatar-sm me-3">`
            : `<div class="avatar avatar-sm me-3 bg-gradient-secondary rounded-circle d-flex align-items-center justify-content-center">
                <i class="fas fa-calendar text-white"></i>
               </div>`;
        
        const statusBadge = this.getStatusBadge(event.status || 'pending');
        
        let eventDate, eventTime;
        try {
            const startDate = event.start_date ? new Date(event.start_date) : new Date(event.created_at || Date.now());
            eventDate = startDate.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
            eventTime = startDate.toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true });
        } catch (dateError) {
            console.error('Date formatting error:', dateError);
            eventDate = 'Invalid Date';
            eventTime = 'Invalid Time';
        }
        
        return `
            <td>
                <div class="d-flex px-2 py-1">
                    <div>${imageHtml}</div>
                    <div class="d-flex flex-column justify-content-center">
                            <h6 class="mb-0 text-sm">${event.title || 'Untitled Event'}</h6>
                            <p class="text-xs text-secondary mb-0">${event.description ? event.description.substring(0, 50) + '...' : 'No description available'}</p>
                        </div>
                    </div>
                </td>
                <td>
                    <p class="text-xs font-weight-bold mb-0">${eventDate}</p>
                    <p class="text-xs text-secondary mb-0">${eventTime}</p>
            </td>
                <td class="align-middle text-center">${statusBadge}</td>
                <td>
                    ${event.creator ? `
                        <p class="text-xs font-weight-bold mb-0">${event.creator.name || 'Unknown'}</p>
                        <p class="text-xs text-secondary mb-0">${event.creator.email || ''}</p>
                    ` : `
                        <p class="text-xs text-secondary mb-0">Unknown Creator</p>
                    `}
            </td>
            <td class="align-middle">
                    <a href="/admin/events/${event.id}/edit" class="text-secondary font-weight-bold text-xs me-3" data-toggle="tooltip" data-original-title="Edit event">
                        Edit
                    </a>
                    ${event.status === 'pending' ? `
                        <form action="/admin/events/${event.id}/approve" method="POST" class="d-inline">
                            <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                            <button type="submit" class="text-success font-weight-bold text-xs border-0 bg-transparent me-3" onclick="return confirm('Approve this event?')">Approve</button>
                        </form>
                    ` : ''}
                <form action="/admin/events/${event.id}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this event?')">Delete</button>
                </form>
            </td>
        `;
    }
    
    createCityRow(city) {
        return `
            <td class="align-middle" style="padding-left: 24px;">
                <input type="checkbox" class="select-item" value="${city.id}">
            </td>
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">${city.id}</span>
            </td>
            <td><p class="text-xs font-weight-bold mb-0">${city.name}</p></td>
            <td><p class="text-xs font-weight-bold mb-0">${city.state.name}</p></td>
            <td><p class="text-xs font-weight-bold mb-0">${city.areas_count}</p></td>
            <td class="align-middle">
                <a href="/admin/cities/${city.id}/edit" class="text-secondary font-weight-bold text-xs me-3">Edit</a>
                <form action="/admin/cities/${city.id}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this city?')">Delete</button>
                </form>
            </td>
        `;
    }
    
    createAreaRow(area) {
        return `
            <td class="align-middle" style="padding-left: 24px;">
                <input type="checkbox" class="select-item" value="${area.id}">
            </td>
            <td class="align-middle text-center">
                <span class="text-secondary text-xs font-weight-bold">${area.id}</span>
            </td>
            <td><p class="text-xs font-weight-bold mb-0">${area.name}</p></td>
            <td><p class="text-xs font-weight-bold mb-0">${area.city.name}</p></td>
            <td><p class="text-xs font-weight-bold mb-0">${area.city.state.name}</p></td>
            <td class="align-middle">
                <a href="/admin/areas/${area.id}/edit" class="text-secondary font-weight-bold text-xs me-3">Edit</a>
                <form action="/admin/areas/${area.id}" method="POST" class="d-inline">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="text-secondary font-weight-bold text-xs border-0 bg-transparent" onclick="return confirm('Are you sure you want to delete this area?')">Delete</button>
                </form>
            </td>
        `;
    }
    
    createUserRow(user) {
        const avatarBg = user.role === 'admin' ? 'dark' : (user.role === 'vendor' ? 'warning' : 'success');
        const statusBadge = user.role === 'vendor' 
            ? `<span class="badge bg-gradient-${user.status === 'approved' ? 'success' : (user.status === 'pending' ? 'warning' : 'danger')}">${user.status ? user.status.charAt(0).toUpperCase() + user.status.slice(1) : 'Pending'}</span>`
            : `<span class="badge bg-gradient-secondary">-</span>`;
        const businessInfo = user.business_name 
            ? `<p class="text-xs font-weight-bold mb-0">${user.business_name}</p>`
            : `<span class="text-xs text-secondary">Not a business</span>`;
        
        return `
            <td>
                <div class="d-flex px-3 py-1">
                    <div>
                        <div class="avatar avatar-sm me-3 bg-gradient-${avatarBg} rounded-circle">
                            <span class="text-white text-uppercase">${user.name.charAt(0)}</span>
                        </div>
                    </div>
                    <div class="d-flex flex-column justify-content-center">
                        <h6 class="mb-0 text-sm">${user.name}</h6>
                        <p class="text-xs text-secondary mb-0">${user.email}</p>
                        ${user.phone ? `<p class="text-xs text-secondary mb-0">${user.phone}</p>` : ''}
                    </div>
                </div>
            </td>
            <td>
                <span class="badge bg-gradient-${avatarBg}">
                    ${user.role.charAt(0).toUpperCase() + user.role.slice(1)}
                </span>
            </td>
            <td>${statusBadge}</td>
            <td>${businessInfo}</td>
            <td class="align-middle text-center text-sm">
                <p class="text-xs font-weight-bold mb-0">${new Date(user.created_at).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</p>
                <p class="text-xs text-secondary mb-0">${this.timeAgo(new Date(user.created_at))}</p>
            </td>
            <td class="align-middle text-center">
                <a href="/admin/users/${user.id}" class="btn btn-link text-dark px-2 mb-0" title="View Details">
                    <i class="fas fa-eye text-dark me-2"></i>
                </a>
                <a href="/admin/users/${user.id}/edit" class="btn btn-link text-dark px-2 mb-0" title="Edit User">
                    <i class="fas fa-pencil-alt text-dark me-2"></i>
                </a>
                <form action="/admin/users/${user.id}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                    <input type="hidden" name="_token" value="${this.getCsrfToken()}">
                    <input type="hidden" name="_method" value="DELETE">
                    <button type="submit" class="btn btn-link text-danger px-2 mb-0" title="Delete User">
                        <i class="fas fa-trash text-danger me-2"></i>
                    </button>
                </form>
            </td>
        `;
    }
    
    getStatusBadge(status) {
        const badges = {
            'active': '<span class="badge badge-sm bg-gradient-success">Active</span>',
            'inactive': '<span class="badge badge-sm bg-gradient-secondary">Inactive</span>',
            'pending': '<span class="badge badge-sm bg-gradient-warning">Pending</span>',
            'approved': '<span class="badge badge-sm bg-gradient-success">Approved</span>',
            'rejected': '<span class="badge badge-sm bg-gradient-danger">Rejected</span>'
        };
        return badges[status] || `<span class="badge badge-sm bg-gradient-secondary">${status}</span>`;
    }
    
    timeAgo(date) {
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);
        
        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`;
        if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`;
        if (diffInSeconds < 2592000) return `${Math.floor(diffInSeconds / 86400)}d ago`;
        if (diffInSeconds < 31536000) return `${Math.floor(diffInSeconds / 2592000)}mo ago`;
        return `${Math.floor(diffInSeconds / 31536000)}y ago`;
    }
    
    getCsrfToken() {
        const metaTag = document.querySelector('meta[name="csrf-token"]');
        return metaTag ? metaTag.getAttribute('content') : '';
    }
    
    showLoadingIndicator() {
        const indicator = document.getElementById('infinite-scroll-loading');
        if (indicator) {
            indicator.classList.remove('d-none');
        }
    }
    
    hideLoadingIndicator() {
        const indicator = document.getElementById('infinite-scroll-loading');
        if (indicator) {
            indicator.classList.add('d-none');
        }
    }
    
    showCompleteIndicator() {
        const indicator = document.getElementById('infinite-scroll-complete');
        if (indicator) {
            indicator.classList.remove('d-none');
        }
    }
    
    hideCompleteIndicator() {
        const indicator = document.getElementById('infinite-scroll-complete');
        if (indicator) {
            indicator.classList.add('d-none');
        }
    }
    
    showErrorIndicator() {
        const indicator = document.getElementById('infinite-scroll-error');
        if (indicator) {
            indicator.classList.remove('d-none');
        }
    }
    
    hideErrorIndicator() {
        const indicator = document.getElementById('infinite-scroll-error');
        if (indicator) {
            indicator.classList.add('d-none');
        }
    }
    
    resetPagination() {
        this.currentPage = 1;
        this.hasMorePages = true;
        this.isLoading = false;
        
        // Hide all indicators
        this.hideLoadingIndicator();
        this.hideCompleteIndicator();
        this.hideErrorIndicator();
    }
    
    applyFilters() {
        // Update search params from current form values
        const forms = document.querySelectorAll('form[method="GET"]');
        if (forms.length > 0) {
            const formData = new FormData(forms[0]);
            this.searchParams = new URLSearchParams();
            
            for (let [key, value] of formData.entries()) {
                if (value.trim() !== '') {
                    this.searchParams.set(key, value);
                }
            }
        }
    }
}

// Initialize infinite scroll when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on an admin page that should have infinite scroll
    const adminPages = [
        '/admin/categories',
        '/admin/subcategories', 
        '/admin/businesses',
        '/admin/events',
        '/admin/cities',
        '/admin/areas',
        '/admin/users'
    ];
    
    const currentPath = window.location.pathname;
    const shouldInitialize = adminPages.some(page => currentPath.startsWith(page));
    
    if (shouldInitialize) {
        // Store instance globally for retry functionality
        window.adminInfiniteScroll = new AdminInfiniteScroll();
    }
}); 