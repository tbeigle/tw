<div class="row">
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Customers</div>
			<div class="panel-body">

				<button type="button" ng-click="getCustomers()" class="btn btn-primary">Get Customers</button>

				<div class="form-group"  >
					<label for="">Customer ID</label>						
					<input  type="text" class="form-control" ng-model="customer.id"  />
				</div>
				<div class="form-group"  >
					<label for="">First Name</label>						
					<input  type="text" class="form-control" ng-model="customer.firstName"  />
				</div>
				<div class="form-group"  >
					<label for="">Last Name</label>						
					<input  type="text" class="form-control" ng-model="customer.lastName"  />
				</div>
				<button type="button" ng-click="updateCustomerName()" class="btn btn-primary">Update Customer Name</button>
			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Orders</div>
			<div class="panel-body">
				<button type="button" ng-click="getOrders()" class="btn btn-primary">Read Orders</button>
				<button type="button" ng-click="createOrder()" class="btn btn-primary">Create Order</button>

			</div>
		</div>
	</div>
	<div class="col-md-4">
		<div class="panel panel-default">
			<div class="panel-heading">Inventory</div>
			<div class="panel-body">

				<button type="button" ng-click="getItems()" class="btn btn-primary">Get Items</button>
				<button type="button" ng-click="getCategories()" class="btn btn-success">Get Categories</button>

				<div class="form-group"  >
					<label for="">Item ID</label>						
					<input  type="text" class="form-control" ng-model="item.id"  />
				</div>

				<button type="button" ng-click="importItem()" ng-disabled="!item.id" class="btn btn-primary">Import Item</button>
				<button type="button" ng-click="importAllItems()" class="btn btn-primary">Import All Items</button>

			</div>
		</div>
	</div>


</div>
<div class="row">
	<div class="col-lg-12">
		<pre style="outline: 1px solid #ccc; padding: 5px; margin: 5px;" >
			{{result| json}}
		</pre>

	</div>
</div>