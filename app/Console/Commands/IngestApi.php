<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Civilization;
use App\Models\Leader;
use App\Models\HistoricalInfo;
use App\Helpers\LifespanParser;

class IngestApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ingest-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch civilizations data from the external API and populate the database';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Fetching data from API...');

        $response = Http::get('https://eyefyre.github.io/civvapi/v1/en/civilizations/civilizations.json');

        if (!$response->ok()) {
            $this->error('Failed to fetch data from API.');
            return 1;
        }

        $data = $response->json();

        if (!is_array($data)) {
            $this->error('Invalid data structure received.');
            return 1;
        }

        // Iterate over each civilization record in the array.
        foreach ($data as $civData) {
            // Create or update a civilization using the "name" as a unique identifier.
            $civilization = Civilization::updateOrCreate(
                ['name' => $civData['name'] ?? ''],
                ['icon' => $civData['icon'] ?? '']
            );

            $this->info("Processed civilization: {$civilization->name}");

            // Process historical info for the civilization.
            if (isset($civData['historical_info']) && is_array($civData['historical_info'])) {
                foreach ($civData['historical_info'] as $histData) {
                    HistoricalInfo::updateOrCreate(
                        [
                            'taxonomy_id' => $civilization->id,
                            'type'        => Civilization::class,
                            'heading'     => $histData['heading'] ?? '',
                        ],
                        ['text' => $histData['text'] ?? '']
                    );
                    $this->info("Added historical info for civilization: {$civilization->name}");
                }
            }

            // Process leader information if available.
            if (isset($civData['leader']) && is_array($civData['leader'])) {
                $leaderData = $civData['leader'];
                $lived = $leaderData['lived'] ?? '';
                $lifeData = LifespanParser::parseLivedField($leaderData['lived'] ?? '');

                $leader = Leader::updateOrCreate(
                    ['civilization_id' => $civilization->id],
                    [
                        'name'      => $leaderData['name'] ?? '',
                        'icon'      => $leaderData['icon'] ?? '',
                        'subtitle'  => $leaderData['subtitle'] ?? '',
                        'life_start' => $lifeData['life_start'] ?? null,
                        'life_end'   => $lifeData['life_end'] ?? null,
                    ]
                );

                $this->info("Processed leader: {$leader->name}");

                // Process leader's historical info.
                if (isset($leaderData['historical_info']) && is_array($leaderData['historical_info'])) {
                    foreach ($leaderData['historical_info'] as $histData) {
                        HistoricalInfo::updateOrCreate(
                            [
                                'taxonomy_id' => $leader->id,
                                'type'        => Leader::class,
                                'heading'     => $histData['heading'] ?? '',
                            ],
                            ['text' => $histData['text'] ?? '']
                        );
                        $this->info("Added historical info for leader: {$leader->name}");
                    }
                }
            }
        }

        $this->info('Data ingestion complete.');
        return 0;
    }
}
