<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait ScopePerson
{
    /**
     * Scope a query to include people information.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePerson($query)
    {
        return $query->select(
            "{$this->getTable()}.*",
            'people.nif',
            'people.name',
            'people.full_name',
            'people.birthdate',
            'people.state_registration',
            'people.city_registration',
            'people.email',
            'people.phone',
            'people.city_id',
            DB::raw("concat(cities.title, ' - ', states.letter) as city"),
            DB::raw("concat(people.name, ' - ', people.nif) as info"),
            'people.zip_code',
            'people.address',
            'people.district',
            'people.number',
        )
            ->join('people', 'people.id', '=', "{$this->getTable()}.person_id")
            ->join('cities', 'cities.id', '=', 'people.city_id')
            ->join('states', 'states.id', '=', 'cities.state_id');
    }
}
