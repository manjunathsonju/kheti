<?php
class ModelExtensionModulePopupMaker extends Model {
	const TABLE = 'popup_maker';

	public function getPopups() {
		$query = 'SELECT `option_data` FROM '.DB_PREFIX.self::TABLE.' WHERE 1';
		$popups = $this->db->query($query);
		$options = $popups->row['option_data'];
		return unserialize($options);
	}
}
