<?php

namespace Domain\State\Services;

use Domain\Country\Models\Country;
use Domain\State\Models\State;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;

class StateService
{
    /**
     * @return Collection<int,\Domain\State\Models\State>
     */
    public function getStates(Country $country): Collection
    {
        return Cache::tags('localizations')
            ->remember('states.' . $country->id, now()->addMinutes(15), function () use ($country) {
                return State::where('country_id', $country->id)->select('id', 'name')->get();
            });
    }
}
