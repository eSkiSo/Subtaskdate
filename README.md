SubTask Due Date
===============

- Adds a new due date field for subtasks.
- Adds API Procedures for subtask due date field.

createSubtaskdd
-------------

-  Purpose: **Create a new subtask**
-  Parameters:

   -  **task_id** (integer, required)
   -  **title** (integer, required)
   -  **user_id** (int, optional)
   -  **time_estimated** (int, optional)
   -  **time_spent** (int, optional)
   -  **status** (int, optional)
   -  **due_date** (int, optional)

-  Result on success: **subtask_id**
-  Result on failure: **false**

Request example:

.. code:: json

    {
        "jsonrpc": "2.0",
        "method": "createSubtask",
        "id": 2041554661,
        "params": {
            "task_id": 1,
            "title": "Subtask #1",
            "due_date": 1523998125
        }
    }

Response example:

.. code:: json

    {
        "jsonrpc": "2.0",
        "id": 2041554661,
        "result": 45
    }

updateSubtaskdd
-------------

-  Purpose: **Update a subtask**
-  Parameters:

   -  **id** (integer, required)
   -  **task_id** (integer, required)
   -  **title** (integer, optional)
   -  **user_id** (integer, optional)
   -  **time_estimated** (integer, optional)
   -  **time_spent** (integer, optional)
   -  **status** (integer, optional)
   -  **due_date** (int, optional)

-  Result on success: **true**
-  Result on failure: **false**

Request example:

.. code:: json

    {
        "jsonrpc": "2.0",
        "method": "updateSubtask",
        "id": 191749979,
        "params": {
            "id": 1,
            "task_id": 1,
            "status": 1,
            "time_spent": 5,
            "user_id": 1,
            "due_date": 1523998125
        }
    }

Response example:

.. code:: json

    {
        "jsonrpc": "2.0",
        "id": 191749979,
        "result": true
    }


Author
------

- Manuel Raposo
- License MIT

Requirements
------------

- Kanboard >= 1.0.34
- PHP >= 5.3.3

PS:
  - Commented dashboard hook to work with versions >=1.0.41, if you are using a version prior to that, just uncomment lines 33 and 34
  
Installation
------------

You have the choice between 3 methods:

1. Install the plugin from the Kanboard plugin manager in one click (not yet)
2. Download the zip file and decompress everything under the directory `plugins/Subtaskdate`
3. Clone this repository into the folder `plugins/Subtaskdate`

Note: Plugin folder is case-sensitive.
