<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('chatroom', function () { return true; });

Broadcast::channel('chatroom.{receiver_id}', function (User $user, $receiver_id) {
    return $user->id == $receiver_id;  //current userid ar event theke send kora id seme hole jekhan theke request korsea se message ta se pabe. 
}); //chatroom channel jekhan theke call hobe sekhan kar reciver id sob somoy kan pete sune thaksea keu kisu dissea ki nah dilei show korabe. 

Broadcast::channel('videocall.{receiver_id}', function (User $user, $receiver_id) {
    return $user->id == $receiver_id;
});

Broadcast::channel('chat.{receiver_id}', function (User $user, $receiver_id) {
    return $user->id == $receiver_id;   // true false return korbea... user $user(bortomane je user ta login obosthay asea.) $receiver_id (je user k pathano hoisea.{$receiver_id ta laravel dhore ne channel name er oikhan theke})2 user aki ki nah. ak hole ja na holea jabea nah
});

Broadcast::channel('audiocall.{receiver_id}', function (User $user, $receiver_id) {
    return $user->id == $receiver_id;   // true false return korbea... user $user(bortomane je user ta login obosthay asea.) $receiver_id (je user k pathano hoisea.{$receiver_id ta laravel dhore ne channel name er oikhan theke})2 user aki ki nah. ak hole ja na holea jabea nah
});

