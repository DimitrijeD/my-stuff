<?php

namespace Database\Seeders\clusters\ModelBuilders;

use App\Models\Chat\ChatGroup;

class ChatGroupBuilder 
{
    public $properties;
    
    public function __construct($properties)
    {
        $this->properties = $properties;
    }

    public function makeModel()
    {
        return ChatGroup::factory()->create($this->properties); 
    }

}