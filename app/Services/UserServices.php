<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserServices
{

    public function storeBulk(array $users): bool
    {
        return DB::table('users')->insert($users);
    }

    public function readyToBeInserted(Collection $users): Collection
    {
        $duplicatedEmails = User::select('email')->whereIn('email', $users->pluck('email'))->get()->pluck(
            'email'
        )->toArray();

        return $users->reject(
            function ($item) use ($duplicatedEmails) {
                if (!$item['first_name'] || !$item['last_name'] || !$item['avatar'] || !$item['email']) {
                    return true;
                }

                if (in_array($item['email'], $duplicatedEmails)) {
                    return true;
                }

                return false;
            }
        );
    }

}
