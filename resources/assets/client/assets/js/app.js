(function () {
    'use strict';

    angular.module('laravel-setup', [
        'ui.router',
        'ngAnimate',

        //foundation
        'foundation',
        'foundation.dynamicRouting',
        'foundation.dynamicRouting.animations',
        'formly',
        'setupControllers'
    ])
        .config(config)
        .config(formlyConfig)
        .run(run)
    ;

    config.$inject = ['$urlRouterProvider', '$locationProvider', '$interpolateProvider'];

    function config($urlProvider, $locationProvider, $interpolateProvider) {
        $urlProvider.otherwise('/');

        $locationProvider.html5Mode({
            enabled: false,
            requireBase: false
        });

        $locationProvider.hashPrefix('!');
    }

    formlyConfig.$inject = ['formlyConfigProvider'];

    function formlyConfig(formlyConfigProvider) {
        formlyConfigProvider.setWrapper({
            name: 'InputWrapper',
            template: '<div class="grid-block"><div class="small-12 medium-6 grid-content"><formly-transclude></formly-transclude></div></div>'
        });

        formlyConfigProvider.setType({
            name: 'input',
            template: '<label for="{{ options.templateOptions.id }}">{{ options.templateOptions.label }}<input data-ng-model="model[options.key]" id="{{ options.templateOptions.id }}" name="{{ options.key }}" required="required" type="text"  />',
            wrapper: 'InputWrapper'
        });
    } // function

    function run() {
        FastClick.attach(document.body);
    }

})();
