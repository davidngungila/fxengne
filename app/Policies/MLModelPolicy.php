<?php

namespace App\Policies;

use App\Models\MLModel;
use App\Models\User;

class MLModelPolicy
{
    /**
     * Determine if the user can view the model.
     */
    public function view(User $user, MLModel $mlModel): bool
    {
        return $user->id === $mlModel->user_id;
    }

    /**
     * Determine if the user can update the model.
     */
    public function update(User $user, MLModel $mlModel): bool
    {
        return $user->id === $mlModel->user_id;
    }

    /**
     * Determine if the user can delete the model.
     */
    public function delete(User $user, MLModel $mlModel): bool
    {
        return $user->id === $mlModel->user_id;
    }
}
