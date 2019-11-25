        <div class="modal-body">


            <div class="form-group">
              <label for="message-text" class="control-label sr-only">討論版名稱:</label>
              @if(Request::segment(3)=='edit')
              {!! Form::text('name', null, ['class'=> 'form-control', 'placeholder' => '討論版名稱，上限20個字','disabled' => 'disabled']) !!}
              @else
              {!! Form::text('name', null, ['class'=> 'form-control', 'placeholder' => '討論版名稱，上限20個字']) !!}
              @endif
              <p class="help-block text-danger">※請注意！討論版新增後，名稱不能於日後修改。</p>
            </div>

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">討論版描述:</label>
              {!! Form::textarea('description', null, ['class'=> 'form-control', 'placeholder' => '討論版描述 (可以標明板規和其他資訊)']) !!}          
            </div>

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">討論版domain:</label>
              @if(Request::segment(3)=='edit')
              {!! Form::text('domain', null, ['class'=> 'form-control', 'placeholder' => '討論版domain','disabled' => 'disabled']) !!}
              @else
              {!! Form::hidden('all', 'all') !!}
              {!! Form::text('domain', null, ['class'=> 'form-control', 'placeholder' => '討論版domain']) !!}
              @endif
              <p class="help-block text-danger">※請注意！domain只能是英文，且日後不能修改。</p>
            </div>

            <div class="form-group">
                  {!! Form::label('type', '討論版型態') !!}
                  <label class="c-input c-radio">
                    {!! Form::radio('type', '0', true, ['id' => 'public', 'name' => 'type']) !!}
                    <span class="c-indicator"></span>
                    公開
                  </label>
                  <label class="c-input c-radio">
                    {!! Form::radio('type', '1', null, ['id' => 'private', 'name' => 'type']) !!}
                    <span class="c-indicator"></span>
                    私密
                  </label>
            </div> 

            <div class="form-group">
                  {!! Form::label('target', '指定性別') !!}
                  <label class="c-input c-radio">
                    @if(Request::segment(3)=='edit')
                    {!! Form::radio('target', '0', true, ['id' => 'all', 'name' => 'target','onclick' => 'javascript: return false;']) !!}
                    @else
                    {!! Form::radio('target', '0', true, ['id' => 'all', 'name' => 'target']) !!}
                    @endif
                    <span class="c-indicator"></span>
                    全部
                  </label>
                  <label class="c-input c-radio">
                    @if(Request::segment(3)=='edit' || Auth::user()->gender==0)
                    {!! Form::radio('target', '1', null, ['id' => 'male', 'name' => 'target','onclick' => 'javascript: return false;']) !!}
                    @else
                    {!! Form::radio('target', '1', null, ['id' => 'male', 'name' => 'target']) !!}
                    @endif
                    <span class="c-indicator"></span>
                    男
                  </label>
                  <label class="c-input c-radio">
                    @if(Request::segment(3)=='edit' || Auth::user()->gender==1)
                    {!! Form::radio('target', '2', null, ['id' => 'female', 'name' => 'target','onclick' => 'javascript: return false;']) !!}
                    @else
                    {!! Form::radio('target', '2', null, ['id' => 'female', 'name' => 'target']) !!}
                    @endif
                    <span class="c-indicator"></span>
                    女
                  </label> 
                  <p class="help-block text-danger">※請注意！性別只能定自己性別，且日後不能修改。</p>
             </div> 

             <div class="form-group">
                  {!! Form::label('anonymous', '匿名設定') !!}
                  <label class="c-input c-radio">
                    @if(Request::segment(3)=='edit')
                      {!! Form::radio('anonymous', '0', true, ['id' => 'all', 'name' => 'anonymous','onclick' => 'javascript: return false;']) !!}
                    @else
                      {!! Form::radio('anonymous', '0', true, ['id' => 'all', 'name' => 'anonymous']) !!}
                    @endif
                    <span class="c-indicator"></span>
                    選擇性匿名
                  </label>
                  <label class="c-input c-radio">
                    @if(Request::segment(3)=='edit')
                      {!! Form::radio('anonymous', '1', null, ['id' => 'male', 'name' => 'anonymous','onclick' => 'javascript: return false;']) !!}
                    @else
                      {!! Form::radio('anonymous', '1', null, ['id' => 'male', 'name' => 'anonymous']) !!}
                    @endif
                    <span class="c-indicator"></span>
                    匿名
                  </label>
                  <label class="c-input c-radio">
                    @if(Request::segment(3)=='edit')
                      {!! Form::radio('anonymous', '2', null, ['id' => 'female', 'name' => 'anonymous','onclick' => 'javascript: return false;']) !!}
                    @else
                      {!! Form::radio('anonymous', '2', null, ['id' => 'female', 'name' => 'anonymous']) !!}
                    @endif
                    <span class="c-indicator"></span>
                    不匿名
                  </label> 
                  <p class="help-block text-danger">※請注意！匿名設定日後不能修改。</p>
             </div> 

           <label class="c-input c-checkbox">
              {!! Form::hidden('adult', false) !!}
              {!! Form::checkbox('adult', true) !!}
              <span class="c-indicator"></span>
              討論版為18禁
           </label>

           <label class="c-input c-checkbox">
              {!! Form::hidden('limit_college', false) !!}
              {!! Form::checkbox('limit_college', true) !!}
              <span class="c-indicator"></span>
              限定@if(Request::segment(3)=='create'){{ Auth::user()->college()->first()->name }}@else{{ $billboard->college()->first()->name }}@endif學生發言
           </label>


        <div class="modal-footer">
          {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!}
        </div>

    
        </div>