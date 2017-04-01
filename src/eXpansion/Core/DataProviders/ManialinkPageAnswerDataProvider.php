<?php


namespace eXpansion\Core\DataProviders;


class ManialinkPageAnswerDataProvider extends AbstractDataProvider
{
    /**
     * When a player uses an action dispatch information.
     *
     * @param $login
     * @param $actionId
     * @param array $entryValues
     *
     */
    public function onPlayerManialinkPageAnswer($playerUid, $login, $actionId, array $entries)
    {
        $entryValues = array();
        if(count($entries))
        {
            foreach($entries as $entry) {
                $entryValues[$entry['Name']] = $entry['Value'];
            }
        }

        $this->dispatch(__FUNCTION__, [$login, $actionId, $entryValues]);
    }
}