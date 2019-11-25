	<script type="text/javascript">
		$(document).ready(function() {
				$("#notificationLink").click(function() {
					$("#notificationContainer").fadeToggle(300);
					$("#notification_count").fadeOut("slow");
					return false;
				});

			//Document Click hiding the popup 
			$(document).click(function() {
				$("#notificationContainer").hide();
			});

			//Popup on click
			$("#notificationContainer").click(function() {
				return false;
			});
			
			$(window).resize(function() {
				
				  ///alert('Resize...');
			});


		});
	</script>



	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script type="text/javascript" src="{{ asset('assets/js/bootstrap.min.js') }}"></script>


<a href="https://www.facebook.com/messages/100012727386797" class="bugreport bugIcon" title="錯誤回報" target="_blank"></a>

  </body>
</html>