<?php

namespace eXpansion\Framework\Core\Plugins\Gui;

use eXpansion\Framework\Core\DataProviders\Listener\ListenerInterfaceExpTimer;
use eXpansion\Framework\Core\Model\Gui\ManialinkFactoryContext;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\Script\Variable;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use FML\Script\Script;
use FML\Script\ScriptLabel;

/**
 * Class ScriptVariableUpdateFactory
 *
 * @author    de Cramer Oliver<oliverde8@gmail.com>
 * @copyright 2018 eXpansion
 * @package eXpansion\Framework\Core\Plugins\Gui
 */
class ScriptVariableUpdateFactory extends WidgetFactory implements ListenerInterfaceExpTimer
{
    /** @var Variable[] */
    protected $variables = [];

    /** @var Variable */
    protected $checkVariable;

    /** @var int */
    protected $maxUpdateFrequency;

    /** @var Variable */
    protected $checkOldVariable;

    /** @var Group */
    protected $playerGroup;

    protected $queuedForUpdate = null;

    /**
     * ScriptVariableUpdateFactory constructor.
     *
     * @param                      $name
     * @param array                $variables
     * @param int                  $maxUpdateFrequency
     * @param Group                $playerGroup
     * @param WidgetFactoryContext $context
     */
    public function __construct($name, array  $variables, int $maxUpdateFrequency = 1, Group $playerGroup, WidgetFactoryContext $context)
    {
        parent::__construct($name, 0, 0, 0, 0, $context);
        $this->playerGroup = $playerGroup;
        $this->maxUpdateFrequency = $maxUpdateFrequency;

        foreach ($variables as $variable) {
            $this->variables[$variable['name']] = new Variable(
                $variable['name'],
                $variable['type'],
                'This',
                $variable['default']
            );
        }

        $uniqueId = uniqid('exp_',true);
        $this->checkVariable = new Variable('check', 'Text', 'This', "\"$uniqueId\"");
        $this->checkOldVariable = new Variable('check_old', 'Text', 'Page', "\"$uniqueId\"");
    }

    /**
     * Update script value.
     *
     * @param $variable
     * @param $newValue
     */
    public function updateValue($variable, $newValue)
    {
        if ($this->variables[$variable]->getValue() != $newValue) {
            $this->variables[$variable]->setValue($newValue);
            $uniqueId = '"' . uniqid('exp_', true) . '"';
            $this->checkVariable->setValue($uniqueId);

            if (is_null($this->queuedForUpdate)) {
                $this->queuedForUpdate = time();
            }
        }

    }

    /**
     * Get a variable.
     *
     * @param $variable
     *
     * @return Variable
     */
    public function getVariable($variable)
    {
        return $this->variables[$variable];
    }

    /**
     * Get variable to use to check if data needs to be updated.
     *
     * @return Variable
     */
    public function getCheckVariable()
    {
        return $this->checkVariable;
    }

    /**
     * Get script to execute when there is a change.
     *
     * @param string $toExecute Script to execute.
     *
     * @return string
     */
    public function getScriptOnChange($toExecute)
    {
        return <<<EOL
            if ({$this->checkVariable->getVariableName()} != {$this->checkOldVariable->getVariableName()}) {
                {$this->checkOldVariable->getVariableName()} = {$this->checkVariable->getVariableName()};
                $toExecute
            }
EOL;
    }

    /**
     * Get initialization script.
     *
     * @param bool $defaultValues
     * @return string
     */
    public function getScriptInitialization($defaultValues = false)
    {
        $scriptContent = '';
        foreach ($this->variables as $variable) {
            $scriptContent .= $variable->getScriptDeclaration() . "\n";
            if ($defaultValues) {
                $scriptContent .= $variable->getScriptValueSet() . "\n";
            }
        }
        $scriptContent .= $this->checkVariable->getScriptDeclaration() . "\n";
        $scriptContent .= $this->checkOldVariable->getScriptDeclaration() . "\n";
        if ($defaultValues) {
            $scriptContent .= $this->checkVariable->getScriptValueSet() . "\n";
            $scriptContent .= $this->checkOldVariable->getScriptValueSet() . "\n";
        }

        return $scriptContent;
    }

    /**
     * @inheritdoc
     */
    protected function updateContent(ManialinkInterface $manialink)
    {
        // Empty existing script.
        parent::updateContent($manialink);
        $manialink->getFmlManialink()->removeAllChildren();

        // Get script with new values.
        $scriptContent = $this->getScriptInitialization(true);

        // Update FML Manialink
        $script = new Script();
        $script->addCustomScriptLabel(ScriptLabel::OnInit, $scriptContent);
        $manialink->getFmlManialink()->setScript($script);

        $this->queuedForUpdate = null;
    }

    /**
     * @inheritdoc
     */
    public function create($group = null)
    {
        return parent::create($this->playerGroup);
    }

    /**
     * @inheritdoc
     */
    public function update($group = null)
    {
        parent::update($this->playerGroup);
    }

    /**
     * @inheritdoc
     */
    public function destroy(Group $group = null)
    {
        parent::destroy($this->playerGroup);
    }

    /**
     * @inheritdoc
     */
    public function onPreLoop()
    {
        // Nothing
    }

    /**
     * @inheritdoc
     */
    public function onPostLoop()
    {
        // Nothing
    }

    /**
     * @inheritdoc
     */
    public function onEverySecond()
    {
        if (!is_null($this->queuedForUpdate)) {
            if (time() - $this->queuedForUpdate > $this->maxUpdateFrequency) {
                $this->update($this->playerGroup);
            }
        }
    }
}
