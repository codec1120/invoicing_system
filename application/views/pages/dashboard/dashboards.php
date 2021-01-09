<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<script>var base_url = '<?php echo base_url() ?>';</script>
		<script type="text/javascript" src="/assets/js/Dashboard/dashboards.js">
		</script>
		<style>
			table .edit, table .delete, table .print {
				cursor: grab;
			}
		</style>
	</head>
	<body>
		<div class="card text-center">
			<div class="card-header">
				Dashboard
			</div>
			<div class="card-body">
				<div class="row">
					<div class="col col-sm-2">
						Year: <input type="text" id="datepickerYear" />
					</div>
					<div class="col col-sm-2">
						Month: <input type="text" id="datepickerMonth" />
					</div>
					<div class="col col-sm-2">
						Day: <input type="text" id="datepickerDay" />
					</div>
					<div class="col col-sm-1">
						<button type="button" id="viewBtn" class="mt-3 btn btn-primary">View</button>
					</div>
				</div>
				<div class="table-div mt-5">
						<table class="table table-striped table-hover" id="dashboardTbl">
							<thead class="thead-dark">
								<tr>
									<th scope="col" >Item Name</th>
									<th scope="col" >Sales</th>
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