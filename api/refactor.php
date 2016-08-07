//Controller
btn.click(function(){
	Ion.request({
		fetch-query
	})
	.onComplete(function(JsonResult result){
		DAO dao = new DAO;
		List<User> users = dao.getUsers(result);

		myadapter(users, Fragment)

	})
	.onError(function(){

	})
	.onProgress(function(){

	});
});


//Model
User {
	String name;
	String address;
}

//Adapter or DAO
public List<User> getUsers(JsonResult result) {
	List<User> users = new ArrayList();
	for(int x = 0; x < result.size; x++) {
		mre
	}
	return users;
}
