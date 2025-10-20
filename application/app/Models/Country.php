<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = [
        'code',
        'country',
        'dial_code'
    ];

    // If you want to use the country code as the primary key
    protected $primaryKey = 'code';
    public $incrementing = false;
    protected $keyType = 'string';

    // Disable timestamps if not needed
    public $timestamps = false;

    /**
     * Get all countries from JSON file as a collection
     * This method can be used to load countries from the existing JSON file
     */
    public static function getAllFromJson()
    {
        $jsonPath = resource_path('views/includes/country.json');
        if (file_exists($jsonPath)) {
            $countries = json_decode(file_get_contents($jsonPath), true);
            $collection = collect();
            
            foreach ($countries as $code => $data) {
                $collection->push((object) [
                    'code' => $code,
                    'country' => $data['country'],
                    'dial_code' => $data['dial_code']
                ]);
            }
            
            return $collection;
        }
        
        return collect();
    }

    /**
     * Get country by code
     */
    public static function getByCode($code)
    {
        $countries = self::getAllFromJson();
        return $countries->firstWhere('code', $code);
    }
}