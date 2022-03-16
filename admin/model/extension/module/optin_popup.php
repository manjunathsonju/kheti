<?php

class ModelExtensionModuleOptinPopup extends Model {
	protected $cacheKey = 'optin_popup_pages';

	public function loadPopupForEdit($id) {
		$this->load->model('tool/image');
		$placeholder = $this->model_tool_image->resize('no_image.png', 100, 100);

		$query = $this->db->query("SELECT * FROM " .DB_PREFIX. "optin_popup WHERE optin_popup_id = " .$id. " LIMIT 1");
		$popup = $query->row;

		if (!empty($popup)) {
			$query = $this->db->query("SELECT page FROM " .DB_PREFIX. "optin_popup_pages WHERE optin_popup_id = " .(int)$id);
			$popup['pages'] = array();
			foreach ($query->rows as $row) $popup['pages'][] = $row['page'];

			if (!empty($popup['styles'])) $popup['styles'] = json_decode($popup['styles'], TRUE);

			$query = $this->db->query("SELECT * FROM " .DB_PREFIX. "optin_popup_description WHERE optin_popup_id = " .(int)$id);
			$popup['values'] = array();
			foreach ($query->rows as $values) {
				$languageId = $values['language_id'];
				if (!isset($popup['values'][$languageId])) $popup['values'][$languageId] = array();
				$values['image_thumb'] = empty($values['image']) ? $placeholder : $this->model_tool_image->resize($values['image'], 100, 100);
				$popup['values'][$languageId] = $values;
			}
		}

		return $popup;
	}

	public function loadPopups() {
		$query = $this->db->query("SELECT * FROM " .DB_PREFIX. "optin_popup ORDER BY optin_popup_id DESC");
		return $query->rows;
	}

	public function addPopup($values) {
		$values['styles'] = empty($values['styles']) ? '' : json_encode($values['styles']);
		$sql = "INSERT INTO " .DB_PREFIX. "optin_popup (name, status, styles)
			VALUES ('" .$this->db->escape($values['name']). "', " .(int)$values['status']. ", '" .$this->db->escape($values['styles']). "')";
		$this->db->query($sql);

		$id = $this->db->getLastId();
		$this->editPopup($id, $values, TRUE);
		return $id;
	}

	public function editPopup($id, $values, $new = FALSE) {
		if (!$new) {
			$values['styles'] = empty($values['styles']) ? '' : json_encode($values['styles']);
			$sql = "UPDATE " .DB_PREFIX. "optin_popup SET name ='" .$this->db->escape($values['name']). "', status = " .(int)$values['status'].
			", styles = '" .$this->db->escape($values['styles']). "' WHERE optin_popup_id = " .$id;
			$this->db->query($sql);
		}

		$sql = "DELETE FROM " .DB_PREFIX. "optin_popup_description WHERE optin_popup_id = " .$id;
		$this->db->query($sql);
		$sql = "DELETE FROM " .DB_PREFIX. "optin_popup_pages WHERE optin_popup_id = " .$id;
		$this->db->query($sql);

		if (!empty($values['pages'])) {
			$pages = explode(PHP_EOL, $values['pages']);
			$sql = "INSERT INTO " .DB_PREFIX. "optin_popup_pages (optin_popup_id, page) VALUES ";

			$parts = array();
			foreach ($pages as $page) {
				$parts[] = "(" .$id. ", '" .$this->db->escape($page). "')";
			}

			if (!empty($parts)) {
				$this->db->query($sql.implode(', ', $parts));
			}
		}

		$set = array();
		foreach ($values['data'] as $languageId => $data) {
			if (!isset($set[$languageId])) $set[$languageId] = array();
			foreach ($data as $k => $v) {
				if (in_array($k, array('title', 'subtitle', 'description', 'image', 'button_label', 'button_url', 'pages'))) {
					if ($k == 'pages') {
						$lines = explode(PHP_EOL, $v);
						$v = json_encode($lines);
					}
					$set[$languageId][] = $k . "='".$this->db->escape($v)."'";
				}
			}
		}
		foreach ($set as $languageId => $v) {
			$v[] = 'optin_popup_id ='. $id;
			$v[] = 'language_id ='. $languageId;
			$this->db->query("INSERT INTO " .DB_PREFIX. "optin_popup_description SET " .implode (', ', $v));
		}

		$this->rebuildPageCache();
	}

	public function deletePopup($id) {
		$sql = "DELETE FROM " .DB_PREFIX. "optin_popup WHERE optin_popup_id = " .$id;
		$this->db->query($sql);

		$sql = "DELETE FROM " .DB_PREFIX. "optin_popup_description WHERE optin_popup_id = " .$id;
		$this->db->query($sql);

		$sql = "DELETE FROM " .DB_PREFIX. "optin_popup_pages WHERE optin_popup_id = " .$id;
		$this->db->query($sql);

		$this->rebuildPageCache();
	}

	protected function rebuildPageCache() {
		$pages = array();
		$query = $this->db->query("SELECT opp.* FROM " .DB_PREFIX. "optin_popup op, " .DB_PREFIX. "optin_popup_pages opp
			WHERE op.optin_popup_id = opp.optin_popup_id AND op.status = 1");
		foreach ($query->rows as $row) {
			$pages[$row['page']] = (int)$row['optin_popup_id'];
		}

		$this->cache->set($this->cacheKey, $pages);
		return $pages;
	}
}