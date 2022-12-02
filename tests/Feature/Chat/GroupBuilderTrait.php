<?php

namespace Tests\Feature\Chat;
use Database\Seeders\ChatGroupClusterSeeder;

trait GroupBuilderTrait 
{
    public function makeGroup()
    {
        $this->chatSeeder = (resolve(ChatGroupClusterSeeder::class))->run();

        $this->user      = $this->chatSeeder->getCreator();
        $this->otherUser = $this->chatSeeder->getOtherUser();
        $this->group     = $this->chatSeeder->group;
        $this->group_id  = $this->chatSeeder->group->id;

        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");
    }

    public function makeGroupWith($config)
    {
        $this->chatSeeder = resolve(ChatGroupClusterSeeder::class);
        $this->chatSeeder->massSetter($config);
        $this->chatSeeder = $this->chatSeeder->run();

        $this->user      = $this->chatSeeder->getCreator();
        $this->otherUser = $this->chatSeeder->getOtherUser();
        $this->group     = $this->chatSeeder->group;
        $this->group_id  = $this->chatSeeder->group->id;

        $this->withHeader('Authorization', "Bearer {$this->user->createToken('app')->plainTextToken}");
    }
}