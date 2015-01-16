<?php

class view {
    private $ext = '.twg';

    public function render($tpl = '', $option = array()) {
        global $template;

        $tpl .= $this->ext;
        return $template->render($tpl, $option);
    }

    public function finalize($runme) {
        global $app;

        $runme->navigator();

        $content = $runme->getOutput();
        $layout = $runme->getLayout();

        if($layout) {
            $addon = Helper::get('addon', 'component')->get();
        }

        $title = 'twig test';

        $layout = 'layout' . DS . $layout;

        echo $this->render($layout, compact('title', 'content', 'addon'));

        $app->finish();
        return ob_get_clean();
    }
}