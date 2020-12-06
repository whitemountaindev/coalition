@extends('layouts.main')

  @section('content')
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="mt-5">Task Manager</h1>
          <div style="margin-bottom:1rem;">
          <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add Task</button>
          <button class="btn btn-primary" data-toggle="modal" data-target="#projectModal">Add Project</button>
          </div>
          <table id="tasksTable" class="display">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Project</th>
                <th>Created</th>
                <th></th>
              </tr>
            </thead>
            <tbody class="sortable" data-entityname="tasks">
            @foreach($tasks as $task)
            <tr data-itemId="{{ $task->id }}">
                <td class="sortable-handle"><img src="/images/sort.png" height="15px"></td>
                <td>{{ $task->name }}</td>
                @if($task->project != null)
                <td>{{ $task->project->name }}</td>
                @else
                <td></td>
                @endif
                <td>{{ $task->created_at }}</td>
                <td>
                  <a href="#" class="editTaskModalLink" data-target="#editModal" data-toggle="modal" data-id="{{ $task->id }}" data-name="{{ $task->name }}" data-priority="{{ $task->position }}" data-project="{{ $task->project->id }}" style="margin-left: 5px; text-decoration: none;">
                    Edit</span>
                  </a>
                  <a class="delete-link" href="{{ route('delete_task', ['id' => $task->id]) }}" style="margin-left: 5px; text-decoration: none;">
                  Delete</span>
                  </a>
                </td>
              </tr>
            @endforeach
            </tbody>
          </table>

        </div>
      </div>
    </div>

<!-- Create Task Modal -->
<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createModalLabel">Create New Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="newTaskName">Task Name</label>
          <input type="text" id="newTaskName" class="form-control">
          <label for="newPriority">Task Priority</label>
          <input type="number" id="newPriority" class="form-control" min="1">
          <label for="newProject">Task Project</label>
          <select name="newProject" id="newProject" class="form-control">
            @foreach($projects as $project)
              <option value="{{ $project->id }}"> {{ $project->name }}</option>
            @endforeach
          </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="newTask">Save Task</button>
      </div>
    </div>
  </div>
</div>

<!-- Edit Task Modal -->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editModalLabel">Create New Task</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="editTaskName">Task Name</label>
          <input type="text" id="editTaskName" class="form-control">
          <label for="editPriority">Task Priority</label>
          <input type="number" id="editPriority" class="form-control" min="1">
          <label for="editProject">Task Project</label>
          <select id="editProject" class="form-control">
            @foreach($projects as $project)
              <option value="{{ $project->id }}">{{ $project->name }}</option>
            @endforeach
          </select>
          <input type="hidden" id="hidden_edit_id">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="editTask">Save Changes</button>
      </div>
    </div>
  </div>
</div>

<!-- Create Project Modal -->
<div class="modal fade" id="projectModal" tabindex="-1" role="dialog" aria-labelledby="projectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="projectModalLabel">Create New Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label for="projectName">Project Name</label>
          <input type="text" id="projectName" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="newProjectButton">Save Task</button>
      </div>
    </div>
  </div>
</div>
  @endsection

  @section('scripts')
  <!-- Initializes DataTable -->
    <script>
      $(document).ready(function() {
       $('#tasksTable').DataTable( {
          } );
      } );
    </script>

  <!-- Initializes Sortable Trait -->
  <script>
    /**
     *
     * @param type string 'insertAfter' or 'insertBefore'
     * @param entityName
     * @param id
     * @param positionId
     */
    var changePosition = function(requestData){
        $.ajax({
            'url': '/sort',
            'type': 'POST',
            'data': requestData,
            'success': function(data) {
                if (data.success) {
                    console.log('Saved!');
                } else {
                    console.error(data.errors);
                }
            },
            'error': function(){
                console.error('Something wrong!');
            }
        });
    };

    $(document).ready(function(){
        var $sortableTable = $('.sortable');
        if ($sortableTable.length > 0) {
            $sortableTable.sortable({
                handle: '.sortable-handle',
                axis: 'y',
                update: function(a, b){

                    var entityName = $(this).data('entityname');
                    var $sorted = b.item;

                    var $previous = $sorted.prev();
                    var $next = $sorted.next();

                    if ($previous.length > 0) {
                        changePosition({
                            parentId: $sorted.data('parentid'),
                            type: 'moveAfter',
                            entityName: entityName,
                            id: $sorted.data('itemid'),
                            positionEntityId: $previous.data('itemid')
                        });
                    } else if ($next.length > 0) {
                        changePosition({
                            parentId: $sorted.data('parentid'),
                            type: 'moveBefore',
                            entityName: entityName,
                            id: $sorted.data('itemid'),
                            positionEntityId: $next.data('itemid')
                        });
                    } else {
                        console.error('Something wrong!');
                    }
                },
                cursor: "move"
            });
        }
    });
  </script>

<!-- Create new task -->
  <script type="text/javascript">
  $("#newTask").on("click", function(){

      name = $('#newTaskName').val();
      priority = $('#newPriority').val();
      project = $('#newProject').val();

      $.ajax({
              headers: {
                  'X-CSRF-TOKEN': '{!! csrf_token() !!}'
              },
              data: {
                  name: name,
                  priority: priority,
                  project: project,
              },
              method: 'POST',
              url: '{!! route('create_task') !!}',
              success: function(e) {
                  console.log('Success!');
                  $('#createModal').modal('hide');
                  location.reload();
              },
              error: function(e) {
                console.log('failure', e);
              }
      });
  });
  </script>

<!-- Pass Task Object Values To Edit Modal -->
  <script>
    $('.editTaskModalLink').click(function(e) {
        var link             = $(this);
        var id               = link.data("id")
        var name             = link.data("name")
        var project          = link.data("project")
        //One issue I didn't quite have time to work through is this priority variable. When sorting Tasks with drag-and-drop the database is automatically updated to reflect the change. But when the page is loaded, the $task->position will not change until a page reload. If I had another half-hour or so I could really get this polished, but I believe I've spent too much time on it already.

        var priority         = link.data("priority")

        $("#hidden_edit_id").val(id);
        $('#editTaskName').val(name)
        $('#editProject').val(project)
        $('#editPriority').val(priority)
    });
  </script>

  <!-- Update task -->
  <script type="text/javascript">
  $("#editTask").on("click", function(){

      name = $('#editTaskName').val();
      priority = $('#editPriority').val();
      project = $('#editProject').val();
      id = $('#hidden_edit_id').val();
  

      $.ajax({
              headers: {
                  'X-CSRF-TOKEN': '{!! csrf_token() !!}'
              },
              data: {
                  name: name,
                  priority: priority,
                  project: project,
                  id: id,
              },
              method: 'PATCH',
              url: '{!! route('edit_task') !!}',
              success: function(e) {
                  console.log('Success!');
                  $('#createModal').modal('hide');
                  location.reload();
              },
              error: function(e) {
                console.log('failure', e);
              }
      });
  });
  </script>

  <!-- Create new project -->
  <script type="text/javascript">
  $("#newProjectButton").on("click", function(){

      name = $('#projectName').val();

      $.ajax({
              headers: {
                  'X-CSRF-TOKEN': '{!! csrf_token() !!}'
              },
              data: {
                  name: name,
              },
              method: 'POST',
              url: '{!! route('create_project') !!}',
              success: function(e) {
                  console.log('Success!');
                  $('#projectModal').modal('hide');
                  location.reload();
              },
              error: function(e) {
                console.log('failure', e);
              }
      });
  });
  </script>
  @endsection