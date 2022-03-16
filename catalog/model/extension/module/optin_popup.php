<?php

class ModelExtensionModuleOptinPopup extends Model {
	protected $cacheKey = 'optin_popup_pages';

	public function loadPopupFromUrl($url) {
		$popupId = $this->getPopupIdFromCache($url);
		if (empty($popupId)) return;

		$languageId = (int)$this->config->get('config_language_id');
		$query = $this->db->query("SELECT op.*, opd.title, opd.subtitle, opd.description, opd.image, opd.button_label, opd.button_url
			FROM " .DB_PREFIX. "optin_popup op, " .DB_PREFIX. "optin_popup_description opd
			WHERE op.optin_popup_id = opd.optin_popup_id AND op.optin_popup_id = {$popupId} AND op.status = 1 AND opd.language_id = " .$languageId. " LIMIT 1");
		$popup = $query->row;
		if (!empty($popup) && !empty($popup['styles'])) $popup['styles'] = json_decode($popup['styles'], TRUE);

		return $popup;
	}

	protected function getPopupIdFromCache($url) {
		$cache = $this->cache->get($this->cacheKey);
		if (empty($cache)) $cache = $this->rebuildPageCache();
		if (empty($cache)) return;

		$url = trim($url);
		foreach ($cache as $page => $popupId) {
			$page = trim($page);
			if ($page == $url) return $popupId;

			$search = array('*', '?');
			$replace = array('(.*)', '\?');
			$page = str_replace($search, $replace, $page);
			$result = preg_match('|' .$page. '$|Uis', $url);
			if ($result) return $popupId;
		}
	}

	protected function rebuildPageCache() {
		$cache = array();
		$query = $this->db->query("SELECT opp.* FROM " .DB_PREFIX. "optin_popup op, " .DB_PREFIX. "optin_popup_pages opp
			WHERE op.optin_popup_id = opp.optin_popup_id AND op.status = 1");
		foreach ($query->rows as $row) {
			$cache[$row['page']] = (int)$row['optin_popup_id'];
		}

		$this->cache->set($this->cacheKey, $cache);
		return $cache;
	}
}