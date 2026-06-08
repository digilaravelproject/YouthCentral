<?php

namespace App\Exports;

use App\Models\Business;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BusinessesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Business::with(['subcategory.category', 'area.city.state'])->get();
    }

    public function headings(): array
    {
        return [
            'Category',
            'Subcategory',
            'State',
            'City',
            'Area',
            'Business Name',
            'Phone',
            'Whatsapp Number',
            'Website',
            'Email',
            'Facebook Link',
            'Instagram Link',
            'Twitter Link',
            'Pinterest Link',
            'Street Address',
            'Description',
            'Latitude',
            'Longitude',
            'Status'
        ];
    }

    public function map($business): array
    {
        return [
            $business->subcategory->category->name ?? null,
            $business->subcategory->name ?? null,
            $business->area->city->state->name ?? null,
            $business->area->city->name ?? null,
            $business->area->name ?? null,
            $business->business_name,
            $business->phone,
            $business->whatsapp_number,
            $business->website,
            $business->email,
            $business->facebook_link,
            $business->instagram_link,
            $business->twitter_link,
            $business->pinterest_link,
            $business->street_address,
            $business->description,
            $business->latitude,
            $business->longitude,
            $business->status
        ];
    }

}