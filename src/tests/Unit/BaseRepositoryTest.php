<?php

use App\Models\Transfer;
use Domain\Shared\Repositories\BaseRepository;
use Tests\TestCase;

class StubRepository extends BaseRepository
{
    protected string $modelClass = Transfer::class;
}

uses(TestCase::class);

it('delegates findById to findOne', function () {
    $repo = $this->getMockBuilder(StubRepository::class)
        ->onlyMethods(['findOne'])
        ->getMock();

    $repo->expects($this->once())
        ->method('findOne')
        ->with(['id' => 5])
        ->willReturn(['id' => 5]);

    $result = $repo->findById(5);
    expect($result)->toBe(['id' => 5]);
});
