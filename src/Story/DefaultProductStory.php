<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\ProductFactory;

final class DefaultProductStory extends Story
{
    public function build(): void
    {
        ProductFactory::createMany(500);
    }
}
