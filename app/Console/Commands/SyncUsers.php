<?php

namespace App\Console\Commands;

use App\Services\UserServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and create users from third parties';
    /**
     * @var UserServices
     */
    private UserServices $userServices;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserServices $userServices)
    {
        parent::__construct();
        $this->userServices = $userServices;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach (config('sync-third-parties.users') as $thirdParty) {
            try {
                $this->syncThirdParty($thirdParty['url'], $thirdParty['fields_mapping']);
            } catch (Throwable $exception) {
                Log::channel('console')->error($exception);
            }
        }

        return 0;
    }

    private function syncThirdParty(string $url, array $fieldsMapMapping)
    {
        $usersReadyToBeSynced = collect([]);

        $response = Http::get($url);

        if ($response->failed()) {
            $this->error('Failed to call: ' . $url);

            return;
        }

        foreach ($response->json() as $item) {
            $usersReadyToBeSynced->push($this->reformatToSupportedArray($item, $fieldsMapMapping));
        }

        $readyToBeInserted = $this->userServices->readyToBeInserted($usersReadyToBeSynced);
        $this->userServices->storeBulk($readyToBeInserted->toArray());

        $this->info($url . ' Synced successfully');
    }

    private function reformatToSupportedArray(array $array, array $fieldsMapping): array
    {
        $supportedArray = [];

        foreach ($fieldsMapping as $key => $value) {
            $supportedArray[$key] = $array[$value];
        }

        return $supportedArray;
    }

}
