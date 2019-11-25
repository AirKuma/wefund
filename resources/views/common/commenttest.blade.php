  


  <div class="modal-body">
  

            <div class="form-group">
              {!! Form::textarea('content', null, ['class'=> 'form-control', 'placeholder' => '', 'size' => '5x3','v-model' => 'newComment.content']) !!}
             

        <div class="modal-footer">
          <!-- {!! Form::submit( $submitButtonText , ['class' => 'btn btn-secondary']) !!} -->
          <button :disabled="!isValid" @click="Editcomment({{ $comments->id }})" class="btn btn-secondary" type="submit" >儲存</button>
        </div>

          





           
        </div>
  </div>