<?php

namespace Database\Seeders\clusters\ModelBuilders;

use Faker\Factory as Faker;

use Database\Seeders\clusters\Contracts\Cluster;

class ChatMessageTextGenerator implements Cluster 
{
    public $numMessages, $messagesTexts;

    public function __construct($numMessages, $minTextLen, $maxTextLen, $useIncrementedNumbersAsTxt = false)
    {
        $this->numMessages = $numMessages;
        $this->minTextLen = $minTextLen;  
        $this->maxTextLen = $maxTextLen;

        $this->messagesTexts = [];
        $this->faker = Faker::create();
        $this->useIncrementedNumbersAsTxt = $useIncrementedNumbersAsTxt ? true : false;
    }

    public function build()
    {
        for($i = 0; $i < $this->numMessages; $i++){
            $this->messagesTexts[] = [
                'text' =>  $this->useIncrementedNumbersAsTxt 
                    ? $i 
                    : $this->faker->realText($this->generateTextLength())
            ];
        }
        return $this->messagesTexts;
    }

    private function generateTextLength()
    {
        return rand($this->minTextLen, $this->maxTextLen);
    }

}