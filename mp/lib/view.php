<?php

class view {
    private $ext = '.twg';

    public function render($tpl = '', $option = array()) {
        global $template;

        $tpl .= $this->ext;
        return $template->render($tpl, $option);
    }

    public function finalize($runme) {
        $runme->navigator();

        $content = $runme->getOutput();
        $layout  = $runme->getLayout();

        if (empty($layout)) {
            echo $content;
        } else {
            $title = Helper::config()->get('website.page_title');

            $addon = Helper::get('addon', 'component')->get();
            $layout = 'layout' . DS . $layout;

            echo $this->render($layout, compact('title', 'content', 'addon'));
        }

        Helper::app()->finish();
        return ob_get_clean();
    }
}