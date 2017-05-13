<?php


namespace eXpansion\Framework\Core\DataProviders;


class ManialinkPageAnswerDataProvider extends AbstractDataProvider
{
    /**
     * When a player uses an action dispatch information.
     *
     * @param $login
     * @param $actionId
     *
     */
    public function onPlayerManialinkPageAnswer($playerUid, $login, $actionId, array $entries)
    {
        $entryValues = array();
        if (count($entries))
        {
            foreach ($entries as $entry) {
                $entryValues[$entry['Name']] = $entry['Value'];
            }
        }

        $this->dispatch(__FUNCTION__, [$login, $actionId, $entryValues]);
    }
}