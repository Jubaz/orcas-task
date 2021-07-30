<?php

namespace App\Services;

use App\Constants\Pagination;
use App\Repositories\UserRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class UserServices
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsersByKeyword(string $keyword): Collection
    {
        return $this->userRepository->getUsersByKeyword($keyword);
    }

    public function getUsersPaginated(): LengthAwarePaginator
    {
        return $this->userRepository->paginate(Pagination::DEFAULT_PER_PAGE);
    }

    public function storeBulk(array $users): bool
    {
        return $this->userRepository->insert($users);
    }

    public function getReadyToBeInserted(Collection $users): Collection
    {
        $duplicatesUsers = $this->userRepository->getUsersByEmails($users->pluck('email')->toArray());

        $duplicatesEmails = $duplicatesUsers->pluck('email')->toArray();

        return $users->reject(
            function ($item) use ($duplicatesEmails) {
                if (empty($item['first_name'])) {
                    return true;
                }

                if (empty($item['last_name'])) {
                    return true;
                }

                if (empty($item['avatar'])) {
                    return true;
                }

                if (empty($item['email'])) {
                    return true;
                }

                if (in_array($item['email'], $duplicatesEmails)) {
                    return true;
                }

                return false;
            }
        );
    }

}
