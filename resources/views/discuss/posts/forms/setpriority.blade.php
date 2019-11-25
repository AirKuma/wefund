        <div class="modal-body">




            <div class="form-group">
            <label for="message-text" class="control-label sr-only">頂置等級</label>
              {!! Form::select('priority',['0' => '無頂置','1' => '頂置低','2' => '頂置中','3' => '頂置高'], null, ['class' => 'c-select']) !!}            

            </div>


          
           

        <div class="modal-footer">
          {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!}
        </div>


           
 </div>
