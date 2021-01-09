<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
	<head>
		<script>var base_url = '<?php echo base_url() ?>';</script>
		<script type="text/javascript" src="/assets/js/Invoice/invoices.js"> -->
		<!-- </script>
		<style>
			table .edit, table .delete, table .print {
				cursor: grab;
			}
		</style>
	</head>
	<body>
		<div class="card text-center">
			<div class="card-header">
				Invoice
			</div>
			<div class="card-body">
					<input type="text" class="form-control" id="id" placeholder="id" aria-label="id" style="display:none">
					<input type="text" class="form-control" id="transactionId" placeholder="transactionId" aria-label="id" style="display:none">
					<div class="row">
						<div class="col col-sm-4">
							<select class="form-control" id="customerSelect">
								<option selected>Select Customer</option>
							</select>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-4">
							<div class="float-left mt-2 w-100">
								<select class="selectpicker w-100" id="itemSelect" multiple>
									
								</select>
							</div>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-4">
							<input type="text" class="form-control" id="item_price" placeholder="Price" aria-label="Price" disabled>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-4">
							<input type="number" class="form-control" id="amount_paid" placeholder="Amount Paid" aria-label="Amount Paid" required>
						</div>
					</div>
					<div class="row mt-2">
						<div class="col col-sm-4">
							<input type="number" class="form-control" id="total" placeholder="Total" aria-label="Total" disabled>
						</div>
					</div>
					<div class="float-left mt-2">
							<button type="button" id="saveBtn" type="submit" class="btn btn-primary">Save</button>
							<button type="button" id="resetBtn" class="btn btn-secondary">Reset</button>
					</div>
				</br>
				<div class="table-div">
					<table class="table mt-5 table-striped table-hover" id="invoicesTbl">
						<thead class="thead-dark">
							<tr>
								<th scope="col">Invoice Number</th>
								<th scope="col">Customer</th>
								<th scope="col">Paid Amount</th>
								<th scope="col">Total</th>
								<th scope="col">Action</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td colspan="6">No Data Found.</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

	</body>
</html>