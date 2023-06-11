<?php

namespace Domain\Country\Services;

use Domain\Country\Models\Country;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class CountryService
{
    /**
     * @return Collection<int,\Domain\Country\Models\Country>
     */
    public function getCountries(): Collection
    {
        return Cache::tags('localizations')->remember('countries', now()->addMinutes(15), function () {
            return Country::select('id', 'name')->get();
        });
    }
}
