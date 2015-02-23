<?php

/* main.html.twig */
class __TwigTemplate_46569df15b672f3377f894e3a5c6879d5128cf5a200ebeac9a8608d32e5d8c7d extends Twig_Template
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
        <h1>Последние файлы</h1>
        <div class=\"main-column\">
        <ul>
            ";
        // line 12
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["files"]) ? $context["files"] : null));
        foreach ($context['_seq'] as $context["_key"] => $context["file"]) {
            // line 13
            echo "                <li>
                <a href=\"";
            // line 14
            echo twig_escape_filter($this->env, (isset($context["hostName"]) ? $context["hostName"] : null), "html", null, true);
            echo "/";
            echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "key", array()), "html", null, true);
            echo "\" title=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "name", array()), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "name", array()), "html", null, true);
            echo "</a>
                <span>";
            // line 15
            echo twig_escape_filter($this->env, $this->getAttribute($context["file"], "getSize", array()), "html", null, true);
            echo "</span>
                </li>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['file'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 18
        echo "        </ul>
        </div>
    </div>
";
    }

    public function getTemplateName()
    {
        return "main.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  82 => 18,  73 => 15,  63 => 14,  60 => 13,  56 => 12,  50 => 8,  47 => 7,  40 => 4,  37 => 3,  11 => 1,);
    }
}
