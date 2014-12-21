<?php
class pathHelper{

    public function cdn(){
        global $config, $request;

        return sprintf('%s/%s', $config->vars['cdn'], $request->branch);
    }



}