/**
 * Location Dropdown Helper
 * 
 * This script handles the dynamic loading of cities and areas based on state/city selection.
 * It provides a consistent interface for all forms that use location dropdowns,
 * including Select2 integration and dynamic spinner loaders.
 */

document.addEventListener('DOMContentLoaded', function() {
    // Helper function to initialize location dropdowns
    function initLocationDropdowns(stateSelectId, citySelectId, areaSelectId) {
        var stateSelect = document.getElementById(stateSelectId);
        var citySelect = document.getElementById(citySelectId);
        var areaSelect = document.getElementById(areaSelectId);
        
        if (!stateSelect || !citySelect || !areaSelect) return;
        
        // Initialize Select2 with Bootstrap 5 theme if jQuery and Select2 are loaded
        if (window.jQuery && jQuery().select2) {
            var $state = $(stateSelect).select2({ theme: 'bootstrap-5', width: '100%' });
            var $city = $(citySelect).select2({ theme: 'bootstrap-5', width: '100%' });
            var $area = $(areaSelect).select2({ theme: 'bootstrap-5', width: '100%' });

            // State change handler via jQuery (captures both Select2 UI changes and native changes)
            $state.on('change', function() {
                var stateId = $(this).val();
                
                // Remove any existing loaders
                $('.city-loader, .area-loader').remove();
                
                // Reset city and area dropdowns
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = !stateId;
                
                areaSelect.innerHTML = '<option value="">Select Area</option>';
                areaSelect.disabled = true;
                
                $city.trigger('change');
                $area.trigger('change');
                
                if (stateId) {
                    // Add loader spinner next to City label
                    var cityLabel = document.querySelector('label[for="' + citySelectId + '"]');
                    if (cityLabel) {
                        var loader = document.createElement('span');
                        loader.className = 'city-loader ms-2 text-primary';
                        loader.style.fontSize = '0.8rem';
                        loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                        cityLabel.appendChild(loader);
                    }
                    citySelect.disabled = true;
                    $city.trigger('change');
                    
                    fetchCities(stateId)
                        .then(cities => {
                            citySelect.innerHTML = '<option value="">Select City</option>';
                            if (cities && Array.isArray(cities)) {
                                cities.forEach(city => {
                                    var option = document.createElement('option');
                                    option.value = city.id;
                                    option.textContent = city.name;
                                    citySelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching cities:', error);
                        })
                        .finally(() => {
                            var loader = document.querySelector('.city-loader');
                            if (loader) loader.remove();
                            citySelect.disabled = false;
                            $city.trigger('change');
                        });
                }
            });

            // City change handler via jQuery
            $city.on('change', function() {
                var cityId = $(this).val();
                
                // Remove any existing area loaders
                $('.area-loader').remove();
                
                // Reset area dropdown
                areaSelect.innerHTML = '<option value="">Select Area</option>';
                areaSelect.disabled = !cityId;
                
                $area.trigger('change');
                
                if (cityId) {
                    // Add loader spinner next to Area label
                    var areaLabel = document.querySelector('label[for="' + areaSelectId + '"]');
                    if (areaLabel) {
                        var loader = document.createElement('span');
                        loader.className = 'area-loader ms-2 text-primary';
                        loader.style.fontSize = '0.8rem';
                        loader.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
                        areaLabel.appendChild(loader);
                    }
                    areaSelect.disabled = true;
                    $area.trigger('change');
                    
                    fetchAreas(cityId)
                        .then(areas => {
                            areaSelect.innerHTML = '<option value="">Select Area</option>';
                            if (areas && Array.isArray(areas)) {
                                areas.forEach(area => {
                                    var option = document.createElement('option');
                                    option.value = area.id;
                                    option.textContent = area.name;
                                    areaSelect.appendChild(option);
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error fetching areas:', error);
                        })
                        .finally(() => {
                            var loader = document.querySelector('.area-loader');
                            if (loader) loader.remove();
                            areaSelect.disabled = false;
                            $area.trigger('change');
                        });
                }
            });

        } else {
            // Fallback for native event listeners if jQuery is not loaded
            stateSelect.addEventListener('change', function() {
                var stateId = this.value;
                citySelect.innerHTML = '<option value="">Select City</option>';
                citySelect.disabled = !stateId;
                areaSelect.innerHTML = '<option value="">Select Area</option>';
                areaSelect.disabled = true;
                if (stateId) {
                    fetchCities(stateId).then(cities => {
                        citySelect.innerHTML = '<option value="">Select City</option>';
                        if (cities && Array.isArray(cities)) {
                            cities.forEach(city => {
                                var option = document.createElement('option');
                                option.value = city.id;
                                option.textContent = city.name;
                                citySelect.appendChild(option);
                            });
                        }
                    });
                }
            });

            citySelect.addEventListener('change', function() {
                var cityId = this.value;
                areaSelect.innerHTML = '<option value="">Select Area</option>';
                areaSelect.disabled = !cityId;
                if (cityId) {
                    fetchAreas(cityId).then(areas => {
                        areaSelect.innerHTML = '<option value="">Select Area</option>';
                        if (areas && Array.isArray(areas)) {
                            areas.forEach(area => {
                                var option = document.createElement('option');
                                option.value = area.id;
                                option.textContent = area.name;
                                areaSelect.appendChild(option);
                            });
                        }
                    });
                }
            });
        }
    }
    
    // API fetch functions with enhanced error handling
    function fetchCities(stateId) {
        return fetch('/api/states/' + stateId + '/cities')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! Status: ' + response.status);
                }
                return response.json();
            })
            .catch(error => {
                console.error('Fetch cities error:', error);
                // Try alternative route as fallback
                return fetch('/states/' + stateId + '/cities')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error in fallback! Status: ' + response.status);
                        }
                        return response.json();
                    });
            });
    }
    
    function fetchAreas(cityId) {
        return fetch('/api/cities/' + cityId + '/areas')
            .then(response => {
                if (!response.ok) {
                    throw new Error('HTTP error! Status: ' + response.status);
                }
                return response.json();
            })
            .catch(error => {
                console.error('Fetch areas error:', error);
                // Try alternative route as fallback
                return fetch('/cities/' + cityId + '/areas')
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('HTTP error in fallback! Status: ' + response.status);
                        }
                        return response.json();
                    });
            });
    }
    
    // Initialize dropdowns on pages that have them
    initLocationDropdowns('state_id', 'city_id', 'area_id');
});