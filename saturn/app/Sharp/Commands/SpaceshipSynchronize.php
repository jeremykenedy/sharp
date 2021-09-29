<?php

namespace App\Sharp\Commands;

use App\SpaceshipType;
use Code16\Sharp\EntityList\Commands\EntityCommand;

class SpaceshipSynchronize extends EntityCommand
{
    public function label(): string
    {
        return sprintf(
            "Synchronize the gamma-spectrum of %s spaceships",
            SpaceshipType::findOrFail($this->queryParams->filterFor("type"))->label
        );
    }

    public function description(): string
    {
        return "Let's be honest: this command is a fraud. It's just an empty command for test purpose.";
    }

    public function execute(array $data=[]): array
    {
        sleep(1);

        return $this->info(
            sprintf(
                "Gamma spectrum of %s spaceships synchronized!",
                SpaceshipType::findOrFail($this->queryParams->filterFor("type"))->label
            )
        );
    }

    public function confirmationText(): string
    {
        return "Sure, really?";
    }

    public function authorize():bool
    {
        return sharp_user()->hasGroup("boss");
    }
}