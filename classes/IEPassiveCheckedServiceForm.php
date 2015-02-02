<?php

/**
 * Formulář průvodce založením nové služby
 *
 * @package    IcingaEditor
 * @subpackage WebUI
 * @author     Vitex <vitex@hippy.cz>
 * @copyright  2015 Vitex@hippy.cz (G)
 */
require_once 'classes/IEPlatformSelector.php';
require_once 'Ease/EaseTWBootstrap.php';

/**
 * Description of NewPassiveCheckedHostForm
 *
 * @author vitex
 */
class IEPassiveCheckedServiceForm extends EaseTWBForm
{

    function afterAdd()
    {
        $this->addItem(new EaseTWBFormGroup(_('Jméno'), new EaseHtmlInputTextTag('service_name'), $this->webPage->getRequestValue('host_name'), _('Volné místo disku'), _('Název služby testu')));
        $this->addItem(new EaseTWBFormGroup(_('Platforma'), new IEPlatformSelector('platform'), null, _('Platforma sledovaného stroje')));
        $this->addItem(new EaseTWSubmitButton(_('Založit') . '&nbsp' . EaseTWBPart::GlyphIcon('forward'), 'success'));
    }

}
