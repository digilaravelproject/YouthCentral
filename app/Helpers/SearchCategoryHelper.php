<?php

namespace App\Helpers;

use App\Models\Subcategory;

class SearchCategoryHelper
{
    /**
     * Get hero search categories with Flaticon icons
     * 
     * @return array
     */
    public static function getHeroSearchCategories()
    {
        // Map custom categories to real subcategories with their icons and routes
        $customCategoryMappings = [
            'Tuitions' => ['icon' => 'fi fi-rr-book-alt', 'search_terms' => ['tuition', 'tutoring', 'tutor']],
            'Football/Soccer' => ['icon' => 'fi fi-rr-football-player', 'search_terms' => ['football', 'soccer']],
            'Cricket' => ['icon' => 'fi fi-rs-cricket', 'search_terms' => ['cricket']],
            'Swimming' => ['icon' => 'fi fi-rs-swimmer', 'search_terms' => ['swimming', 'swim']],
            'Coaching Classes' => ['icon' => 'fi fi-rr-workshop', 'search_terms' => ['coaching', 'classes', 'training']],
            'Computers/AI' => ['icon' => 'fi fi-rs-computer', 'search_terms' => ['computer', 'ai', 'programming', 'coding']],
            'Theatre/Acting' => ['icon' => 'fi fi-ts-theater-masks', 'search_terms' => ['theatre', 'acting', 'drama']],
            'Music' => ['icon' => 'fi fi-rs-music-alt', 'search_terms' => ['music', 'musical']],
            'Day Care' => ['icon' => 'fi fi-rr-child', 'search_terms' => ['daycare', 'day care', 'childcare']],
            'Chess' => ['icon' => 'fi fi-ts-chess', 'search_terms' => ['chess']],
            'Table Tennis' => ['icon' => 'fi fi-rr-ping-pong', 'search_terms' => ['table tennis', 'ping pong']],
            'Martial Arts/Karate' => ['icon' => 'fi fi-tr-uniform-martial-arts', 'search_terms' => ['martial arts', 'karate', 'taekwondo']],
            'Foundational Stem' => ['icon' => 'fi fi-rr-microscope', 'search_terms' => ['stem', 'science', 'foundational']],
            'Maths/Science' => ['icon' => 'fi fi-sr-calculator-simple', 'search_terms' => ['math', 'science', 'mathematics']],
            'Library' => ['icon' => 'fi fi-rr-book-alt', 'search_terms' => ['library', 'libraries']],
            'Pediatrician' => ['icon' => 'fi fi-rr-stethoscope', 'search_terms' => ['pediatrician', 'pediatric']],
            'Counselling' => ['icon' => 'fi fi-rr-meeting', 'search_terms' => ['counselling', 'counseling', 'therapy']],
            'Painting/Sketching' => ['icon' => 'fi fi-rr-palette', 'search_terms' => ['painting', 'sketching', 'art']]
        ];
        
        // Find matching subcategories for each custom category
        $customCategories = [];
        foreach ($customCategoryMappings as $categoryName => $mapping) {
            // Try to find a matching subcategory
            $subcategory = null;
            foreach ($mapping['search_terms'] as $term) {
                $subcategory = Subcategory::where('name', 'LIKE', '%' . $term . '%')->first();
                if ($subcategory) {
                    break;
                }
            }
            
            if ($subcategory) {
                $customCategories[] = [
                    'name' => $categoryName,
                    'icon' => $subcategory->icon_class ? $subcategory->getFormattedIconClass() : $mapping['icon'],
                    'route' => route('listings', $subcategory)
                ];
            } else {
                // Fallback to search route
                $customCategories[] = [
                    'name' => $categoryName,
                    'icon' => $mapping['icon'],
                    'route' => route('search') . '?query=' . urlencode($categoryName)
                ];
            }
        }
        
        return $customCategories;
    }
} 