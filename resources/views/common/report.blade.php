  


  <div class="modal-body">
  

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">描述:</label>
              {!! Form::textarea('content', null, ['class'=> 'form-control', 'placeholder' => '描述 (可以標明產品原價和規格等資訊)']) !!}
             

        <div class="modal-footer">
          {!! Form::submit( $submitButtonText , ['class' => 'btn btn-primary']) !!}
        </div>

          





           
        </div>
  </div>