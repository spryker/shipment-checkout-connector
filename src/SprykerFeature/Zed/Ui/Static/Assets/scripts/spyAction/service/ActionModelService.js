'use strict';



/**
 * Basic ActionModel service
 * @ngdoc service
 * @name ActionModelService
 * @kind function
 * @param {string} url The ActionModel source url
 * @returns {$resource}
 */
require('Ui').ng
	.module('spyAction')
	.factory('ActionModelService', [
		'$resource',
		'$q',
		'JSONModelDenormalizeService',

		function($resource, $q, denormalizeResponse) {

			return function(url) {
				return $resource(url, {}, {
					get : {
						method : 'get',
						isArray : false,
						transformResponse : denormalizeResponse,
						interceptor : {
							response : function(response) {
								if (status >= 400) return $q.reject(response);

								return response;
							}
						}
					}
				});
			};
		}
	]);