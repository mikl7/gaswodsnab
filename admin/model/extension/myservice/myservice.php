<?php

class ModelExtensionMyserviceMyservice extends Model
{
    public function addService($data)
    {
        $this->db->query("INSERT INTO " . DB_PREFIX . "service SET price = '" . (float)$data['price'] . "', date_added = NOW(), date_modified = NOW()");

        $service_id = $this->db->getLastId();

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "service SET image = '" . $this->db->escape($data['image']) . "' WHERE service_id = '" . (int)$service_id . "'");
        }

        foreach ($data['service_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "service_description SET service_id = '" . (int)$service_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }

        if (isset($data['service_image'])) {
            foreach ($data['service_image'] as $service_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "service_image SET service_id = '" . (int)$service_id . "', image = '" . $this->db->escape($service_image['image']) . "', sort_order = '" . (int)$service_image['sort_order'] . "'");
            }
        }



        $this->cache->delete('service');

        return $service_id;
    }

    public function editService($service_id, $data)
    {
        $this->db->query("UPDATE " . DB_PREFIX . "service SET price = '" . (float)$data['price'] . "', date_modified = NOW() WHERE service_id = '" . (int)$service_id . "'");

        if (isset($data['image'])) {
            $this->db->query("UPDATE " . DB_PREFIX . "service SET image = '" . $this->db->escape($data['image']) . "' WHERE service_id = '" . (int)$service_id . "'");
        }

        $this->db->query("DELETE FROM " . DB_PREFIX . "service_description WHERE service_id = '" . (int)$service_id . "'");

        foreach ($data['service_description'] as $language_id => $value) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "service_description SET service_id = '" . (int)$service_id . "', language_id = '" . (int)$language_id . "', name = '" . $this->db->escape($value['name']) . "', description = '" . $this->db->escape($value['description']) . "', meta_title = '" . $this->db->escape($value['meta_title']) . "', meta_description = '" . $this->db->escape($value['meta_description']) . "', meta_keyword = '" . $this->db->escape($value['meta_keyword']) . "'");
        }



        $this->db->query("DELETE FROM " . DB_PREFIX . "service_image WHERE service_id = '" . (int)$service_id . "'");

        if (isset($data['service_image'])) {
            foreach ($data['service_image'] as $service_image) {
                $this->db->query("INSERT INTO " . DB_PREFIX . "service_image SET service_id = '" . (int)$service_id . "', image = '" . $this->db->escape($service_image['image']) . "', sort_order = '" . (int)$service_image['sort_order'] . "'");
            }
        }
    }

    public function deleteService($service_id)
    {
        $this->db->query("DELETE FROM " . DB_PREFIX . "service WHERE service_id = '" . (int)$service_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "service_description WHERE service_id = '" . (int)$service_id . "'");
        $this->db->query("DELETE FROM " . DB_PREFIX . "service_image WHERE service_id = '" . (int)$service_id . "'");

        $this->cache->delete('service');
    }


    public function getService($service_id)
    {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "service s LEFT JOIN " . DB_PREFIX . "service_description sd ON (s.service_id = sd.service_id) WHERE s.service_id = '" . (int)$service_id . "' AND sd.language_id = '" . (int)$this->config->get('config_language_id') . "'");

        return $query->row;
    }

    public function getServices($data = array())
    {
        $sql = "SELECT * FROM " . DB_PREFIX . "service s LEFT JOIN " . DB_PREFIX . "service_description sd ON (s.service_id = sd.service_id) WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND pd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (!empty($data['filter_price'])) {
            $sql .= " AND p.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
        }

        $sql .= " GROUP BY s.service_id";

        $sort_data = array(
            'sd.name',
            's.price',
        );

        if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
            $sql .= " ORDER BY " . $data['sort'];
        } else {
            $sql .= " ORDER BY sd.name";
        }

        if (isset($data['order']) && ($data['order'] == 'DESC')) {
            $sql .= " DESC";
        } else {
            $sql .= " ASC";
        }

        if (isset($data['start']) || isset($data['limit'])) {
            if ($data['start'] < 0) {
                $data['start'] = 0;
            }

            if ($data['limit'] < 1) {
                $data['limit'] = 20;
            }

            $sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
        }

        $query = $this->db->query($sql);

        return $query->rows;
    }
    public function getServiceDescriptions($service_id)
    {
        $service_description_data = array();

        $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "service_description WHERE service_id = '" . (int)$service_id . "'");

        foreach ($query->rows as $result) {
            $service_description_data[$result['language_id']] = array(
                'name'             => $result['name'],
                'description'      => $result['description'],
                'meta_title'       => $result['meta_title'],
                'meta_description' => $result['meta_description'],
                'meta_keyword'     => $result['meta_keyword'],
            );
        }

        return $service_description_data;
    }
    public function getTotalServices($data = array())
    {
        $sql = "SELECT COUNT(DISTINCT s.service_id) AS total FROM " . DB_PREFIX . "service s LEFT JOIN " . DB_PREFIX . "service_description sd ON (s.service_id = sd.service_id)";

        $sql .= " WHERE sd.language_id = '" . (int)$this->config->get('config_language_id') . "'";

        if (!empty($data['filter_name'])) {
            $sql .= " AND sd.name LIKE '" . $this->db->escape($data['filter_name']) . "%'";
        }

        if (isset($data['filter_price']) && !is_null($data['filter_price'])) {
            $sql .= " AND s.price LIKE '" . $this->db->escape($data['filter_price']) . "%'";
        }

        $query = $this->db->query($sql);

        return $query->row['total'];
    }
}
