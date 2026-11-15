<?php

namespace App\Policies;

use App\Models\WipScan;
use App\Models\User;

class WipScanPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->can('wip_scans.view') || $user->can('wip_scans.manage');
    }

    public function view(User $user, WipScan $record): bool
    {
        return $this->viewAny($user);
    }

    public function create(User $user): bool
    {
        return $user->can('wip_scans.manage');
    }

    public function update(User $user, WipScan $record): bool
    {
        return $user->can('wip_scans.manage');
    }

    public function delete(User $user, WipScan $record): bool
    {
        return $user->can('wip_scans.manage');
    }
}
