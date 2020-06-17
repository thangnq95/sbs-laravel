<?php

namespace App\Observers;

use App\Model\PokemonRegistration;

class PokemonRegistrationObserve
{
    /**
     * Handle the pokemon registration "created" event.
     *
     * @param  \App\Model\PokemonRegistration  $pokemonRegistration
     * @return void
     */
    public function created(PokemonRegistration $pokemonRegistration)
    {
        //
    }

    /**
     * Handle the pokemon registration "updated" event.
     *
     * @param  \App\Model\PokemonRegistration  $pokemonRegistration
     * @return void
     */
    public function updated(PokemonRegistration $pokemonRegistration)
    {
        //
    }

    /**
     * Handle the pokemon registration "deleted" event.
     *
     * @param  \App\Model\PokemonRegistration  $pokemonRegistration
     * @return void
     */
    public function deleted(PokemonRegistration $pokemonRegistration)
    {
        //
    }

    /**
     * Handle the pokemon registration "restored" event.
     *
     * @param  \App\Model\PokemonRegistration  $pokemonRegistration
     * @return void
     */
    public function restored(PokemonRegistration $pokemonRegistration)
    {
        //
    }

    /**
     * Handle the pokemon registration "force deleted" event.
     *
     * @param  \App\Model\PokemonRegistration  $pokemonRegistration
     * @return void
     */
    public function forceDeleted(PokemonRegistration $pokemonRegistration)
    {
        //
    }
}
