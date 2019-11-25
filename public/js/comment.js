

var vm = new Vue({

	http: {
      root: '/root',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('#token').getAttribute('value')
      }
    },

	el: '#comment',

	data: {
		newComment: {
			content: '',
			anonymous: '0',
		},
		//errors:[]
	},
	

	methods: {

		Addcomment: function(id){

			var comment = this.newComment

			// Clear form input
			this.newComment = { content:'',anonymous:'0'}

			// Send post request
			this.$http.post('/api/comment/'+ id, comment).then((data) => {
				console.log(data)
				// Reload page
				location.reload()		   		
				
		    })
		    // .catch(error => {
      //               error.data.message.forEach(e => {
      //                   this.errors.push(e);
      //         		});
      //         }); 

		},

		AddItemcomment: function(id){

			var comment = this.newComment

			// Clear form input
			this.newComment = { content:''}
			// Send post request
			this.$http.post('/api/itemcomment/'+ id, comment).then((data) => {
				console.log(data)
				// Reload page
				location.reload()		   		
				
		    })

		},

		Votecomment: function (votetype,id) {
			this.$http.get('/api/commentvote/'+votetype+'/'+ id).then((data) => {
				console.log(data)
			})
			//location.reload()
			location.reload(true)
		},

		Editcomment: function (id) {
			var comment = this.newComment

			this.$http.patch('/api/edit/comment/' + id, comment).then((data) => {
				console.log(data)
				location.reload()
			})

			
		},

		

	},

	computed: {
		validation: function(){
			return {
				content: !!this.newComment.content.trim(),
				anonymous: !!this.newComment.anonymous.trim(),
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