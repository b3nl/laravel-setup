var setupControllers = angular.module( "setupControllers", [] );

setupControllers.controller(
	"ConfigCtrlr",
	[
		"$http", "$scope", "$stateParams", "$state", "$controller",
		function( $http, $scope, $stateParams, $state, $controller ) {
			function initScope() {
				angular.extend( $scope, {
					config: {},
					configFields: []
				} );

				$http.get( "/setup/config" ).then( function( response ) {
					angular.forEach( response.data, function( field, key ) {
						$scope.config[ key ] = field.value || field.default;

						$scope.configFields.push( {
							defaultValue: field.default,
							"key": key,
							type: "input",
							templateOptions: {
								id: key,
								label: key
							}
						} );
					} );
				} );
			}

			$scope.submit = function() {
				console.log( $scope.config );
				$http.post( "/setup/config", $scope.config ).then( function( response ) {
					console.log( response );
				} );
			};

			angular.extend(
				this,
				$controller( "DefaultController", { $scope: $scope, $stateParams: $stateParams, $state: $state } )
			);

			initScope();
		}
	]
);
