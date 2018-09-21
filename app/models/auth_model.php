<?php

class Auth_model extends Model {
	
    public function getSomething($id)
    {
        $id = $this->escapeString($id);
        $result = $this->query('SELECT * FROM stickylabels_version WHERE 1');
        return $result;
    }
	
	public function checkUserCredentials($user,$pwd,$remember=false)	
	{
		$result = $this->query('SELECT user_id FROM users WHERE user="'.$user.'" AND pwd="'.$pwd.'"');
		return $result;
	}
}
