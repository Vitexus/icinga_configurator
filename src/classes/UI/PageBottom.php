<?php

namespace Icinga\Editor\UI;

/**
 * Page Bottom
 *
 * @package    VitexSoftware
 * @author     Vitex <vitex@hippy.cz>
 */
class PageBottom extends \Ease\Html\FooterTag
{

    /**
     * Zobrazí přehled právě přihlášených a spodek stránky
     */
    public function finalize()
    {
        $composer = 'composer.json';
        if (!file_exists($composer)) {
            $composer = '../'.$composer;
        }

        $appInfo = json_decode(file_get_contents($composer));

        $container = $this->setTagID('footer');
        $this->addItem('<hr>');
        $star      = '<iframe src="https://ghbtns.com/github-btn.html?user=Vitexus&repo=icinga_configurator&type=star&count=true" frameborder="0" scrolling="0" width="170px" height="20px"></iframe>';
        $footrow   = new \Ease\TWB\Row();
        $footrow->addColumn(4,
            '<a href="https://github.com/VitexSoftware/Icinga-Editor">Icinga Editor</a> v.: '.$appInfo->version.'&nbsp;&nbsp; &copy; 2012-2017 <a href="http://vitexsoftware.cz/">Vitex Software</a>');
        $footrow->addColumn(4,
            '<a href="http://www.austro-bohemia.cz/"><img style="position: relative;top: -2px; left: -10px; height: 25px" align="right" style="border:0" src="images/austro-bohemia-logo.png" alt="ABSRO" title="Pasivní checky napsány pro společnost Austro Bohemia s.r.o." /></a>');
        $footrow->addColumn(4,
            '<a href="http://www.spoje.net"><img style="position: relative; top: -7px; left: -10px;" align="right" style="border:0" src="img/spojenet_small_white.gif" alt="SPOJE.NET" title="Housing zajišťují SPOJE.NET s.r.o." /></a>');
        $this->addItem(new \Ease\TWB\Container($footrow));
//        $Foot->addItem('<a href="https://twitter.com/VSMonitoring" class="twitter-follow-button" data-show-count="true" data-lang="cs">Sledovat @VSMonitoring</a>');
    }
}
