<?php

namespace App\Repositories\Contracts;

interface ActionInterface extends RepositoryInterface
{
    public function createOrDeleteLike($action, $userId);

    public function getActionPhotos($actions);

    public function deleteFromEvent($event);

}
