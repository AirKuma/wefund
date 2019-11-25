        <div class="modal-body">
            {!! Form::hidden('type', $type) !!}

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">討論版</label>
                <div class="dropdown">  <!-- dropdown 加 margin-bottom：10px -->
                 <button type="button" class="btn dropdown-toggle" id="rank" 
                    data-toggle="dropdown" @if(Request::segment(3)=='edit') disabled="disabled" @endif>  
                    @if($domain!='all' && $billboards->where('domain',$domain)->first()!=null)               
                      {{ $billboards->where('domain',$domain)->first()->name }}
                    @else
                      選擇討論版 
                    @endif   
                    <span class="caret"></span>
                 </button>
                 <ul class="dropdown-menu" role="menu" aria-labelledby="rank">
                  @foreach($billboards as $billboard)
                    <li role="presentation">
                       <a href="{{ $billboard->domain }}" class="dropdown-item">{{ $billboard->name }}</a>
                    </li>
                  @endforeach
                 </ul>
             </div>
            </div>   
            @if($domain!='all'&& $billboards->where('domain',$domain)->first()!=null)               
              {!! Form::hidden('billboard_id', $billboards->where('domain',$domain)->first()->id) !!}
            @endif

            @if($domain!='all' && $billboards->where('domain',$domain)->first()!=null)
            <div class="form-group">
            <label for="message-text" class="control-label sr-only">分類</label>
            @if(Request::segment(3)=='create')
              {!! Form::select('category_id',$categories, null, ['placeholder' => '無分類', 'class' => 'c-select']) !!}            
            @else
              {!! Form::select('category_id',$categories, $post->billboards()->first()!=null ? $post->billboards()->first()->id : null, ['placeholder' => '無分類', 'class' => 'c-select']) !!}            
            @endif
            <span class="help-block text-danger"> ※若無法分類可以不選。</span>
            </div>
            @endif

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">標題:</label>
              {!! Form::text('title', null, ['class'=> 'form-control', 'placeholder' => '標題，上限100個字']) !!}
            </div>

            @if($type=="link")
            <div class="form-group">
              <label for="message-text" class="control-label sr-only">連結:</label>
              {!! Form::text('link', null, ['class'=> 'form-control', 'placeholder' => '連結']) !!}
            </div>
            @endif

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">內容:</label>
              {!! Form::textarea('content', null, ['class'=> 'form-control', 'placeholder' => '內容']) !!}
              @if($type=="link")<p class="help-block text-danger">※上限255個字。</p> @endif
            </div>

           @if($domain!='all' && $billboards->where('domain',$domain)->first()!=null)   
             @if($billboards->where('domain',$domain)->first()->anonymous==0)
              <div class="form-group">
                    {!! Form::label('anonymous', '選擇是否匿名') !!}
                    <label class="c-input c-radio">
                      {!! Form::radio('anonymous', '0', true, ['id' => 'public', 'name' => 'anonymous']) !!}
                      <span class="c-indicator"></span>
                      不匿名
                    </label>
                    <label class="c-input c-radio">
                      {!! Form::radio('anonymous', '1', null, ['id' => 'private', 'name' => 'anonymous']) !!}
                      <span class="c-indicator"></span>
                      匿名
                    </label>
              </div> 
              @endif
            @endif
           

        <div class="modal-footer">
          {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!}
        </div>


           
 </div>
