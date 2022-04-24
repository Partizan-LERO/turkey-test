<?php

namespace Tests\Unit\Domain\Fixture\Policy;

use App\Domain\Fixture\Policy\FixturePolicy;
use App\Domain\Fixture\Repository\FixtureRepository;
use Tests\TestCase;

class FixturePolicyTest extends TestCase
{
    public function testAreFixturesGenerated()
    {
        $fixtureRepository = $this->mock(FixtureRepository::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->once()->andReturn(false);
        });
        $fixturePolicy = new FixturePolicy($fixtureRepository);
        $result = $fixturePolicy->areFixturesGenerated();
        $this->assertFalse($result);

        $fixtureRepository = $this->mock(FixtureRepository::class, function ($mock) {
            $mock->shouldReceive('areFixturesGenerated')->once()->andReturn(true);
        });
        $fixturePolicy = new FixturePolicy($fixtureRepository);
        $result = $fixturePolicy->areFixturesGenerated();
        $this->assertTrue($result);
    }
}
