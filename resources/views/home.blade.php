@extends('layouts.main')

  @section('content')
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="mt-5">Task Manager</h1>
          <div style="margin-bottom:1rem;">
          <button class="btn btn-primary" data-toggle="modal" data-target="#createModal">Add Task</button>
          </div>
          <table id="tasksTable" class="display">
            <thead>
              <tr>
                <th></th>
                <th>Name</th>
                <th>Created</th>
              </tr>
            </thead>
            <tbody class="sortable" data-entityname="tasks">
            @foreach($tasks as $task)
            <tr data-itemId="{{{ $task->id }}}">
                <td class="sortable-handle"><img src="/images/sort.png" height="15px"></td>
                <td>{{ $task->name }}</td>
                <td>{{ $task->created_at }}</td>
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
          <input type="text" id="newPriority" class="form-control">
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="newTask">Save Task</button>
      </div>
    </div>
  </div>
</div>
  @endsection

  @section('scripts')
  <!-- Initializes DataTable -->
    <script>
    $(document).ready( function () {
    $('#tasksTable').DataTable({
      // "bPaginate": false,
      // "bFilter": false,
    });
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

      $.ajax({
              headers: {
                  'X-CSRF-TOKEN': '{!! csrf_token() !!}'
              },
              data: {
                  name: name,
                  priority: priority,
              },
              method: 'POST',
              url: '{!! route('create_task') !!}',
              success: function(e) {
                  console.log('Success!');
                  $('#createModal').modal('hide');
                  // location.reload();
              },
              error: function(e) {
                console.log('failure', e);
              }
      });
  });
  </script>
  @endsection