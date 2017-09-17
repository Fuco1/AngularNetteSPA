var angular = require('angular');
var ngAdmin = require('ng-admin');
var myApp = angular.module('myApp', [ngAdmin]);

myApp.config(['NgAdminConfigurationProvider', function (nga) {
	var admin = nga.application('Cosmonauts Administration')
		.baseApiUrl('http://localhost:8080/api/');

	var cosmonaut = nga.entity('cosmonauts');
	cosmonaut.listView().fields([
		nga.field('name').isDetailLink(true),
		nga.field('surname'),
		nga.field('dateOfBirth', 'date'),
		nga.field('superpower'),
	]);

	cosmonaut.creationView().fields([
		nga.field('name'),
		nga.field('surname'),
		nga.field('dateOfBirth', 'date'),
		nga.field('superpower'),
	]);

	cosmonaut.editionView().fields(cosmonaut.creationView().fields());

	admin.addEntity(cosmonaut);

	nga.configure(admin);
}]);
