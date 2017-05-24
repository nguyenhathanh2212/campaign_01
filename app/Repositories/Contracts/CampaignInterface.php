<?php

namespace App\Repositories\Contracts;

interface CampaignInterface extends RepositoryInterface
{
    public function create($inputs);
    public function delete($campaignId);
    public function update($campaign, $inputs);
}
