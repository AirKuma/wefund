

var vm = new Vue({

	http: {
      root: '/root',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
      }
    },

	el: '#post',

	data: {
		//errors:[]
	},
	

	methods: {

		// fetchUser: function(){

		//    this.$http.get('/api/post/all/all').then((data) => {
		//    		data = data.json()
		//    		console.log(data)
		// 		this.$set('posts', data)
		//    })

		// },

		Bookmark: function (id) {
			this.$http.get('/api/bookmark/' + id).then((data) => {
				console.log(data)
			})
			location.reload()
			//this.fetchUser()
		},

		Vote: function (votetype,id) {
			this.$http.get('/api/vote/'+votetype+'/'+ id).then((data) => {
				console.log(data)
			})
			location.reload()
		},

		Billboard: function (type,domain) {
			this.$http.get('/api/post/create/'+type+'/'+domain).then((data) => {
				console.log(data)
			})
			location.reload()
		},


	},


});