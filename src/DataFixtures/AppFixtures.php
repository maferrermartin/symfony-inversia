<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Story\DefaultCategoryStory;
use App\Story\DefaultProductStory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        DefaultCategoryStory::load();
        DefaultProductStory::load();
    }
}
