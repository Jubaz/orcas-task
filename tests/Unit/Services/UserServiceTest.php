<?php

namespace Tests\Unit\Services;

use App\Constants\Pagination;
use App\Models\User;
use App\Services\UserServices;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use WithFaker;

    private UserServices $userService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = app()->make(UserServices::class);
    }

    /**
     * @test
     */
    public function it_can_get_users_paginated()
    {
        User::factory()->count(15)->create();

        $users = $this->userService->getUsersPaginated();

        $this->assertDatabaseCount('users', 15);
        $this->assertCount(Pagination::DEFAULT_PER_PAGE, $users);
    }

    /**
     * @test
     */
    public function it_can_store_bulk_users()
    {
        $data = [
            [
                'first_name' => 'Fake first name',
                'last_name' => 'Fake last name',
                'email' => 'test',
                'avatar' => 'help'
            ],
            [
                'first_name' => 'Fake first name',
                'last_name' => 'Fake last name',
                'email' => 'test11',
                'avatar' => 'help'
            ]
        ];

        $this->userService->storeBulk($data);

        $this->assertDatabaseCount('users', 2);

        $this->assertDatabaseHas(
            'users',
            [
                'first_name' => 'Fake first name',
                'last_name' => 'Fake last name',
                'email' => 'test',
                'avatar' => 'help'
            ]
        );
    }

    /**
     * @test
     */
    public function it_can_get_valid_users()
    {
        // User::factory()->create(['email' => 'test@email.com']);
        $data = collect(
            [
                [
                    'first_name' => 'First Name',
                    'last_name' => $this->faker->lastName,
                    'email' => $this->faker->email,
                    'avatar' => 'avatar'
                ],
                [
                    'first_name' => 'Second Name',
                    'last_name' => $this->faker->lastName,
                    'email' => $this->faker->email,
                    'avatar' => 'avatar'
                ]
            ]
        );

        $users = $this->userService->getReadyToBeInserted($data);

        $this->assertCount(2, $users);
        $this->assertTrue($users->contains('first_name', 'First Name'));
        $this->assertTrue($users->contains('first_name', 'Second Name'));
    }

    /**
     * @test
     */
    public function it_can_reject_user_with_empty_first_name()
    {
        $data = collect(
            [
                [
                    'first_name' => '',
                    'last_name' => $this->faker->lastName,
                    'email' => $this->faker->email,
                    'avatar' => 'avatar'
                ]
            ]
        );

        $users = $this->userService->getReadyToBeInserted($data);

        $this->assertCount(0, $users);
    }

    /**
     * @test
     */
    public function it_can_reject_user_with_empty_last_name()
    {
        $data = collect(
            [
                [
                    'first_name' => $this->faker->name,
                    'last_name' => '',
                    'email' => $this->faker->email,
                    'avatar' => 'avatar'
                ],
                [
                    'first_name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'avatar' => 'avatar'
                ]
            ]
        );

        $users = $this->userService->getReadyToBeInserted($data);

        $this->assertCount(0, $users);
    }

    /**
     * @test
     */
    public function it_can_reject_user_with_empty_email_or_email_already_in_database()
    {
        User::factory()->create(['email' => 'fake@email.com']);

        $data = collect(
            [
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'avatar' => 'avatar'
                ],
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => 'fake@email.com',
                    'avatar' => 'avatar'
                ]
            ]
        );

        $users = $this->userService->getReadyToBeInserted($data);

        $this->assertCount(0, $users);
    }

    /**
     * @test
     */
    public function it_can_reject_user_with_empty_avatar()
    {
        $data = collect(
            [
                [
                    'first_name' => $this->faker->name,
                    'last_name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'avatar' => ''
                ],
                [
                    'first_name' => $this->faker->name,
                    'email' => $this->faker->email,
                    'last_name' => $this->faker->name,
                ]
            ]
        );

        $users = $this->userService->getReadyToBeInserted($data);

        $this->assertCount(0, $users);
    }

}
