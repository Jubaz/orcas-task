<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{

    public function model()
    {
        return User::class;
    }

    public function getUsersByEmails(array $emails): Collection
    {
        return $this->findWhereIn('email', $emails);
    }

    public function getUsersByKeyword(string $keyword): Collection
    {
        return $this->where('email', 'like', '%' . $keyword . '%')
            ->orWhere('first_name', 'like', '%' . $keyword . '%')
            ->orWhere('last_name', 'like', '%' . $keyword . '%')
            ->get();
    }
}
