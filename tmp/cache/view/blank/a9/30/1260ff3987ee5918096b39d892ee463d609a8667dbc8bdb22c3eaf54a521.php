<?php

/* 	       <!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi" lang="vi">
<head>
<title>{{ title }}</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<meta content="Chuyên trang phụ nữ" name="author" />
<meta content="Chuyên trang phụ nữ" name="copyright" />
<meta content="follow, index" name="robots" />

<meta property="fb:app_id" content="118406908367197"/>
<meta property="fb:admins" content="100002340136737"/>

<link href="http://mp.me/favicon.ico" rel="shortcut icon" type="image/x-icon" />

</head>
<body>
{% for i in range(0, 3) %}
    {{ i }},
{% endfor %}

{{ pandog }}
        <br />
        {{ pandog }}
</body>
</html>     */
class __TwigTemplate_a9301260ff3987ee5918096b39d892ee463d609a8667dbc8bdb22c3eaf54a521 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "\t       <!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"vi\" lang=\"vi\">
<head>
<title>";
        // line 4
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
        echo "</title>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\" />
<meta content=\"Chuyên trang phụ nữ\" name=\"author\" />
<meta content=\"Chuyên trang phụ nữ\" name=\"copyright\" />
<meta content=\"follow, index\" name=\"robots\" />

<meta property=\"fb:app_id\" content=\"118406908367197\"/>
<meta property=\"fb:admins\" content=\"100002340136737\"/>

<link href=\"http://mp.me/favicon.ico\" rel=\"shortcut icon\" type=\"image/x-icon\" />

</head>
<body>
";
        // line 17
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(range(0, 3));
        foreach ($context['_seq'] as $context["_key"] => $context["i"]) {
            // line 18
            echo "    ";
            echo twig_escape_filter($this->env, $context["i"], "html", null, true);
            echo ",
";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['i'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 20
        echo "
";
        // line 21
        echo twig_escape_filter($this->env, (isset($context["pandog"]) ? $context["pandog"] : $this->getContext($context, "pandog")), "html", null, true);
        echo "
        <br />
        ";
        // line 23
        echo twig_escape_filter($this->env, (isset($context["pandog"]) ? $context["pandog"] : $this->getContext($context, "pandog")), "html", null, true);
        echo "
</body>
</html>    ";
    }

    public function getTemplateName()
    {
        return "\t       <!DOCTYPE html>
<html xmlns=\"http://www.w3.org/1999/xhtml\" xml:lang=\"vi\" lang=\"vi\">
<head>
<title>{{ title }}</title>
<meta content=\"text/html; charset=utf-8\" http-equiv=\"content-type\" />
<meta content=\"Chuyên trang phụ nữ\" name=\"author\" />
<meta content=\"Chuyên trang phụ nữ\" name=\"copyright\" />
<meta content=\"follow, index\" name=\"robots\" />

<meta property=\"fb:app_id\" content=\"118406908367197\"/>
<meta property=\"fb:admins\" content=\"100002340136737\"/>

<link href=\"http://mp.me/favicon.ico\" rel=\"shortcut icon\" type=\"image/x-icon\" />

</head>
<body>
{% for i in range(0, 3) %}
    {{ i }},
{% endfor %}

{{ pandog }}
        <br />
        {{ pandog }}
</body>
</html>    ";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 23,  80 => 21,  77 => 20,  68 => 18,  64 => 17,  48 => 4,  43 => 1,);
    }
}
