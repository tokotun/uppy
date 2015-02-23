<?php

/* base.html.twig */
class __TwigTemplate_459888296dfbb27fb516274b3438ad249bed5350e4a6d202acd3f7ebb5864bbf extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'brand' => array($this, 'block_brand'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html lang=\"ru\">
    <head>
    <meta charset=\"utf-8\">
    <title>Uppy — клон Rghost</title>
    <link href=\"bootstrap/css/bootstrap.css\" media=\"screen\" rel=\"stylesheet\" type=\"text/css\"/>
    </head>
    <body>
        <header class=\"navbar\">
            <div class=\"navbar-inner\">
                ";
        // line 11
        $this->displayBlock('brand', $context, $blocks);
        // line 14
        echo "                <div class=\"pull-right nav-collapse\">
                    <form accept-charset=\"UTF-8\" action=\"/search\" class=\"navbar-search pull-right\" method=\"get\">
                    <input class=\"search-query\" name=\"s\" placeholder=\"Поиск\" size=\"20\" type=\"search\">
                    </form>
                </div>
            </div>
        </header>
        ";
        // line 21
        $this->displayBlock('content', $context, $blocks);
        // line 22
        echo "    </body>
</html>";
    }

    // line 11
    public function block_brand($context, array $blocks = array())
    {
        // line 12
        echo "                    <a class=\"brand\" href=''>UPPY</a>
                ";
    }

    // line 21
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "base.html.twig";
    }

    public function getDebugInfo()
    {
        return array (  59 => 21,  54 => 12,  51 => 11,  46 => 22,  44 => 21,  35 => 14,  33 => 11,  21 => 1,);
    }
}
