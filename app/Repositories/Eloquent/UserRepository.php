<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Models\Role;
use App\Repositories\Contracts\UserInterface;
use App\Jobs\SendEmail;
use Illuminate\Foundation\Bus\DispatchesJobs;
use App\Exceptions\Api\UnknowException;

class UserRepository extends BaseRepository implements UserInterface
{
    use DispatchesJobs;

    public function model()
    {
        return User::class;
    }

    public function active($token)
    {
        $user = $token ? $this->where('token_confirm', $token)->first() : false;

        if (!$user) {
            return false;
        }

        $this->update($user->id, [
            'status' => User::ACTIVE,
            'token_confirm' => null,
        ]);

        return true;
    }

    public function register($inputs, $roleId)
    {
        $user = $this->create($inputs);

        if (!$user) {
            throw new UnknowException('Had errors while processing');
        }

        $user->roles()->attach($roleId);

        // Send email active to user
        $info = [
            'email' => $inputs['email'],
            'subject' => trans('emails.active_subject'),
        ];

        $fields = [
            'linkActive' => action('Frontend\UserController@active', $user->token_confirm),
            'content' => trans('emails.active_account', ['object' => $user->name]),
        ];

        $this->dispatch(new SendEmail($info, User::ACTIVE_LINK_SEND, $fields));

        return $fields;
    }

    /**
     * List all campaigns that the user owns
     * @return Illuminate\Pagination\Paginator
     */
    public function ownedCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)->campaigns()
            ->wherePivot('role_id', app(RoleRepository::class)->getRoleByName(Role::ROLE_OWNER, Role::TYPE_CAMPAIGN)->first())
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all campaigns that the user join
     * @return Illuminate\Pagination\Paginator
     */
    public function joinedCampaign($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)->campaigns()
            ->wherePivotIn('role_id', app(RoleRepository::class)->getRoleByName([
                Role::ROLE_OWNER,
                Role::ROLE_MODERATOR,
                Role::ROLE_MEMBER,
            ], Role::TYPE_CAMPAIGN)->all())
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all user's follower
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollower($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)
            ->followers()
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }

    /**
     * List all user that this user are following
     * @return Illuminate\Pagination\Paginator
     */
    public function listFollowing($id, $orderBy = 'created_at', $direction = 'desc')
    {
        return $this->findOrFail($id)
            ->followings()
            ->orderBy($orderBy, $direction)
            ->simplePaginate();
    }
}
