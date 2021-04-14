<?php

namespace App\Observers;

use App\Models\Log;

class LogObserver
{
	/**
	 * Handle events after all transactions are committed.
	 *
	 * @var bool
	 */
	public $afterCommit = true;


	/**
	 * Listen models create or update events
	 *
	 * @param $model
	 * @return void
	 */
	public function save($model)
	{
		if(!$model->getOriginal()) {
			$action = "created";
		} else {
			$action = "updated";
		}

		$this->saveLog($model, $action);
	}

    /**
     * Handle the Log "created" event.
	 *
     * @param $model
     * @return void
     */
   /* public function created($model)
    {
		$this->saveLog($model, 'created');
    }*/

    /**
     * Handle the Log "updated" event.
     *
	 * @param $model
     * @return void
     */
    /*public function updated($model)
    {
		$this->saveLog($model, 'updated');
    }*/

    /**
     * Handle the Log "deleted" event.
     *
	 * @param $model
     * @return void
     */
    public function deleted($model)
    {
		$this->saveLog($model, 'deleted');
    }

	/**
	 * @param $model
	 * @param $action
	 */
    private function saveLog($model, $action)
	{
		if(auth()->guard('panel')->check()) {
			Log::create([
				'user_id' => auth()->user()->id,
				'action' => $action,
				'action_model' => get_class($model),
				'action_id' => $model->id,
				'old_data' => json_encode($model->getOriginal()),
				'new_data' => $model,
				'ip' => request()->ip(),
				'created_at' => now(),
				'updated_at' => now()
			]);
		}
	}
}
