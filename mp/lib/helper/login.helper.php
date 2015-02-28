<?php

class loginHelper {


    public function userId() {
        global $request;

        $channel = $request->channel;
        return Session::read("auth.{$channel}.user.id");
    }

    public function loggedIn() {
        return !empty(self::userId());
    }
}