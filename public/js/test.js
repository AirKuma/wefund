
var emailRE = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/






var vm = new Vue({

	http: {
      root: '/root',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
      }
    },

	el: '#test',
	
	data: {
		newUser: {
			email: '',
			password: ''
		},
		success: false,
		edit: false
	},



	methods: {

		fetchUser: function(){

		   this.$http.get('/api/users').then((data) => {
		   		data = data.json()
		   		// console.log(data.json().users)
				this.$set('users', data)
		   })

		},

		AddNewUser: function(){

			// User input
			var user = this.newUser

			// Clear form input
			this.newUser = {  email:'', address:'' }

			// Send post request
			this.$http.post('/api/users/', user).then((data) => {
			// Show success message
				self = this
				this.success = true
				setTimeout(function () {
					self.success = false
				}, 5000)

				// Reload page
				this.fetchUser()		   		
				
		    })



		},

		RemoveUser: function (id) {
			var ConfirmBox = confirm("Are you sure, you want to delete this User?")

			if(ConfirmBox) this.$http.delete('/api/users/' + id).then((data)=>{
				console.log(data)
				this.fetchUser()
			})

			
		},


		EditUser: function (id) {
			var user = this.newUser
			this.newUser = { id: '', email: '', password: ''}
			this.$http.patch('/api/users/' + id, user).then((data) => {
				console.log(data)
				this.edit = false
				this.fetchUser()
			})

			
		},


		ShowUser: function (id) {
			this.edit = true

			this.$http.get('/api/users/' + id).then((data) => {
				data = data.json()
				this.newUser.id = data.id
				this.newUser.email = data.email
				this.newUser.password = data.password
			})
		}




	},

	computed: {
		validation: function(){
			return {
				email: emailRE.test(this.newUser.email),
				password: !!this.newUser.password.trim()
			}
		},

		isValid: function () {
			var validation = this.validation
			return Object.keys(validation).every(function (key) {
				return validation[key]
			})
		}

	},

	ready: function(){
		
		this.fetchUser()
	}
});