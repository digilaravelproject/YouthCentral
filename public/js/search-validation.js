// Search form validation - Prevent empty searches
document.addEventListener('DOMContentLoaded', function() {
    
    // Function to validate and toggle submit button state
    function validateSearchForm(form) {
        const queryInput = form.querySelector('input[name="query"]');
        const submitButton = form.querySelector('input[type="submit"]');
        const searchIcon = form.querySelector('.hero-search-icon');
        
        if (!queryInput || !submitButton) return;
        
        // Function to check and update button state
        function updateSubmitState() {
            const hasQuery = queryInput.value.trim().length > 0;
            
            if (hasQuery) {
                submitButton.disabled = false;
                submitButton.style.opacity = '1';
                submitButton.style.cursor = 'pointer';
                if (searchIcon) {
                    searchIcon.style.opacity = '1';
                }
            } else {
                submitButton.disabled = true;
                submitButton.style.opacity = '0.5';
                submitButton.style.cursor = 'not-allowed';
                if (searchIcon) {
                    searchIcon.style.opacity = '0.5';
                }
            }
        }
        
        // Initial state check
        updateSubmitState();
        
        // Listen for input changes
        queryInput.addEventListener('input', updateSubmitState);
        queryInput.addEventListener('keyup', updateSubmitState);
        queryInput.addEventListener('paste', function() {
            setTimeout(updateSubmitState, 10);
        });
        
        // Prevent form submission if query is empty
        form.addEventListener('submit', function(e) {
            const queryValue = queryInput.value.trim();
            if (queryValue.length === 0) {
                e.preventDefault();
                e.stopPropagation();
                
                // Show visual feedback
                queryInput.style.borderColor = '#dc3545';
                queryInput.placeholder = 'Please enter a search term';
                
                // Reset border color after 3 seconds
                setTimeout(function() {
                    queryInput.style.borderColor = '';
                    queryInput.placeholder = queryInput.getAttribute('data-placeholder') || 'Search...';
                }, 3000);
                
                return false;
            }
        });
    }
    
    // Find and validate all search forms on the page
    const searchForms = document.querySelectorAll('.hero-search form, .search-popup form');
    searchForms.forEach(function(form) {
        validateSearchForm(form);
    });
    
    // Also handle any forms that might be dynamically loaded
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            if (mutation.type === 'childList') {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        const newSearchForms = node.querySelectorAll ? node.querySelectorAll('.hero-search form, .search-popup form') : [];
                        newSearchForms.forEach(function(form) {
                            validateSearchForm(form);
                        });
                    }
                });
            }
        });
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
}); 