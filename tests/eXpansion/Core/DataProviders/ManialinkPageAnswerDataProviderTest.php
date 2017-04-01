<?php

namespace Tests\eXpansion\Core\DataProviders;

use eXpansion\Core\DataProviders\Listener\ManialinkPageAnswerDataListenerInterface;
use eXpansion\Core\DataProviders\ManialinkPageAnswerDataProvider;
use Tests\eXpansion\Core\TestCore;

class ManialinkPageAnswerDataProviderTest extends TestCore
{

    public function testDispatch()
    {
        $mockPlugin = $this->createMock(ManialinkPageAnswerDataListenerInterface::class);
        $mockPlugin->expects($this->once())
            ->method('onPlayerManialinkPageAnswer')
            ->with('test', 'action', ['val1' => 'test1', 'val2' => 'test2']);

        /** @var ManialinkPageAnswerDataProvider $dataProvider */
        $dataProvider = $this->container->get('expansion.core.data_providers.manialink_page_answer_provider');

        $dataProvider->registerPlugin('test', $mockPlugin);
        $dataProvider->onPlayerManialinkPageAnswer(
            'test',
            'test',
            'action',
            [['Name' => 'val1', 'Value' => 'test1'], ['Name' => 'val2', 'Value' => 'test2']]
        );
    }
}
