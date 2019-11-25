        <div class="modal-body">

          <div class="form-group">
          <label for="message-text" class="control-label sr-only">分類</label>
          {!! Form::select('category_id',$category, null, ['placeholder' => '刊登分類', 'class' => 'c-select']) !!}            
          
          @if($auction == 'bid')
          <label class="c-input c-checkbox">
          <!-- <input type="checkbox"> -->
          {!! Form::hidden('new', false) !!}
          {!! Form::checkbox('new', true) !!}
          <span class="c-indicator"></span>
          全新項目
          </label>

          <label class="c-input c-checkbox">
          <!-- <input type="checkbox"> -->
          {!! Form::hidden('free', false) !!}
          @if(Route::currentRouteName() == 'get.auction.item.edit')
            {!! Form::checkbox('free',true,null,['id' => 'free','onclick'=> 'javascript: return false;']) !!}
          @else
            {!! Form::checkbox('free',true,null,['id' => 'free','onclick'=> 'hide_price(this)']) !!}
          @endif
          <span class="c-indicator"></span>
          免費贈送
          </label>
          @endif
          </div>   



            <div class="form-group">
              <label for="message-text" class="control-label sr-only">名稱:</label>
              {!! Form::text('name', null, ['class'=> 'form-control', 'placeholder' => '名稱，上限100個字']) !!}
            </div>


            <span id="price">
            <div class="input-group">
              <span class="input-group-addon">$</span>
              @if(Route::currentRouteName() == 'get.auction.item.edit' && $item->users()->count()!=0)
                {!! Form::text('price', null, ['class'=> 'form-control', 'aria-label' => '', 'placeholder' => '價格','disabled' => 'disabled']) !!}
                {!! Form::hidden('edit',0) !!}
              @else  
                {!! Form::text('price', null, ['class'=> 'form-control', 'aria-label' => '', 'placeholder' => '價格']) !!}
              @endif
              <span class="input-group-addon">.00</span>
            </div>
            <p class="help-block text-danger">※請填入底價</p>
            <br/>
          </span>


            <div class="form-group">
              <label for="message-text" class="control-label sr-only">描述:</label>
              {!! Form::textarea('description', null, ['class'=> 'form-control', 'placeholder' => '描述 (可以標明產品原價和規格等資訊)']) !!}
             
            </div>
            <div class="form-group">
                  {!! Form::label('target', '指定性別') !!}
                  <label class="c-input c-radio">
                    {!! Form::radio('target', '0', true, ['id' => 'all', 'name' => 'target']) !!}
                    <span class="c-indicator"></span>
                    全部
                  </label>
                  <label class="c-input c-radio">
                    {!! Form::radio('target', '1', null, ['id' => 'male', 'name' => 'target']) !!}
                    <span class="c-indicator"></span>
                    男
                  </label>
                  <label class="c-input c-radio">
                    {!! Form::radio('target', '2', null, ['id' => 'female', 'name' => 'target']) !!}
                    <span class="c-indicator"></span>
                    女
                  </label> 
             </div> 



<!--                         <div class="form-group">
                            {!! Form::label('day', '刊登時間') !!}
                            <label class="c-input c-radio">
                            {!! Form::radio('day', '7', null) !!}
                            <span class="c-indicator"></span>
                            ７天
                            </label>
                            <label class="c-input c-radio">
                            {!! Form::radio('day', '14', true) !!}
                            <span class="c-indicator"></span>
                            14 天
                            </label> 
                            <label class="c-input c-radio">
                            {!! Form::radio('day', '30', null) !!}
                            <span class="c-indicator"></span>
                            30 天
                            </label> 
                        </div>  -->
            
            @if(Route::currentRouteName() != 'get.auction.item.edit')
            <div class="form-group">
              圖片 <br />
              <label class="file">
              {!! Form::file('image[]', ['multiple' => true], ['class'=> 'file-custom', 'id' => 'file']) !!}
              <span class="file-custom"></span>
              </label>
              <!-- <input type="text" class="form-control" id="recipient-name"> -->
              <p class="help-block text-danger">※圖片最多只能上傳三張，可選取多張上傳，大小上限10 mb @if($auction=='bid')，至少需要上傳一張圖片@endif。</p>
            </div>
            @endif

        <div class="modal-footer">
          {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!}
        </div>

          





           
        </div>

<script type="text/javascript">
    function hide_price(source) {
        if(source.checked)
          $("#price").hide();
        else
          $("#price").show();
    }
    if(document.getElementById('free').checked)
      $("#price").hide();
 </script> 