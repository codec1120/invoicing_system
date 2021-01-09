<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<script>var base_url = '<?php echo base_url() ?>';</script>
		<script type="text/javascript" src="/assets/js/item/Items.js">
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
				Item
			</div>
			<div class="card-body">
					<input type="text" class="form-control" id="id" placeholder="id" aria-label="id" style="display:none">
					<div class="row">
						<div class="col col-sm-4">
							<input type="text" class="form-control" id="item_name" placeholder="Item name" aria-label="Item name" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-4">
							<input type="number" class="form-control" id="item_price" placeholder="Item Price" aria-label="Item Price">
						</div>
					</div>
					<div class="float-left mt-2">
							<button type="button" id="saveBtn" type="submit" class="btn btn-primary">Save</button>
							<button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
					</div>
				</br>
				<div class="table-div mt-5">
					<table class="table table-striped table-hover" id="itemsTbl">
						<thead class="thead-dark">
							<tr>
								<th scope="col" >Item ID</th>
								<th scope="col" >Item Name</th>
								<th scope="col" >Price</th>
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