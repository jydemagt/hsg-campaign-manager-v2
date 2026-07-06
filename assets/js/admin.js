/**
 * HSG Campaign Manager
 * Admin
 */

(function ($) {

	'use strict';

	/**
	 * Campaign model.
	 */
	class CampaignModel {

		constructor() {

			this.reset();

		}

		reset() {

			this.data = {

				id: 0,

				general: {
					title: '',
					status: 'draft',
					priority: 10,
					start_date: '',
					end_date: ''
				},

				conditions: {
					products: [],
					categories: [],
					product_tags: [],
					customer_roles: []
				},

				rule: {
					type: 'fixed_price',
					value: ''
				},

				coupon: {
					enabled: false,
					code: ''
				},

				advanced: {
					stop_processing: false,
					usage_limit: '',
					notes: ''
				}

			};

		}

		set(data) {

			this.data = $.extend(
				true,
				{},
				this.data,
				data
			);

		}

		get() {

			return this.data;

		}

	}

	/**
	 * API Client.
	 */
	class ApiClient {

		post(action, data = {}) {

			return $.post(

				hsgcmAdmin.ajaxUrl,

				$.extend(
					{
						action: action,
						nonce: hsgcmAdmin.nonce
					},
					data
				)

			);

		}

	}

	/**
	 * Notifications.
	 */
	class Notification {

		success(message) {

			this.remove();

			$('.hsgcm-form').prepend(
				'<div class="notice notice-success hsgcm-message"><p>' +
				message +
				'</p></div>'
			);

		}

		error(message) {

			this.remove();

			$('.hsgcm-form').prepend(
				'<div class="notice notice-error hsgcm-message"><p>' +
				message +
				'</p></div>'
			);

		}

		remove() {

			$('.hsgcm-message').remove();

		}

	}

	/**
	 * Main controller.
	 */
	class CampaignEditor {

		constructor() {

			this.model = new CampaignModel();

			this.api = new ApiClient();

			this.notify = new Notification();

			this.form = $('#hsgcm-campaign-form');

			if (!this.form.length) {
				return;
			}

			this.bindEvents();

		}

		bindEvents() {

			this.form.on(
				'submit',
				this.save.bind(this)
			);

		}

		save(e) {

			e.preventDefault();

			this.readForm();

			this.api.post(

				'hsgcm_save_campaign',

				{
					campaign: this.model.get()
				}

			).done((response) => {

				if (!response.success) {

					this.notify.error(
						response.data.message
					);

					return;

				}

				this.notify.success(
					response.data.message
				);

			});

		}

		readForm() {

			this.model.set({

				id: Number(
					$('#hsgcm-id').val()
				),

				general: {

					title: $('#hsgcm-title').val(),

					status: $('#hsgcm-status').val(),

					start_date: $('#hsgcm-start').val(),

					end_date: $('#hsgcm-end').val()

				},

				conditions: {

					products: $('#hsgcm-products').val() || []

				},

				rule: {

					value: $('#hsgcm-price').val()

				},

				coupon: {

					code: $('#hsgcm-coupon').val()

				}

			});

		}

	}

	$(function () {

		window.HSGCM = new CampaignEditor();

	});

})(jQuery);
