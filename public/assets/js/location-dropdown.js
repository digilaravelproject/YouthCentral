/**
 * Location Dropdown Helper
 * 
 * This script handles the dynamic loading of cities and areas based on state/city selection.
 * It provides a consistent interface for all forms that use location dropdowns.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Helper function to initialize location dropdowns
    function initLocationDropdowns(stateSelectId, citySelectId, areaSelectId) {
        const stateSelect = document.getElementById(stateSelectId);
        const citySelect = document.getElementById(citySelectId);
        const areaSelect = document.getElementById(areaSelectId);
        
        if (!stateSelect || !citySelect || !areaSelect) return;
        
        // State change handler
        stateSelect.addEventListener('change', function() {
            // Reset city and area dropdowns
            citySelect.innerHTML = '<option value="">Select City</option>';
            citySelect.disabled = !this.value;
            
            areaSelect.innerHTML = '<option value="">Select Area</option>';
            areaSelect.disabled = true;
            
            if (this.value) {
                // Get cities for the selected state
                fetchCities(this.value)
                    .then(cities => {
                        if (cities && Array.isArray(cities)) {
                            cities.forEach(city => {
                                const option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.appendChild(option);
                            });
                        } else {
                            console.error('Invalid cities data received:', cities);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching cities:', error);
                    });
            }
        });
        
        // City change handler
        citySelect.addEventListener('change', function() {
            // Reset area dropdown
            areaSelect.innerHTML = '<option value="">Select Area</option>';
            areaSelect.disabled = !this.value;
            
            if (this.value) {
                // Get areas for the selected city
                fetchAreas(this.value)
                    .then(areas => {
                        if (areas && Array.isArray(areas)) {
                            areas.forEach(area => {
                                const option = document.createElement('option');
                                option.value = area.id;
                                option.textContent = area.name;
                                areaSelect.appendChild(option);
                            });
                        } else {
                            console.error('Invalid areas data received:', areas);
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching areas:', error);
                    });
            }
        });
    }
    
    // API fetch functions with enhanced error handling
    function fetchCities(stateId) {
        return fetch(`/api/states/${stateId}/cities`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('Fetch cities error:', error);
                // Try alternative route as fallback
                return fetch(`/states/${stateId}/cities`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error in fallback! Status: ${response.status}`);
                        }
                        return response.json();
                    });
            });
    }
    
    function fetchAreas(cityId) {
        return fetch(`/api/cities/${cityId}/areas`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('Fetch areas error:', error);
                // Try alternative route as fallback
                return fetch(`/cities/${cityId}/areas`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error in fallback! Status: ${response.status}`);
                        }
                        return response.json();
                    });
            });
    }
    
    // Initialize dropdowns on pages that have them
    // Vendor event create/edit page
    initLocationDropdowns('state_id', 'city_id', 'area_id');
}); 