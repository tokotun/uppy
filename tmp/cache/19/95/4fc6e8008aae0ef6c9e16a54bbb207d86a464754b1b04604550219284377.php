<?php

/* upload.html.twig */
class __TwigTemplate_19954fc6e8008aae0ef6c9e16a54bbb207d86a464754b1b04604550219284377 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 2
        try {
            $this->parent = $this->env->loadTemplate("base.html.twig");
        } catch (Twig_Error_Loader $e) {
            $e->setTemplateFile($this->getTemplateName());
            $e->setTemplateLine(2);

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

    // line 4
    public function block_brand($context, array $blocks = array())
    {
        // line 5
        echo "    <a class=\"brand\" href='";
        echo twig_escape_filter($this->env, (isset($context["hostName"]) ? $context["hostName"] : null), "html", null, true);
        echo "/main'>UPPY</a>
";
    }

    // line 8
    public function block_content($context, array $blocks = array())
    {
        // line 9
        echo "    <div class=\"container-fluid\">
        <center>
            <form accept-charset=\"UTF-8\" action=\"";
        // line 11
        echo twig_escape_filter($this->env, (isset($context["hostName"]) ? $context["hostName"] : null), "html", null, true);
        echo "/upload\" enctype=\"multipart/form-data\" method=\"post\">
            <input name=\"file\" size=\"50\" type=\"file\">
            <input class=\"btn upload\" name=\"submit\" value=\"Отправить\" type=\"submit\">
            </form>
        </center>
        <span class=\"text-error\">";
        // line 16
        echo twig_escape_filter($this->env, (isset($context["errorSize"]) ? $context["errorSize"] : null), "html", null, true);
        echo "</span>
        <span class=\"text-error\">";
        // line 17
        echo twig_escape_filter($this->env, (isset($context["errorUpload"]) ? $context["errorUpload"] : null), "html", null, true);
        echo "</span>
    </div>
";
    }

    public function getTemplateName()
    {
        return "upload.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  66 => 17,  62 => 16,  54 => 11,  50 => 9,  47 => 8,  40 => 5,  37 => 4,  11 => 2,);
    }
}
