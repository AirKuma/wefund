@extends('layouts.master')

@section('content')

    <div class="container">

      <!-- Example row of columns -->
        <div class="row">
           <div class="col-sm-12" >
            <div class="card card-block" style="background-color: #FFFFFF;">
                @if($errors->has())
                <div class="alert alert-danger">
                   @foreach ($errors->all() as $error)
                      {{ $error }} <br />
                  @endforeach
                </div>
                
                @endif
        <div role="form">
          {!! Form::open(['route' => ['post.billboard.category',$id], 'method' => 'post','files' => true] ) !!}
          <div class="form-group">
          新增類別：
          </div>
           @include('discuss.billboards.forms.category', ['submitButtonText' => '新增'])

          {!! Form::close() !!}
        </div><br>

        <table class="table table-hover toggle_margin">
            <thead>
              <tr>
                <th>分類名稱</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
            @foreach($categories as $category)
              <tr>
                <td>{{ $category->name }}</td>
                <td>
                    <a class="btn btn-warning" data-toggle="modal" data-target="#editCategoryModal-{{ $category->id }}" data-whatever="@mdo">編輯</a>
                    <a class="btn btn-danger" data-toggle="modal" data-target="#deleteCategoryModal-{{ $category->id }}" data-whatever="@mdo">刪除</a>
                </td>
              </tr>

          <!--編輯modal-->
        <div class="modal fade" id="editCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="editCategoryModal-{{ $category->id }}" aria-hidden="true">
          <div class="modal-dialog modal-sm" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                  <span class="sr-only">Close</span>
                </button>
                <h4 class="modal-title" id="editCategoryModal">編輯分類</h4>
              </div>
              {!! Form::model($category, ['method' => 'PATCH', 'action' => ['Discuss\BillboardCategoryController@patchUpdateCategory',$category->id]]) !!}
                 @include('discuss.billboards.forms.category', ['submitButtonText' => '修改'])

                {!! Form::close() !!}
              
            </div>
          </div>
        </div>

          <!--刪除modal-->
          <div class="modal fade" id="deleteCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteItemModal-{{ $category->id }}" aria-hidden="true">
            <div class="modal-dialog modal-sm" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    <span class="sr-only">Close</span>
                  </button>
                  <h4 class="modal-title" id="deleteCategoryModal">刪除分類</h4>
                </div>
            {!! Form::open(['action' => ['Discuss\BillboardCategoryController@destroyCategory', 'id' => $category->id], 'method' => 'delete', 'role' => 'form']) !!}
            <div class="modal-body">
              <h6>確定要刪除該分類？</h6>
                   <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                    {!! Form::submit( '確定' , ['class' => 'btn btn-danger']) !!}
                   </div>

                  {!! Form::close() !!}
               </div> 
              </div>
            </div>
          </div>
            @endforeach
            </tbody>
         </table>

            </div>
            </div>
        </div>

    </div> <!-- /container -->

@endsection