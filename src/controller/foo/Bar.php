<?php

namespace Tfis\controller\foo;

use Tfis\controller\Base;

class Bar extends Base
{
	function bar()
	{
        $query = $this->db->query("select * from t00");
        echo "nice";
        return $query;
	}
}
