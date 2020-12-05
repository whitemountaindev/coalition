@extends('layouts.main')

  @section('content')
    <!-- Page Content -->
    <div class="container">
      <div class="row">
        <div class="col-lg-12">
          <h1 class="mt-5">Task Manager</h1>
          <div style="margin-bottom:1rem;">
          <button class="btn btn-primary">Add Task</button>
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
  @endsection