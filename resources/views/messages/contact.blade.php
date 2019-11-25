    <div class="list-group">

      <a href="{{ URL::route('get.edit.profile') }}" class="list-group-item {{ set_active(['profile/*','profile/*']) }}">
            <img style="height: 30px; float: left; " src="https://graph.facebook.com/v2.4/738995062872059/picture?type=normal" class="img-circle special-img"> &nbsp; JOHN KWONG
      </a>
      <a href="{{ URL::route('get.edit.account') }}" class="list-group-item {{ set_active(['account/*','account/*']) }}">個人帳號</a>
    </div>