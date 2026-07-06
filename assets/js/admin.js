/**
 * HSG Campaign Manager V2
 * Admin
 */

(function ($) {

	'use strict';

	class HSGCampaignManager {

		constructor() {

			this.form = $('#hsgcm-campaign-form');
			this.panel = $('.hsgcm-form');

			// Formularen findes kun på Campaigns-siden.
			if (!this.form.length) {
				return;
			}

			this.bindEvents();

			console.log('HSG Campaign Manager V2 loaded');

		}

		/**
		 * Register events.
		 */
		bindEvents() {

			$(document).on(
				'click',
				'.hsgcm-new-campaign',
				this.newCampaign.bind(this)
			);

			$(document).on(
				'click',
				'#hsgcm-reset',
				this.newCampaign.bind(this)
			);

			$(document).on(
				'click',
				'.hsgcm-edit-campaign',
				this.editCampaign.bind(this)
			);

			$(document).on(
				'click',
				'.hsgcm-delete-campaign',
				this.deleteCampaign.bind(this)
			);

			$(document).on(
				'click',
				'.hsgcm-duplicate-campaign',
				this.duplicateCampaign.bind(this)
			);

			this.form.on(
				'submit',
				this.saveCampaign.bind(this)
			);

		}

		/**
		 * Reset form.
		 */
		newCampaign(e) {

			if (e) {
				e.preventDefault();
			}

			this.form.trigger('reset');

			$('#hsgcm-id').val('');
			$('#hsgcm-status').val('draft');
			$('#hsgcm-form-title').text('New Campaign');

			this.clearMessages();

		}

		/**
		 * Show loading.
		 */
		showLoading(button) {

			button.data('original-text', button.text());

			button.prop('disabled', true);

			button.text('Saving...');

		}

		/**
		 * Hide loading.
		 */
		hideLoading(button) {

			button.prop('disabled', false);

			button.text(button.data('original-text'));

		}

		/**
		 * Remove notifications.
		 */
		clearMessages() {

			$('.hsgcm-message').remove();

		}

		/**
		 * Success message.
		 */
		showSuccess(message) {

			this.clearMessages();

			this.panel.prepend(
				'<div class="notice notice-success hsgcm-message"><p>' +
				message +
				'</p></div>'
			);

		}

		/**
		 * Error message.
		 */
		showError(message) {

			this.clearMessages();

			this.panel.prepend(
				'<div class="notice notice-error hsgcm-message"><p>' +
				message +
				'</p></div>'
			);

		}

		/**
		 * Edit campaign.
		 */
		editCampaign(e) {

			e.preventDefault();

			const id = $(e.currentTarget).data('id');

			$.post(
				hsgcmAdmin.ajaxUrl,
				{
					action: 'hsgcm_get_campaign',
					nonce: hsgcmAdmin.nonce,
					id: id
				}
			).done((response) => {

				if (!response.success) {

					this.showError(response.data.message);

					return;

				}

				const c = response.data;

				$('#hsgcm-id').val(c.id);
				$('#hsgcm-title').val(c.title);
				$('#hsgcm-status').val(c.status);
				$('#hsgcm-coupon').val(c.coupon);
				$('#hsgcm-price').val(c.price);
				$('#hsgcm-start').val(c.start);
				$('#hsgcm-end').val(c.end);

				$('#hsgcm-form-title').text('Edit Campaign');

				$('html, body').animate({
					scrollTop: this.panel.offset().top - 30
				}, 300);

			});

		}

		/**
		 * Save campaign.
		 */
		saveCampaign(e) {

			e.preventDefault();

			const button = $('#hsgcm-save');

			this.showLoading(button);

			$.post(
				hsgcmAdmin.ajaxUrl,
				{
					action: 'hsgcm_save_campaign',
					nonce: hsgcmAdmin.nonce,
					id: $('#hsgcm-id').val(),
					title: $('#hsgcm-title').val(),
					status: $('#hsgcm-status').val(),
					coupon: $('#hsgcm-coupon').val(),
					price: $('#hsgcm-price').val(),
					start: $('#hsgcm-start').val(),
					end: $('#hsgcm-end').val()
				}
			).done((response) => {

				this.hideLoading(button);

				if (!response.success) {

					this.showError(response.data.message);

					return;

				}

				this.showSuccess(response.data.message);

				// Midlertidigt indtil vi laver live rendering.
				setTimeout(() => {
					location.reload();
				}, 500);

			}).fail(() => {

				this.hideLoading(button);

				this.showError('Unexpected server error.');

			});

		}

		/**
		 * Delete campaign.
		 */
		deleteCampaign(e) {

			e.preventDefault();

			if (!confirm('Delete this campaign?')) {
				return;
			}

			const id = $(e.currentTarget).data('id');

			$.post(
				hsgcmAdmin.ajaxUrl,
				{
					action: 'hsgcm_delete_campaign',
					nonce: hsgcmAdmin.nonce,
					id: id
				}
			).done((response) => {

				if (!response.success) {

					this.showError(response.data.message);

					return;

				}

				location.reload();

			});

		}

		/**
		 * Duplicate campaign.
		 */
		duplicateCampaign(e) {

			e.preventDefault();

			const id = $(e.currentTarget).data('id');

			$.post(
				hsgcmAdmin.ajaxUrl,
				{
					action: 'hsgcm_duplicate_campaign',
					nonce: hsgcmAdmin.nonce,
					id: id
				}
			).done((response) => {

				if (!response.success) {

					this.showError(response.data.message);

					return;

				}

				location.reload();

			});

		}

	}

	$(document).ready(function () {

		window.HSGCM = new HSGCampaignManager();

	});

})(jQuery);
