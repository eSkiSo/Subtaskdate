<?php

namespace Kanboard\Plugin\Subtaskdate\Api\Procedure;

use Kanboard\Api\Authorization\SubtaskAuthorization;
use Kanboard\Api\Authorization\TaskAuthorization;
use Kanboard\Api\Procedure\BaseProcedure;

/**
 * Subtask API controller
 *
 * @package  Kanboard\Api\Procedure
 * @author   Frederic Guillot
 */
class NewSubtaskProcedure extends BaseProcedure
{
    public function createSubtaskdd($task_id, $title, $user_id = 0, $time_estimated = 0, $time_spent = 0, $status = 0, $due_date = 0)
    {
        TaskAuthorization::getInstance($this->container)->check('subtaskProcedure', 'createSubtask', $task_id);
        
        $values = array(
            'title' => $title,
            'task_id' => $task_id,
            'user_id' => $user_id,
            'time_estimated' => $time_estimated,
            'time_spent' => $time_spent,
            'status' => $status,
            'due_date' => $due_date,
        );

        list($valid, ) = $this->subtaskValidator->validateCreation($values);
        return $valid ? $this->subtaskModel->create($values) : false;
    }
    
    public function updateSubtaskdd($id, $task_id, $title = null, $user_id = null, $time_estimated = null, $time_spent = null, $status = null, $due_date = null)
    {
        TaskAuthorization::getInstance($this->container)->check($this->getClassName(), 'updateSubtask', $task_id);
        
        $values = array(
            'id' => $id,
            'task_id' => $task_id,
            'title' => $title,
            'user_id' => $user_id,
            'time_estimated' => $time_estimated,
            'time_spent' => $time_spent,
            'status' => $status,
            'due_date' => $due_date,
        );
        foreach ($values as $key => $value) {
            if (is_null($value)) {
                unset($values[$key]);
            }
        }
        list($valid, ) = $this->subtaskValidator->validateApiModification($values);
        return $valid && $this->subtaskModel->update($values);
    }

}
