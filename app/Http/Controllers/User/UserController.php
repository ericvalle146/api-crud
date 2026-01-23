<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Actions\User\CreateUser;
use App\Actions\User\DeleteUser;
use App\Actions\User\FetchUser;
use App\Actions\User\FetchUserList;
use App\Actions\User\UpdateUser as UserUpdateUser;
use App\DTOs\Common\PaginationDTO;
use App\DTOs\User\CreateUserDTO;
use App\DTOs\User\UpdateUserDTO;
use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\NoContentResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(PaginationDTO $dto, FetchUserList $action): JsonResponse
    {
        return UserResource::collection($action->handle($dto))->response();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, FetchUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new UserResource($action->handle($id)));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateUserDTO $dto, CreateUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new UserResource($action->handle($dto)), Response::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(string $id, UpdateUserDTO $dto, UserUpdateUser $action): ApiSuccessResponse
    {
        return new ApiSuccessResponse(new UserResource($action->handle($id, $dto)));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id, DeleteUser $action): NoContentResponse
    {
        $action->handle($id);

        return new NoContentResponse();
    }
}
