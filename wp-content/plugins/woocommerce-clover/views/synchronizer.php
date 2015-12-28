<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Inventory</div>
			<div class="panel-body">

				<button type="button" ng-click="importFullInventory()" class="btn btn-primary">Import Inventory from Clover</button>

				<button type="button" ng-click="exportFullInventory()" class="btn btn-success">Export Inventory to Clover</button>

			</div>
		</div>
	</div>

	<div ng-if="isDevEnv" class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Customers</div>
			<div class="panel-body">

				<div class="form-group" >
					<label for="overrideExistingCustomers">Override existing customers information</label>						
					<input type="checkbox" class="form-control"  name="overrideExistingCustomers" />
				</div>

				<button type="button" ng-click="importFullCustomers()"  class="btn btn-primary">Import Customers from Clover</button>
			</div>
		</div>
	</div>
</div>
<div class="row">
	<div ng-if="isDevEnv" class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading">Orders</div>
			<div class="panel-body">

				<div class="form-group" >
					<label for="importOrdersWithZeroAmount">Import orders with no total ($0)</label>						
					<input type="checkbox" class="form-control"  name="importOrdersWithZeroAmount" />
				</div>

				<button type="button" ng-click="importFullOrders()"  class="btn btn-primary">Import Orders from Clover</button>

				<button type="button" ng-click="exportNotSyncOrders()" class="btn btn-success">Export Not Sync orders to Clover</button>

			</div>
		</div>
	</div>
</div>
