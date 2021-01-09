<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<script>var base_url = '<?php echo base_url() ?>';</script>
		<script type="text/javascript" src="/assets/js/User/user.js">
		</script>
		<style>
			table .edit, table .delete {
				cursor: grab;
			}
		</style>
	</head>
	<body>
		<div class="card text-center">
			<div class="card-header">
				Users
			</div>
			<div class="card-body">
					<input type="text" class="form-control" id="id" placeholder="id" aria-label="id" style="display:none">
					<div class="row">
						<div class="col col-sm-4">
							<div class="float-left mt-2">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="" id="customerChk">
									<label class="form-check-label" for="customerChk">
										Customer
									</label>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col col-sm-4">
							<input type="text" class="form-control" id="first_name" placeholder="First name" aria-label="First name" required>
						</div>
						<div class="col col-sm-4">
							<input type="text" class="form-control" id="last_name" placeholder="Last name" aria-label="Last name" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-8">
							<input type="text" class="form-control" id="address" placeholder="Address" aria-label="Address">
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-4">
							<input type="email" class="form-control" id="email" placeholder="Email" aria-label="Email" required>
						</div>
						<div class="col col-sm-4">
							<input type="password" class="form-control" id="password" placeholder="Password" aria-label="Password" required>
						</div>
					</div>
					<div class="row mt-2 mb-5 w-50 float-right">
						<div class="col col-sm-4">
							<button type="button" id="saveBtn" type="submit" class="btn btn-primary">Save</button>
							<button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
						</div>
				</div>
				<div class="table-div">
					<table class="table mt-5 table-striped table-hover" id="usersTbl">
						<thead class="thead-dark">
							<tr>
								<th scope="col" >User ID</th>
								<th scope="col" >First Name</th>
								<th scope="col" >Last Name</th>
								<th scope="col">Address</th>
								<th scope="col">Email</th>
								<th scope="col"  style="display:none">Password</th>
								<th scope="col" >Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="4">No Data Found.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</body>
</html>