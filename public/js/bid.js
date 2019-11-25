

var vm = new Vue({

	http: {
      root: '/root',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
      }
    },

	el: '#bid',

	data: {
		newbid: {
			price: '',
			Error:[],
		},
	},
	

	methods: {

		

		Addbid: function(id){

			var bid = this.newbid

			// Clear form input
			this.newbid = { price:''}

			// Send post request
			this.$http.post('/api/bid/'+ id, bid).then((data) => {
				
				
				// console.log(errors)
				// Reload page
				location.reload()		   		
				
		    }).catch(function (data, status, request) {
			    var errors = data.data;
			    //this.formErrors = errors.message.price;
			    //this.newbid = { price:'', formErrors:errors.message.price}
			    this.newbid = { price:'', Errors:errors.message.price}
			    // console.log(this.newbid.Errors)
			 });
		    

		},

		Addfree: function(id){

			// Send post request
			this.$http.post('/api/free/'+ id).then((data) => {
				console.log(data)
				// Reload page
				location.reload()		   		
				
		    })

		},
		

	},

	computed: {
		validation: function(){
			return {
				content: !!this.newbid.price.trim(),
			}
		},

		isValid: function () {
			var validation = this.validation
			return Object.keys(validation).every(function (key) {
				return validation[key]
			})
		}

	},

});