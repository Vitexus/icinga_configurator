<?php

namespace Icinga\Editor\UI;

/**
 * Description of IEUserGroupForm
 *
 * @author vitex
 */
class UserGroupForm extends \Ease\TWB\Form
{
    /**
     * Objekt skupiny uživatelů
     * @var IEUserGroup
     */
    public $userGroup = null;

    /**
     * Formulář skupiny uživatelů
     * @param IEUserGroup $userGroup
     */
    function __construct($userGroup)
    {
        $this->userGroup = $userGroup;
        parent::__construct('usergroup', $userGroup->keyword.'.php', 'POST');
    }

    function afterAdd()
    {
        $group_name   = $this->userGroup->getDataValue('usergroup_name');
        $this->addItem(new \Ease\TWB\FormGroup(_('Jméno'),
            new \Ease\Html\InputTextTag('usergroup_name', $group_name),
            $group_name, _('Název skupiny'), _('Adminové')));
        $this->addItem($this->userGroup->memberSelector());
        $usergroup_id = $this->userGroup->getMyKey();
        if ($usergroup_id) {
            $this->addItem(new \Ease\Html\InputHiddenTag($this->userGroup->getMyKeyColumn(),
                $usergroup_id));
        }
        if ($usergroup_id) {
            $this->addItem(new \Ease\TWB\SubmitButton(_('Uložit').'&nbsp'.\Ease\TWB\Part::GlyphIcon('save'),
                'success'));
        } else {
            $this->addItem(new \Ease\TWB\SubmitButton(_('Založit').'&nbsp'.\Ease\TWB\Part::GlyphIcon('forward'),
                'success'));
        }
    }
}