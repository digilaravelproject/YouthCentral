@if(is_object($subcategory) && method_exists($subcategory, 'getFormattedIconClass'))
{{ $subcategory->getFormattedIconClass() }} 
@elseif(is_object($subcategory) && isset($subcategory->icon_class))
fas fa-{{ $subcategory->icon_class }}
@else
fas fa-bookmark
@endif 