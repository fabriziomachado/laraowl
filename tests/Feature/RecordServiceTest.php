<?php

use App\Models\Project;
use App\Models\Record;
use App\Services\RecordService;

it('handles non numeric outgoing request status values without SQL errors', function () {
    $project = Project::factory()->create();

    Record::create([
        'project_id' => $project->id,
        'type' => 'outgoing-request',
        'fingerprint' => 'failed-status',
        'payload' => [
            'host' => 'api.example.com',
            'status' => 'failed',
            'duration' => 125,
        ],
        'created_at' => now(),
    ]);

    Record::create([
        'project_id' => $project->id,
        'type' => 'outgoing-request',
        'fingerprint' => 'ok-status',
        'payload' => [
            'host' => 'api.example.com',
            'status_code' => 200,
            'duration' => 75,
        ],
        'created_at' => now(),
    ]);

    $stats = app(RecordService::class)->getOutgoingRequestStats($project, '24h');

    expect($stats['overview'])
        ->toMatchArray([
            'total' => 2,
            'ok' => 1,
            'failed' => 0,
        ]);

    expect($stats['hosts']->total())->toBe(2);
});
