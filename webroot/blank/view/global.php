<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$lang}" lang="{$lang}">
<head>
<title>{{ title }}</title>
<meta content="text/html; charset=utf-8" http-equiv="content-type" />
<meta content="Chuyên trang phụ nữ" name="author" />
<meta content="Chuyên trang phụ nữ" name="copyright" />
<meta content="follow, index" name="robots" />

<meta property="fb:app_id" content="118406908367197"/>
<meta property="fb:admins" content="100002340136737"/>

<link href="{$this->shortcut}" rel="shortcut icon" type="image/x-icon" />

</head>
<body>
{% for i in range(0, 3) %}
    {{ i }},
{% endfor %}

{{ pandog }}
</body>
</html>