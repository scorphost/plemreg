<?php
$self_name = 'WrapMySQLi';

class WrapMySQLi
{
    const host = 'localhost';
    protected $dbh;
    protected $ern;
    protected $ert;
    protected $base;
    public function __construct($CONF) {
    	if ( ! ke('host', $CONF) ) $host = $this::host;
    	list ($user, $pass, $base) = av(give('user,pass,base', $CONF));
        $this->base = $base;
        $this->dbh = @new mysqli($host, $user, $pass, $base);
        if ( $this->dbh->connect_errno ) {
            $this->ert = $this->dbh->connect_error;
            $this->ern = $this->dbh->connect_errno;
            $this->dbh = null;
            hitch(page_dbx);
        } else {
            $this->dbh->query('SET NAMES utf8');
            $this->dbh->set_charset('utf8');
        }
    }
    public function base() { return $this->base; }
    public function obj() { return($this); }
    public function ready() { return the($this->dbh); }
    public function last_id() { return $this->dbh->insert_id; }
    public function run($SQL, $TYPE, & $FAKE = no) {
        $qh = $this->dbh->query($SQL);
        if ( yes($qh) ) return $qh;
          else if ( ! the($qh) ) return nil($FAKE = yes);
        if ( arr($TYPE) ) {
            $res = array();
            while ( $Row = $qh->fetch_assoc() ) $res[] = $Row;
        } else {
            $res = $qh->fetch_assoc();
            if ( skip($TYPE) ) return $res;
                else if ( inat($TYPE) ) $res = nat(one($res));
                    else if ( inum($TYPE) ) $res = num(one($res));
                        else if ( str($TYPE) ) $res = text(one($res));
        }
        $qh->free();
        return $res;
    }
}

return $self_name;
