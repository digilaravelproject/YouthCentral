<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'image', 'slug', 'icon_class'];
    
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['full_icon_class'];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (!$category->slug) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    public function subcategories()
    {
        return $this->hasMany(Subcategory::class);
    }

    /**
     * Define a hasManyThrough relationship to access businesses through subcategories.
     */
    public function businesses()
    {
        return $this->hasManyThrough(Business::class, Subcategory::class);
    }

    /**
     * Get the icon class for the category.
     * 
     * @param string|null $value
     * @return string
     */
    public function getIconClassAttribute($value)
    {
        return $value ?? $this->getDefaultIconClass();
    }

    /**
     * Get the default icon class based on category name.
     * 
     * @return string
     */
    protected function getDefaultIconClass()
    {
        $categoryIcons = [
            'Sports' => 'trophy',
            'Academics' => 'graduation-hat',
            'Arts' => 'palette',
            'Medical' => 'heart-pulse'
        ];

        return $categoryIcons[$this->name] ?? 'icon-default';
    }
    
    /**
     * Get the full icon class with 'icon-' prefix for direct use in HTML.
     * 
     * @return string
     */
    public function getFullIconClassAttribute()
    {
        return 'icon-' . $this->icon_class;
    }

    /**
     * Get formatted icon class for use in templates.
     * 
     * @return string
     */
    public function getFormattedIconClass()
    {
        // If icon_class is set, check if it's a Flaticon or Font Awesome icon
        if ($this->icon_class) {
            // Check if it's already a Flaticon class (starts with 'fi ')
            if (strpos($this->icon_class, 'fi ') === 0) {
                return $this->icon_class;
            }
            
            // Check if it's a full Font Awesome class (starts with 'fa')
            if (strpos($this->icon_class, 'fa') === 0) {
                return $this->icon_class;
            }
            
            // Map common icon names to Flaticon equivalents
            $flaticonMap = [
                'book' => 'fi fi-rr-book-alt',
                'futbol' => 'fi fi-rr-football-player',
                'baseball-ball' => 'fi fi-rs-cricket',
                'swimmer' => 'fi fi-rs-swimmer',
                'chalkboard-teacher' => 'fi fi-rr-workshop',
                'laptop-code' => 'fi fi-rs-computer',
                'theater-masks' => 'fi fi-ts-theater-masks',
                'music' => 'fi fi-rs-music-alt',
                'baby' => 'fi fi-rr-child',
                'chess' => 'fi fi-ts-chess',
                'table-tennis' => 'fi fi-rr-ping-pong',
                'fist-raised' => 'fi fi-tr-uniform-martial-arts',
                'atom' => 'fi fi-rr-microscope',
                'calculator' => 'fi fi-sr-calculator-simple',
                'book-open' => 'fi fi-rr-book-alt',
                'user-md' => 'fi fi-rr-stethoscope',
                'comments' => 'fi fi-rr-meeting',
                'palette' => 'fi fi-rr-palette'
            ];
            
            // If we have a Flaticon equivalent, use it
            if (isset($flaticonMap[$this->icon_class])) {
                return $flaticonMap[$this->icon_class];
            }
            
            // Otherwise, assume it's a Font Awesome 6 icon name and add the prefix
            return 'fas fa-' . $this->icon_class;
        }
        
        // Fallback to default icon
        return 'fas fa-' . $this->getDefaultIconClass();
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }
}
