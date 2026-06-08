/**
 * Comprehensive City Selection Fix for Youth Central
 * Handles city selection across all pages consistently
 */

$(document).ready(function() {
    // Global city selection initialization
    function initializeCitySelection() {
        // Check if elements exist
        if ($('.cities-list a').length === 0) {
            return;
        }
        
        // Remove all existing event handlers to prevent conflicts
        $('.cities-list a').off('click');
        $('.search-cities a').off('click');
        $('body').off('click', '.search-cities a');
        
        // Add our comprehensive event handler
        $('.cities-list a, .search-cities a').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var $this = $(this);
            var cityId = $this.attr('data-city-id');
            
            // Handle city selection (ignore "more" cities)
            if (cityId && cityId !== 'more') {
                // Get the parent form or search container
                var container = $this.closest('.hero-search, .search-popup, form');
                if (container.length === 0) {
                    container = $this.closest('.container-fluid');
                }
                
                // Remove current class from all links in this container
                container.find('.cities-list a, .search-cities a').removeClass('current');
                
                // Add current class to clicked link
                $this.addClass('current');
                
                // Set the city ID in the hidden input
                var hiddenInput = container.find('.chosen-city');
                if (hiddenInput.length > 0) {
                    hiddenInput.val(cityId);
                }
                
                // Close the cities dropdown
                $('.hero-header').removeClass('open-cities-list');
                container.closest('.hero-header').removeClass('open-cities-list');
            }
            
            return false;
        });
        
        // Handle cities dropdown toggle
        $('.search-cities-toggle').off('click').on('click', function(e) {
            e.preventDefault();
            var heroHeader = $(this).closest('.hero-header');
            heroHeader.addClass('open-cities-list');
            heroHeader.find('.search-cities').css({top: '-1000px'});
            heroHeader.find('.search-cities').stop().animate({top: [0, 'easeOutExpo']}, {duration: 1500});
        });
        
        // Handle form submission
        $('form').off('submit.cityfix').on('submit.cityfix', function(e) {
            var cityValue = $(this).find('.chosen-city').val();
            var query = $(this).find('input[name="query"]').val();
            
            // Let the form submit normally
            return true;
        });
    }
    
    // Try multiple times to ensure it works across all scenarios
    setTimeout(initializeCitySelection, 100);
    setTimeout(initializeCitySelection, 500);
    setTimeout(initializeCitySelection, 1000);
    setTimeout(initializeCitySelection, 2000);
    
    // Also try when window is fully loaded
    $(window).on('load', function() {
        setTimeout(initializeCitySelection, 100);
        setTimeout(initializeCitySelection, 500);
    });
    
    // Re-initialize when new content is loaded (for AJAX scenarios)
    $(document).on('DOMNodeInserted', function() {
        setTimeout(initializeCitySelection, 100);
    });
}); 