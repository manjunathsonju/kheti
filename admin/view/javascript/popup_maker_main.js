function SGPopupMaker() {
	this.user_token = '';
	this.container = '';
	this.options_data = [];

	this.layouts_list = '';
	this.categories_list = '';
	this.products_list = '';
}

SGPopupMaker.prototype.init = function() {
	this.layouts_list = OC_LAYOUTS_LIST;
	this.categories_list = OC_CATEGORIES_LIST;
	this.products_list = OC_PRODUCTS_LIST;

	this.container = jQuery('#popup_options');
	this.user_token = jQuery('input[name=oc-user-token]').val();

	this.initNavigationMenues();
	this.initOptionsPanels();
	this.refreshList();
};

SGPopupMaker.prototype.initNavigationMenues = function() {
	jQuery('.sgpm-popups-menu').on('click', function() {
		/** display and hide panels */
		jQuery('.sgpm-popups-panel').addClass('display-block');
		jQuery('.api-credentials-contaner').removeClass('display-block');
		jQuery('.support-container').removeClass('display-block');
		jQuery('.sgpm-popups-panel').removeClass('display-none');
		jQuery('.api-credentials-contaner').addClass('display-none');
		jQuery('.support-container').addClass('display-none');
		jQuery('.popup-options-container').removeClass('display-block');
		/** set active status to nav-menu */
		jQuery('.sgpm-popups-menu').addClass('action-active');
		jQuery('.sgpm-api-credentials-menu').removeClass('action-active');
		jQuery('.sgpm-support-menu').removeClass('action-active');
		/** clear options container */
		jQuery('.dynamic-popup-options').html('');
	});
	jQuery('.sgpm-api-credentials-menu').on('click', function() {
		/** display and hide panels */
		jQuery('.sgpm-popups-panel').removeClass('display-block');
		jQuery('.api-credentials-contaner').addClass('display-block');
		jQuery('.support-container').removeClass('display-block');
		jQuery('.sgpm-popups-panel').addClass('display-none');
		jQuery('.api-credentials-contaner').removeClass('display-none');
		jQuery('.support-container').addClass('display-none');
		jQuery('.popup-options-container').removeClass('display-block');
		/** set active status to nav-menu */
		jQuery('.sgpm-popups-menu').removeClass('action-active');
		jQuery('.sgpm-api-credentials-menu').addClass('action-active');
		jQuery('.sgpm-support-menu').removeClass('action-active');
		/** clear options container */
		jQuery('.dynamic-popup-options').html('');
	});
	jQuery('.sgpm-support-menu').on('click', function() {
		/** displa and hide panels */
		jQuery('.sgpm-popups-panel').removeClass('display-block');
		jQuery('.api-credentials-contaner').removeClass('display-block');
		jQuery('.support-container').addClass('display-block');
		jQuery('.sgpm-popups-panel').addClass('display-none');
		jQuery('.api-credentials-contaner').addClass('display-none');
		jQuery('.support-container').removeClass('display-none');
		jQuery('.popup-options-container').removeClass('display-block');
		/** set active status to nav-menu */
		jQuery('.sgpm-popups-menu').removeClass('action-active');
		jQuery('.sgpm-api-credentials-menu').removeClass('action-active');
		jQuery('.sgpm-support-menu').addClass('action-active');
		/** clear options container */
		jQuery('.dynamic-popup-options').html('');
	});
};

SGPopupMaker.prototype.changeStatus = function(hash_id, element_id) {
	var user_token = this.user_token;

	var status = jQuery('#enable-popup-' + element_id).prop('checked');
	var callback = 'index.php?route=extension/module/popup_maker/changeStatus&user_token=' + user_token;
	var data = {
		id: hash_id,
		status: 'disabled'
	};

	if (status) {
		data.status = 'enabled';
		jQuery('#popup-status-' + element_id).html('Enabled');
		jQuery('#popup-status-' + element_id).removeClass('status-disabled');
		jQuery('#popup-status-' + element_id).addClass('status-enabled');
	} else {
		jQuery('#popup-status-' + element_id).html('Disabled');
		jQuery('#popup-status-' + element_id).removeClass('status-enabled');
		jQuery('#popup-status-' + element_id).addClass('status-disabled');
	}

	jQuery('#toggle-switch-' + element_id).addClass('checbox-disabled');
	jQuery('#enable-popup-' + element_id).prop('disabled', true);

	jQuery.ajax({
		url: callback,
		type: 'post',
		dataType: 'json',
		data: data,
		success: function(responce) {
			if (responce) {
				jQuery('#toggle-switch-' + element_id).removeClass('checbox-disabled');
				jQuery('#enable-popup-' + element_id).prop('disabled', false);
			}
		}
	});
};

SGPopupMaker.prototype.connectToPopupMaker = function() {
	var callback = 'index.php?route=extension/module/popup_maker/connect&user_token=' + this.user_token;
	var api_key = jQuery('.sgpm-api-field').val();

	jQuery.ajax({
		url: callback,
		type: 'post',
		dataType: 'json',
		data: {api: api_key},
		success: function() {
			location.reload();
		}
	});
};

SGPopupMaker.prototype.refreshList = function() {
	var callback = 'index.php?route=extension/module/popup_maker/connect&user_token=' + this.user_token;
	var api_key = jQuery('.sgpm-api-field').val();

	jQuery.post(callback, {api: api_key}, function() { });
};

SGPopupMaker.prototype.initOptionsPanels = function() {
	var api_status = jQuery('.user-api-status').val();

	if (typeof api_status == 'undefined') {
		jQuery('.api-credentials-contaner').addClass('display-block');

	} else {
		jQuery('.api-credentials-contaner').removeClass('display-block');
	}
};

SGPopupMaker.prototype.initTargets = function(el) {
	var that = this;
	var parent_container = jQuery(el).closest('.options-container');
	var closest_page_selector = parent_container.find('.page-select-options');

	jQuery.each(parent_container, function() {
		var targets = jQuery(this).find('.main-target :selected');
		var pages = jQuery(this).find('.targets-list');
		var dropdown = '';

		switch (targets.val()) {
			case 'layouts_selected':
				closest_page_selector.removeClass('display-none');

				for (var i = 0; i < that.layouts_list.length; i++) {
					var layout = that.layouts_list[i];
					dropdown += '<option value="' + layout.data_value + '">' + layout.name + '</option>';
				}

				pages.html('');
				pages.html(dropdown);
				break;

			case 'categories_selected':
				closest_page_selector.removeClass('display-none');

				for (var i = 0; i < that.categories_list.length; i++) {
					var category = that.categories_list[i];
					var category_value = '';
					if (category.path != 0) {
						category_value = category.path + '_' + category.data_value
					} else {
						category_value = category.data_value
					}
					dropdown += '<option value="' + category_value + '">' + category.name + '</option>';
				}

				pages.html('');
				pages.html(dropdown);
				break;

			case 'products_selected':
				closest_page_selector.removeClass('display-none');

				for (var i = 0; i < that.products_list.length; i++) {
					var product = that.products_list[i];
					dropdown += '<option value="' + product.data_value + '">' + product.name + '</option>';
				}

				pages.html('');
				pages.html(dropdown);
				break;

			default:
				closest_page_selector.addClass('display-none');
				break;
		}
	});

	if (jQuery('.page-select-options').not('.display-none').length >= 1) {
		jQuery('.page-type').removeClass('display-none');
	} else {
		jQuery('.page-type').addClass('display-none');
	}
};

SGPopupMaker.prototype.getPopupOptionsByName = function(hash_id) {
	var that = this;
	var callback = 'index.php?route=extension/module/popup_maker/loadOptions&user_token=' + this.user_token;

	jQuery.ajax({
		url: callback,
		type: 'post',
		dataType: 'html',
		data: {id: hash_id},
		success: function(page_data) {
			that.appendOptionsPage(page_data)
		}
	});
};

SGPopupMaker.prototype.appendOptionsPage =  function(page_data) {
	jQuery('.dynamic-popup-options').append(page_data);
	jQuery('#popup-title').html(jQuery('.popup-title-input').val());

	var options = jQuery('.options-container');

	for (var i = 0; i < options.length; i++) {
		var element = jQuery('.options-container')[i];
		var target_data = jQuery(element).find('.main-target :selected');

		if (target_data.val() == 'layouts_all' || target_data.val() == 'categories_all' || target_data.val() == 'products_all') {
			var page_type = jQuery(element).find('.page-select-options');
			page_type.addClass('display-none');
		}
	}

	jQuery('.sgpm-popups-panel').removeClass('display-block');
	jQuery('.sgpm-popups-panel').addClass('display-none');
	jQuery('.popup-options-container').addClass('display-block');

	/** remove button before last child */
	jQuery('.options-container .action-button.add').eq(-2).addClass('display-none');
	jQuery('.options-container:last .action-button.add').removeClass('display-none');

	if (jQuery('.page-select-options').not('.display-none').length >= 1) {
		jQuery('.page-type').removeClass('display-none');
	} else {
		jQuery('.page-type').addClass('display-none');
	}

	if (jQuery('.options-container').length == 1) {
		jQuery('.action-button.remove').prop('disabled', true);
	} else {
		jQuery('.action-button.remove').prop('disabled', false);
	}

	jQuery('.targets-list').select2({
		allowClear: true,
		width: '100%',
		theme: "classic"
	});
};

SGPopupMaker.prototype.removePopupOptionElement = function(el) {
	var parent_container = jQuery(el).closest('.options-container');
	parent_container.remove();

	if (jQuery('.page-select-options').not('.display-none').length >= 1) {
		jQuery('.page-type').removeClass('display-none');
	} else {
		jQuery('.page-type').addClass('display-none');
	}

	if (jQuery('.options-container').length == 1) {
		jQuery('.action-button.remove').prop('disabled', true);
	} else {
		jQuery('.action-button.remove').prop('disabled', false);
	}

	/** remove button before last child */
	jQuery('.options-container .action-button.add').eq(-2).addClass('display-none');
	jQuery('.options-container:last .action-button.add').removeClass('display-none');
};

SGPopupMaker.prototype.returnToMainPanel = function() {
	/** display and hide panels */
	jQuery('.sgpm-popups-panel').addClass('display-block');
	jQuery('.api-credentials-contaner').removeClass('display-block');
	jQuery('.support-container').removeClass('display-block');
	jQuery('.sgpm-popups-panel').removeClass('display-none');
	jQuery('.api-credentials-contaner').addClass('display-none');
	jQuery('.support-container').addClass('display-none');
	jQuery('.popup-options-container').removeClass('display-block');
	/** clear options container */
	jQuery('.dynamic-popup-options').html('');
};

SGPopupMaker.prototype.generateDataObject = function() {
	var that = this;
	this.options_data = [];

	jQuery('.save-popup-targets').prop('disabled', true);

	jQuery('.options-container').each(function() {
		var targets = jQuery(this).find('.main-target :selected');
		var operators = jQuery(this).find('.selected-operator :selected');
		var pages = jQuery(this).find('.targets-list :selected');
		var pages_arr = [];

		for (var i = 0; i < pages.length; i++) {
			var page = pages[i];
			var data = {
				route: page.value.trim(),
				operator: operators.val().trim()
			}

			pages_arr.push(data);
		}

		var data_row = {
			target: targets.val(),
			page: pages_arr
		}
		that.options_data.push(data_row);
	})
	this.savePopupData()
};

SGPopupMaker.prototype.savePopupData = function() {
	var hash_id = jQuery('.popup-hash-id-input').val();
	var callback = 'index.php?route=extension/module/popup_maker/saveOptions&user_token=' + this.user_token;

	var popup_data = [hash_id, this.options_data];

	jQuery.post(callback, {options: popup_data}, function(responce) {
		if (responce) {
			jQuery('.save-popup-targets').prop('disabled', false);
			/** display and hide panels */
			jQuery('.sgpm-popups-panel').addClass('display-block');
			jQuery('.api-credentials-contaner').removeClass('display-block');
			jQuery('.support-container').removeClass('display-block');
			jQuery('.sgpm-popups-panel').removeClass('display-none');
			jQuery('.api-credentials-contaner').addClass('display-none');
			jQuery('.support-container').addClass('display-none');
			jQuery('.popup-options-container').removeClass('display-block');
			/** set active status to nav-menu */
			jQuery('.sgpm-popups-menu').addClass('action-active');
			jQuery('.sgpm-api-credentials-menu').removeClass('action-active');
			jQuery('.sgpm-support-menu').removeClass('action-active');
			/** clear options container */
			jQuery('.dynamic-popup-options').html('');
		}
	});
};

SGPopupMaker.prototype.clearCache = function() {
	var callback = 'index.php?route=marketplace/modification/refresh&user_token=' + this.user_token;

	jQuery.ajax({
		url: callback,
		type: 'post',
		dataType: 'json',
		data: {},
	});
};
