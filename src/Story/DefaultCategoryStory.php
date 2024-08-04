<?php

namespace App\Story;

use Zenstruck\Foundry\Story;
use App\Factory\CategoryFactory;

final class DefaultCategoryStory extends Story
{
    public function build(): void
    {
        CategoryFactory::createMany(100);
    }
}
