<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('chatroom', function () { return true; });

Broadcast::channel('chatroom.{receiver_id}', function ($user, $receiver_id) {  //$user is laravel build is variable jeta current user er data rakhe.
   return $user->id == $receiver_id;  //current userid ar event theke send kora id seme hole jekhan theke request korsea se message ta se pabe. 
});
