<?php

/* download.html.twig */
class __TwigTemplate_75cb9746a3994233ae74d1ccfef59984aeea88c3eb0621c3161467396f4333b1 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        try {
            $this->parent = $this->env->loadTemplate("base.html.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(1);

            throw $e;
        }

        $this->blocks = array(
            'brand' => array($this, 'block_brand'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "base.html.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_brand($context, array $blocks = array())
    {
        // line 4
        echo "    <a class=\"brand\" href='";
        echo twig_escape_filter($this->env, (isset($context["hostName"]) ? $context["hostName"] : null), "html", null, true);
        echo "/upload'>UPPY</a>
";
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "    <div class=\"container\">
    \t";
        // line 9
        if ((isset($context["file"]) ? $context["file"] : null)) {
            // line 10
            echo "\t\t   \t";
            if ($this->getAttribute((isset($context["file"]) ? $context["file"] : null), "isImage", array(), "method")) {
                // line 11
                echo "\t    \t\t<img class=\"img-polaroid\" src=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "getPathThumbs", array(), "method"), "html", null, true);
                echo "\"\">
\t\t\t";
            }
            // line 13
            echo "\t        <p><strong>Файл</strong> - ";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "name", array()), "html", null, true);
            echo "</p>
\t        <p>";
            // line 14
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "getSize", array(), "method"), "html", null, true);
            echo "</p>
\t        <p>";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "dateLoad", array()), "html", null, true);
            echo "</p>
\t        <p>";
            // line 16
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "key", array()), "html", null, true);
            echo "</p>
\t        <a href=\"";
            // line 17
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "getDownloadLink", array(0 => (isset($context["hostName"]) ? $context["hostName"] : null)), "method"), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["file"]) ? $context["file"] : null), "name", array()), "html", null, true);
            echo "\">Скачать</a>
\t\t";
        } else {
            // line 19
            echo "\t\t    <h1>Файл не найден!</h1>
\t\t";
        }
        // line 21
        echo "    </div>
";
    }

    public function getTemplateName()
    {
        return "download.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  92 => 21,  88 => 19,  81 => 17,  77 => 16,  73 => 15,  69 => 14,  64 => 13,  58 => 11,  55 => 10,  53 => 9,  50 => 8,  47 => 7,  40 => 4,  37 => 3,  11 => 1,);
    }
}
