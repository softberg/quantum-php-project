<?php

/**
 * Quantum PHP Framework
 * 
 * An open source software development framework for PHP
 * 
 * @package Quantum
 * @author Arman Ag. <arman.ag@softberg.org>
 * @copyright Copyright (c) 2018 Softberg LLC (https://softberg.org)
 * @link http://quantum.softberg.org/
 * @since 1.0.0
 */

namespace Quantum\Libraries\Sessions;

use ORM;

/**
 * DB Session handler class
 * 
 * @package Quantum
 * @subpackage Libraries.Sessions
 * @category Libraries
 */
class DbSessionHandler extends \SessionHandler {

    /**
     * Initialize session
     */
    public function _open() {}
    
    /**
     * Close the session
     */
    public function _close() {}
    
    /**
     * Read session data
     * 
     * @param string $id The session id
     * @return string
     */
    public function _read($id) {
        return ORM::for_table($this->sessions_table)->findOne($id)->data;
    }
    
    /**
     * Write session data
     * 
     * @param string $id The session id
     * @param mixed $data
     * @return bool
     */
    public function _write($id, $data) {
        $access = time();
        
        ORM::for_table($this->sessions_table)->raw_execute("REPLACE INTO " . $this->sessions_table . " VALUES (:id, :access, :data)", ['id' => $id, 'access' => $access, 'data' => $data]);
    }
    
    /**
     * Destroy a session
     * 
     * @param type $id The session ID
     * @return bool
     */
    public function _destroy($id) {
        ORM::for_table($this->sessions_table)->raw_execute("DELETE FROM " . $this->sessions_table . " WHERE id = :id", ['id' => $id]);
    }
    
    /**
     * Cleanup old sessions
     * 
     * @param int $max Max lifetime
     */
    public function _gc($max) {
        $old = time() - $max;
        
        ORM::for_table($this->sessions_table)->raw_execute("DELETE * FROM " . $this->sessions_table . " WHERE access < :old", ['old' => $old]);
    }
}
