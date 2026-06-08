@props(['name' => 'icon_class', 'value' => '', 'required' => false])

@php
$icons = [
    // Business & Commerce
    'store', 'cart-shopping', 'bag-shopping', 'cash-register', 'credit-card', 'money-bill-wave', 'coins', 'receipt', 'barcode', 'tag', 'tags', 'percent', 'dollar-sign', 'euro-sign', 'pound-sign', 'yen-sign', 'handshake', 'briefcase', 'suitcase', 'chart-line', 'chart-column', 'chart-pie', 'calculator', 'scale-balanced', 'warehouse', 'truck', 'truck-fast', 'box', 'boxes-stacked', 'boxes-packing',
    
    // Food & Dining
    'utensils', 'pizza-slice', 'burger', 'hotdog', 'ice-cream', 'mug-saucer', 'wine-glass', 'beer-mug-empty', 'martini-glass', 'apple-whole', 'carrot', 'pepper-hot', 'bread-slice', 'cheese', 'fish', 'drumstick-bite', 'cake-candles', 'cookie-bite', 'candy-cane', 'lemon', 'seedling', 'leaf',
    
    // Health & Medical
    'hospital', 'user-doctor', 'stethoscope', 'heart-pulse', 'heart', 'pills', 'syringe', 'thermometer', 'bandage', 'kit-medical', 'truck-medical', 'wheelchair', 'tooth', 'eye', 'brain', 'lungs', 'dna', 'microscope', 'x-ray', 'notes-medical', 'prescription-bottle', 'prescription-bottle-medical',
    
    // Education & Learning
    'graduation-cap', 'school', 'building-columns', 'book', 'book-open', 'bookmark', 'building-user', 'pencil', 'pen', 'highlighter', 'eraser', 'ruler', 'calculator', 'microscope', 'flask', 'atom', 'globe', 'map', 'chalkboard', 'chalkboard-user', 'user-graduate', 'certificate', 'award', 'medal', 'trophy',
    
    // Sports & Recreation
    'football', 'basketball', 'baseball', 'tennis-ball', 'golf-ball-tee', 'hockey-puck', 'bowling-ball', 'table-tennis-paddle-ball', 'volleyball', 'person-swimming', 'bicycle', 'person-running', 'person-walking', 'dumbbell', 'weight-hanging', 'skating', 'person-skiing', 'person-snowboarding', 'mountain', 'campground', 'fire', 'binoculars',
    
    // Technology & Electronics
    'laptop', 'desktop', 'tablet-screen-button', 'mobile-screen-button', 'tv', 'camera', 'video', 'headphones', 'microphone', 'keyboard', 'computer-mouse', 'gamepad', 'wifi', 'bluetooth', 'usb', 'hard-drive', 'memory', 'microchip', 'robot', 'satellite', 'satellite-dish', 'tower-broadcast',
    
    // Transportation & Automotive
    'car', 'taxi', 'bus', 'truck', 'motorcycle', 'bicycle', 'plane', 'helicopter', 'ship', 'train-subway', 'train', 'train-tram', 'gas-pump', 'oil-can', 'wrench', 'toolbox', 'gear', 'gears', 'circle-radiation', 'road', 'traffic-light', 'square-parking',
    
    // Home & Garden
    'house', 'house-user', 'bed', 'couch', 'chair', 'table-cells', 'door-open', 'door-closed', 'key', 'lock', 'unlock', 'lightbulb', 'fan', 'temperature-half', 'shower', 'toilet', 'bath', 'faucet', 'hammer', 'screwdriver', 'paintbrush', 'paint-roller', 'seedling', 'tree', 'spa', 'leaf',
    
    // Entertainment & Media
    'film', 'video', 'camera', 'music', 'headphones', 'microphone', 'guitar', 'drum', 'piano', 'masks-theater', 'ticket', 'popcorn', 'dice', 'chess', 'puzzle-piece', 'gamepad', 'cards-blank', 'wand-magic', 'star', 'award',
    
    // Fashion & Beauty
    'shirt', 'hat-cowboy', 'glasses', 'sunglasses', 'ring', 'gem', 'crown', 'shoe-prints', 'socks', 'mitten', 'umbrella', 'handbag', 'bag-shopping', 'lipstick', 'mirror', 'scissors', 'spray-can', 'palette',
    
    // Professional Services
    'briefcase', 'user-tie', 'handshake', 'scale-balanced', 'gavel', 'file-contract', 'stamp', 'signature', 'clipboard', 'folder', 'file-lines', 'box-archive', 'calculator', 'chart-line', 'presentation-screen', 'bullhorn', 'phone', 'envelope', 'fax',
    
    // Pets & Animals
    'dog', 'cat', 'fish', 'dove', 'horse', 'frog', 'spider', 'bug', 'dove', 'crow', 'feather', 'paw', 'bone', 'heart', 'house', 'store',
    
    // Travel & Tourism
    'plane', 'suitcase', 'map', 'compass', 'route', 'bed', 'bed', 'camera', 'binoculars', 'mountain', 'tree', 'umbrella-beach', 'ship', 'anchor', 'globe', 'passport', 'ticket', 'cart-flatbed',
    
    // Arts & Crafts
    'palette', 'paintbrush', 'pencil', 'pen', 'marker', 'highlighter', 'scissors', 'cut', 'paste', 'clone', 'wand-magic', 'wand-sparkles', 'star', 'shapes', 'circle', 'square', 'triangle', 'heart', 'diamond',
    
    // Finance & Banking
    'building-columns', 'piggy-bank', 'wallet', 'credit-card', 'money-bill', 'coins', 'dollar-sign', 'chart-line', 'calculator', 'receipt', 'file-invoice', 'handshake', 'scale-balanced', 'vault', 'vault',
    
    // Communication & Social
    'phone', 'mobile-screen-button', 'envelope', 'comment', 'comments', 'message', 'video', 'microphone', 'bullhorn', 'rss', 'wifi', 'signal', 'satellite', 'tower-broadcast', 'users', 'user-group', 'handshake',
    
    // Security & Safety
    'shield-halved', 'lock', 'unlock', 'key', 'user-shield', 'eye', 'eye-slash', 'fingerprint', 'id-card', 'camera', 'video', 'bell', 'triangle-exclamation', 'fire-extinguisher', 'hard-hat',
    
    // Weather & Environment
    'sun', 'moon', 'cloud', 'cloud-rain', 'cloud-snow', 'bolt', 'rainbow', 'temperature-half', 'wind', 'leaf', 'tree', 'seedling', 'globe', 'recycle', 'solar-panel', 'droplet',
    
    // General Icons
    'star', 'heart', 'thumbs-up', 'thumbs-down', 'check', 'xmark', 'plus', 'minus', 'arrow-right', 'arrow-left', 'arrow-up', 'arrow-down', 'magnifying-glass', 'filter', 'sort', 'list', 'table-cells', 'list-ul', 'bookmark', 'flag', 'bell', 'clock', 'calendar', 'location-dot', 'circle-info', 'circle-question', 'circle-exclamation'
];

// Remove duplicates and sort
$icons = array_unique($icons);
sort($icons);
@endphp

<div class="icon-picker-container">
    <div class="form-group">
        <label for="{{ $name ?? 'icon_class' }}" class="form-label">{{ $label ?? 'Select Icon' }}</label>
        <div class="icon-picker-wrapper">
            <!-- Selected Icon Display -->
            <div class="selected-icon-display" onclick="toggleIconDropdown()">
                <i id="selected-icon-preview" class="fa-solid fa-{{ old($name ?? 'icon_class', $value ?? 'bookmark') }}"></i>
                <span id="selected-icon-name">{{ old($name ?? 'icon_class', $value ?? 'bookmark') }}</span>
                <i class="fa-solid fa-chevron-down dropdown-arrow"></i>
            </div>
            
            <!-- Hidden Input -->
            <input type="hidden" 
                   name="{{ $name ?? 'icon_class' }}" 
                   id="{{ $name ?? 'icon_class' }}" 
                   value="{{ old($name ?? 'icon_class', $value ?? 'bookmark') }}">
            
            <!-- Icon Dropdown -->
            <div class="icon-dropdown" id="icon-dropdown">
                <!-- Search Box -->
                <div class="icon-search-box">
                    <input type="text" 
                           id="icon-search" 
                           placeholder="Search icons..." 
                           onkeyup="filterIcons()">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                
                <!-- Icons Grid -->
                <div class="icons-grid" id="icons-grid">
                    @foreach($icons as $icon)
                    <div class="icon-option" 
                         data-icon="{{ $icon }}" 
                         onclick="selectIcon('{{ $icon }}')"
                         title="{{ ucwords(str_replace('-', ' ', $icon)) }}">
                        <i class="fa-solid fa-{{ $icon }}"></i>
                        <span>{{ $icon }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        @error($name ?? 'icon_class')
            <div class="text-danger small mt-1">{{ $message }}</div>
        @enderror
    </div>
</div>

<style>
.icon-picker-container {
    position: relative;
    margin-bottom: 1rem;
}

.icon-picker-wrapper {
    position: relative;
}

.selected-icon-display {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    min-height: 48px;
}

.selected-icon-display:hover {
    border-color: #5e72e4;
    box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
}

.selected-icon-display i:first-child {
    font-size: 1.2rem;
    margin-right: 0.75rem;
    color: #5e72e4;
    width: 20px;
    text-align: center;
}

.selected-icon-display span {
    flex: 1;
    font-weight: 500;
    color: #344767;
}

.dropdown-arrow {
    font-size: 0.8rem;
    color: #67748e;
    transition: transform 0.2s ease;
}

.selected-icon-display.active .dropdown-arrow {
    transform: rotate(180deg);
}

.icon-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #d2d6da;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    max-height: 400px;
    display: none;
    overflow: hidden;
}

.icon-dropdown.show {
    display: block;
}

.icon-search-box {
    position: relative;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
}

.icon-search-box input {
    width: 100%;
    padding: 0.5rem 2.5rem 0.5rem 1rem;
    border: 1px solid #d2d6da;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    outline: none;
    transition: border-color 0.2s ease;
}

.icon-search-box input:focus {
    border-color: #5e72e4;
    box-shadow: 0 0 0 0.2rem rgba(94, 114, 228, 0.25);
}

.icon-search-box i {
    position: absolute;
    right: 1.5rem;
    top: 50%;
    transform: translateY(-50%);
    color: #67748e;
    font-size: 0.875rem;
}

.icons-grid {
    max-height: 300px;
    overflow-y: auto;
    padding: 0.5rem;
}

.icon-option {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    cursor: pointer;
    border-radius: 0.375rem;
    transition: all 0.2s ease;
    margin-bottom: 0.25rem;
}

.icon-option:hover {
    background-color: #f8f9fa;
    transform: translateX(4px);
}

.icon-option.selected {
    background-color: #5e72e4;
    color: white;
}

.icon-option i {
    font-size: 1.1rem;
    margin-right: 0.75rem;
    width: 20px;
    text-align: center;
    color: #5e72e4;
}

.icon-option.selected i {
    color: white;
}

.icon-option span {
    font-size: 0.875rem;
    font-weight: 500;
    color: #344767;
}

.icon-option.selected span {
    color: white;
}

/* Scrollbar Styling */
.icons-grid::-webkit-scrollbar {
    width: 6px;
}

.icons-grid::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
}

.icons-grid::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
}

.icons-grid::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive */
@media (max-width: 768px) {
    .icon-dropdown {
        max-height: 300px;
    }
    
    .icons-grid {
        max-height: 200px;
    }
    
    .icon-option {
        padding: 0.5rem;
    }
}
</style>

<script>
function toggleIconDropdown() {
    const dropdown = document.getElementById('icon-dropdown');
    const display = document.querySelector('.selected-icon-display');
    
    if (dropdown.classList.contains('show')) {
        dropdown.classList.remove('show');
        display.classList.remove('active');
    } else {
        dropdown.classList.add('show');
        display.classList.add('active');
        
        // Focus search input
        setTimeout(() => {
            document.getElementById('icon-search').focus();
        }, 100);
    }
}

function selectIcon(iconName) {
    // Update hidden input
    document.getElementById('{{ $name ?? 'icon_class' }}').value = iconName;
    
    // Update preview
    document.getElementById('selected-icon-preview').className = 'fa-solid fa-' + iconName;
    document.getElementById('selected-icon-name').textContent = iconName;
    
    // Update selected state
    document.querySelectorAll('.icon-option').forEach(option => {
        option.classList.remove('selected');
    });
    document.querySelector(`[data-icon="${iconName}"]`).classList.add('selected');
    
    // Close dropdown
    document.getElementById('icon-dropdown').classList.remove('show');
    document.querySelector('.selected-icon-display').classList.remove('active');
}

function filterIcons() {
    const searchTerm = document.getElementById('icon-search').value.toLowerCase();
    const iconOptions = document.querySelectorAll('.icon-option');
    
    iconOptions.forEach(option => {
        const iconName = option.getAttribute('data-icon').toLowerCase();
        const iconTitle = option.getAttribute('title').toLowerCase();
        
        if (iconName.includes(searchTerm) || iconTitle.includes(searchTerm)) {
            option.style.display = 'flex';
        } else {
            option.style.display = 'none';
        }
    });
}

// Close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const container = document.querySelector('.icon-picker-container');
    if (container && !container.contains(event.target)) {
        document.getElementById('icon-dropdown').classList.remove('show');
        document.querySelector('.selected-icon-display').classList.remove('active');
    }
});

// Initialize selected state
document.addEventListener('DOMContentLoaded', function() {
    const currentValue = document.getElementById('{{ $name ?? 'icon_class' }}').value;
    if (currentValue) {
        const selectedOption = document.querySelector(`[data-icon="${currentValue}"]`);
        if (selectedOption) {
            selectedOption.classList.add('selected');
        }
    }
});

// Handle keyboard navigation
document.getElementById('icon-search').addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        toggleIconDropdown();
    }
});
</script> 