<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Api\ApiBaseController;
use App\Http\Resources\UserResource;
use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends ApiBaseController
{
    private UserServices $userServices;

    public function __construct(UserServices $userServices)
    {
        $this->userServices = $userServices;
    }

    public function index()
    {
        return UserResource::collection($this->userServices->getUsersPaginated());
    }

    public function search(Request $request)
    {
        return UserResource::collection($this->userServices->getUsersByKeyword($request->get('keyword')));
    }
}
