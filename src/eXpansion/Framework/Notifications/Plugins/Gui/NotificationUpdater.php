<?php

namespace eXpansion\Framework\Notifications\Plugins\Gui;

use eXpansion\Framework\Core\Helpers\ChatNotification;
use eXpansion\Framework\Core\Model\Gui\ManialinkInterface;
use eXpansion\Framework\Core\Model\Gui\WidgetFactoryContext;
use eXpansion\Framework\Core\Model\UserGroups\Group;
use eXpansion\Framework\Core\Plugins\Gui\ScriptVariableUpdateFactory;
use FML\Script\Builder;

class NotificationUpdater extends ScriptVariableUpdateFactory
{
    /**
     * @var ChatNotification
     */
    private $chatNotification;

    /**
     * NotificationUpdater constructor.
     * @param                      $name
     * @param array                $variables
     * @param float                $maxUpdateFrequency
     * @param WidgetFactoryContext $context
     * @param ChatNotification     $chatNotification
     */
    public function __construct(
        $name,
        array $variables,
        float $maxUpdateFrequency = 0.250,
        WidgetFactoryContext $context,
        ChatNotification $chatNotification
    ) {
        parent::__construct($name, $variables, $maxUpdateFrequency, $context);
        $this->chatNotification = $chatNotification;
    }

    /**
     * Update
     *
     * @param string $prefix
     * @param string $title title of the notification
     * @param string $message message part of notification
     * @param string $params parameters for multilingual string
     * @param int    $timeout timeout in milliseconds
     * @param Group  $group
     */
    public function setNotification($prefix, $title, $message, $params, int $timeout, $group)
    {

        $toast = [
            "title" => $this->getTranslations($title, $params),
            "message" => $this->getTranslations($message, $params),
            "params" => ["prefix" => $prefix, "duration" => "".$timeout],
        ];


        $this->updateValue($group, 'notification', Builder::getArray($toast, true));

    }


    /**
     * Generates the needed data structure
     * @param $string
     * @param $params
     * @return array
     */
    private function getTranslations($string, $params)
    {
        $out = [];
        $messages = $this->translationsHelper->getTranslations($string, $params);
        foreach ($messages as $message) {
            $out[$message['Lang']] = $message['Text'];
        }

        return $out;
    }
}
