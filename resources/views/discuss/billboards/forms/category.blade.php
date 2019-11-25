        <div class="modal-body">


           
            <div class="form-group">
              <label for="message-text" class="control-label sr-only">類別名稱:</label>
              {!! Form::text('name', null, ['placeholder' => '類別名稱']) !!}        
            </div>

            <div class="form-group">

              {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!}        
            </div>

       

    
        </div>