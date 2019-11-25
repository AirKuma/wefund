

var vm = new Vue({

	http: {
      root: '/root',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
      }
    },

	el: '#subscription',

	data: {

	},
	

	methods: {



		Addsubscription: function(id){

			// Send post request
			this.$http.post('/api/subscription/'+ id).then((data) => {
				console.log(data)
				// Reload page
				location.reload()		   		
				
		    })

		},
		

	},

});