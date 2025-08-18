<?php

use App\Models\Transfer;
use Domain\Shared\Repositories\BaseRepository;
use Mockery;
use Tests\TestCase;

class StubRepository extends BaseRepository
{
    protected string $modelClass = Transfer::class;
}

uses(TestCase::class);

afterEach(function () {
    Mockery::close();
});

it('delegates findById to findOne', function () {
    $repo = Mockery::mock(StubRepository::class)->makePartial();
    $repo->shouldReceive('findOne')->with(['id' => 5])->andReturn(['id' => 5]);

    $result = $repo->findById(5);
    expect($result)->toBe(['id' => 5]);
});
