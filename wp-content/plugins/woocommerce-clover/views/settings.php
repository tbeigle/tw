
<div class="panel panel-default">
	<div class="panel-heading">Settings</div>
	<div class="panel-body">
		<form role="form"  name="formSettings" ng-submit="saveSettings(settings)">
			<div class="alert alert-danger" ng-show="formSettings.$invalid">
				<span ng-show="formSettings.$error.required">Required elements</span>
				<span ng-show="formSettings.$error.invalid">Invalid elements</span>
			</div>

			<div class="form-group" ng-repeat="setting in settings" ng-class="{'has-error':innerForm.theInput.$error.required}">
				<ng-form name="innerForm">
					<label for="{{setting.name}}">{{setting.label}}</label>						

					<div ng-switch on="setting.type">
						<input ng-switch-when="input" ng-required="{{setting.required}}" type="{{setting.type}}" class="form-control" ng-model="setting.value" name="theInput" />
						<input ng-switch-when="password" ng-required="{{setting.required}}" type="{{setting.type}}" class="form-control" ng-model="setting.value" name="theInput" />
						<select ng-switch-when="dropdown" ng-required="{{setting.required}}"  class="form-control" ng-model="setting.value" name="theInput" ng-options="gOption.id as gOption.name for gOption in setting.options" />
					</div>
					<span ng-if="setting.helpMessage.length > 0" class="help-block">{{setting.helpMessage}}</span>
					<div ng-show="innerForm.theInput.$dirty && formSettings.theInput.$invalid">
						<span class="error" ng-show="innerForm.theInput.$error.required">The field is required.</span>
					</div>
				</ng-form>																											
			</div>
			<div class="alert alert-info">
				<span>If you have any trouble finding the Merchant ID and the API Token, follow the steps we have in 
					<a target="_blank" href="http://www.sitemavens.com/wooclover-help/">this link</a>.
				</span>
			</div>

			<button type="submit" ng-disabled="formSettings.$invalid" class="btn btn-primary">Connect to Clover</button>

		</form>
	</div>
</div>

<div ng-show="config.isValidCredentials" class="panel panel-default">
	<div class="panel-heading">Merchant Configuration</div>
	<div class="panel-body">

		<div class="form-group"  >
			<label for="">Locale:</label>						
			<span ng-bind="merchant.properties.locale"></span>
		</div>
		<div class="form-group"  >
			<label for="">Time Zone:</label>						
			<span ng-bind="merchant.properties.timezone"></span>
		</div>
		<button type="button" ng-click="updateMerchant()" class="btn btn-primary">Update from Clover</button>

	</div>
</div>
