<?php get_header(); ?>
<div class="container">

<form method="post" >
    <input type="text" name="name" placeholder="Name" />
    <input type="email" name="email" placeholder="Email" />
    <input type="text" name="phone" placeholder="Phone" />
    <input type="submit"  ONCLICK="applyJob(event)" />
</form>
	</div>
<script>

function applyJob(event) {
    event.preventDefault();
var name = document.querySelector('input[name="name"]').value;
var email = document.querySelector('input[name="email"]').value;
var phone = document.querySelector('input[name="phone"]').value;
var id = <?php echo $_GET['current_post_id']; ?>;
var data = {
name: name,
email: email,
phone: phone,
id: id,
};

		var xhr = new XMLHttpRequest();
		xhr.open('POST', 'http://localhost:8000/wp-json/hiring/v1/apply/' + id);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify(data));
		xhr.onload = function() {
			if (xhr.status === 200) {
		alert('Job application submitted successfully.');
		} else {
		console.log(xhr);
		alert('Request failed.  Returned status of ' + xhr.status);
		}
		}
}

</script>

<?php get_footer(); ?>
