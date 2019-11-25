  


  <div class="modal-body">
  

            <div class="form-group">
              <label for="message-text" class="control-label sr-only">原因:</label>
              {!! Form::textarea('content', null, ['class'=> 'form-control', 'placeholder' => '原因 (可不填)']) !!}
             

        <div class="modal-footer">
          {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!}
        </div>

          





           
        </div>
  </div>