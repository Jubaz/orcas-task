<?php

namespace App\Repositories;

use App\Models\User;
use Prettus\Repository\Eloquent\BaseRepository;

class UserRepository extends BaseRepository
{

    public function model()
    {
        return User::class;
    }

    public function getUsersByEmails(array $emails)
    {
        return $this->findWhereIn('email', $emails);
    }
}
