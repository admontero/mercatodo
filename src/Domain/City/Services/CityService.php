<?php

namespace Domain\City\Services;

use Domain\City\Models\City;
use Domain\State\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CityService
{
    /**
     * @return Collection<int,\Domain\City\Models\City>
     */
    public function getCities(State $state): Collection
    {
        return Cache::tags('localizations')
            ->remember('cities.' . $state->id, now()->addMinutes(15), function() use ($state) {
                return City::where('state_id', $state->id)->select('id', 'name')->get();
        });
    }
}
